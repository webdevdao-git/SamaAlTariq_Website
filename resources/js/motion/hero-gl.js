import { subscribeToScroll, prefersReducedMotion } from './scroll-engine';

/**
 * The landing hero's photograph, redrawn in WebGL so it can bend.
 *
 * Under the pointer the picture swells like glass held over it, with a faint
 * ripple running out from the centre and the three colour channels pulled a
 * hair apart at the rim. Moving the pointer fast, or scrolling, pulls them
 * further. At rest every one of those terms is zero and the shader samples the
 * photograph straight — the canvas then shows exactly what the <img> under it
 * shows, cropped the same way object-fit: cover crops it.
 *
 * Layered between the <img> and the gradients, inside the media box, so it
 * rides the scroll sink and push-in that parallax.js already applies to that
 * box, and the gradients still darken it exactly as they darken the photo.
 *
 * The <img> stays. It is what paints first, what a crawler reads, and what is
 * left if WebGL is unavailable, the context is lost, or the texture fails —
 * the canvas only fades in once it has a frame to show.
 *
 * Alongside it, the pointer gives the hero depth: the photograph and the
 * headline slide a few pixels in opposite directions, so the type reads as
 * standing in front of the room rather than printed on it.
 *
 * Desktop pointers only. On a phone there is no hover to drive it, and a
 * full-screen shader is the last thing its battery needs.
 */
const VERT = `
attribute vec2 aPos;
varying vec2 vUv;
void main() {
    vUv = aPos * 0.5 + 0.5;
    gl_Position = vec4(aPos, 0.0, 1.0);
}`;

const FRAG = `
precision highp float;
varying vec2 vUv;
uniform sampler2D uTex;
uniform vec2 uRes;
uniform vec2 uImg;
uniform vec2 uMouse;
uniform float uHover;
uniform float uSpeed;
uniform float uScroll;
uniform float uTime;

// object-fit: cover, centred.
vec2 cover(vec2 p) {
    vec2 s = uRes / uImg;
    float k = max(s.x, s.y);
    vec2 size = uImg * k;
    vec2 off = (uRes - size) * 0.5;
    return clamp((p * uRes - off) / size, 0.0, 1.0);
}

void main() {
    float aspect = uRes.x / uRes.y;
    vec2 d = vUv - uMouse;
    d.x *= aspect;
    float r = length(d);

    float f = smoothstep(0.32, 0.0, r) * uHover;
    vec2 dir = d / max(r, 1e-4);

    vec2 disp = -d * f * 0.14;
    disp += dir * sin(r * 42.0 - uTime * 3.6) * 0.0045 * f;
    disp.y += sin(vUv.x * 7.0 + uTime * 1.8) * uScroll * 0.006;
    disp.x /= aspect;

    float split = f * 0.006 + uSpeed * 0.012 + abs(uScroll) * 0.003;
    vec2 off = dir * split;
    off.x /= aspect;

    vec2 p = vUv + disp;
    float rC = texture2D(uTex, cover(p + off)).r;
    float gC = texture2D(uTex, cover(p)).g;
    float bC = texture2D(uTex, cover(p - off)).b;

    gl_FragColor = vec4(rC, gC, bC, 1.0);
}`;

function compile(gl, type, source) {
    const shader = gl.createShader(type);
    gl.shaderSource(shader, source);
    gl.compileShader(shader);
    if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) throw new Error(gl.getShaderInfoLog(shader));
    return shader;
}

function initDepth(hero, media, layers) {
    const state = { x: 0, y: 0, tx: 0, ty: 0, frame: 0 };

    // Enough headroom that the slide can never show an edge of the photograph.
    media.style.scale = '1.04';

    const render = () => {
        state.x += (state.tx - state.x) * 0.07;
        state.y += (state.ty - state.y) * 0.07;

        media.style.translate = `${(state.x * -16).toFixed(2)}px ${(state.y * -12).toFixed(2)}px`;
        for (const [node, depth] of layers) {
            node.style.translate = `${(state.x * depth).toFixed(2)}px ${(state.y * depth * 0.6).toFixed(2)}px`;
        }

        const settled = Math.abs(state.tx - state.x) < 0.001 && Math.abs(state.ty - state.y) < 0.001;
        state.frame = settled ? 0 : requestAnimationFrame(render);
    };

    const wake = () => {
        if (!state.frame) state.frame = requestAnimationFrame(render);
    };

    hero.addEventListener('pointermove', (event) => {
        if (event.pointerType !== 'mouse') return;
        state.tx = (event.clientX / window.innerWidth - 0.5) * 2;
        state.ty = (event.clientY / window.innerHeight - 0.5) * 2;
        wake();
    });

    hero.addEventListener('pointerleave', () => {
        state.tx = 0;
        state.ty = 0;
        wake();
    });
}

function initShader(hero, media, img) {
    const canvas = document.createElement('canvas');
    canvas.className = 'hero-gl';
    canvas.setAttribute('aria-hidden', 'true');

    const gl = canvas.getContext('webgl', { antialias: false, alpha: false, powerPreference: 'low-power' });
    if (!gl) return;

    let program;
    try {
        program = gl.createProgram();
        gl.attachShader(program, compile(gl, gl.VERTEX_SHADER, VERT));
        gl.attachShader(program, compile(gl, gl.FRAGMENT_SHADER, FRAG));
        gl.linkProgram(program);
        if (!gl.getProgramParameter(program, gl.LINK_STATUS)) return;
    } catch {
        return;
    }
    gl.useProgram(program);

    const buffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
    const aPos = gl.getAttribLocation(program, 'aPos');
    gl.enableVertexAttribArray(aPos);
    gl.vertexAttribPointer(aPos, 2, gl.FLOAT, false, 0, 0);

    const u = Object.fromEntries(
        ['uTex', 'uRes', 'uImg', 'uMouse', 'uHover', 'uSpeed', 'uScroll', 'uTime']
            .map((name) => [name, gl.getUniformLocation(program, name)]),
    );

    const state = {
        mouse: { x: 0.5, y: 0.5 },
        target: { x: 0.5, y: 0.5 },
        hover: 0,
        hoverTarget: 0,
        speed: 0,
        scroll: 0,
        frame: 0,
        ready: false,
        visible: true,
    };

    const resize = () => {
        const dpr = Math.min(window.devicePixelRatio || 1, 1.5);
        const rect = media.getBoundingClientRect();
        // The box before parallax.js scales it — offsetWidth ignores transforms.
        canvas.width = Math.max(1, Math.round(media.offsetWidth * dpr));
        canvas.height = Math.max(1, Math.round(media.offsetHeight * dpr));
        gl.viewport(0, 0, canvas.width, canvas.height);
        gl.uniform2f(u.uRes, canvas.width, canvas.height);
        return rect;
    };

    const draw = (now) => {
        state.frame = 0;

        state.mouse.x += (state.target.x - state.mouse.x) * 0.1;
        state.mouse.y += (state.target.y - state.mouse.y) * 0.1;
        state.hover += (state.hoverTarget - state.hover) * 0.06;
        state.speed *= 0.92;
        state.scroll *= 0.9;

        gl.uniform2f(u.uMouse, state.mouse.x, state.mouse.y);
        gl.uniform1f(u.uHover, state.hover);
        gl.uniform1f(u.uSpeed, Math.min(state.speed, 1));
        gl.uniform1f(u.uScroll, Math.max(-1, Math.min(1, state.scroll)));
        gl.uniform1f(u.uTime, now / 1000);
        gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);

        // Runs only while something is still moving; at rest the last frame
        // is the plain photograph and nothing needs redrawing.
        const busy = state.hover > 0.002 || state.speed > 0.002 || Math.abs(state.scroll) > 0.002;
        if (busy && state.visible) state.frame = requestAnimationFrame(draw);
    };

    const wake = () => {
        if (state.ready && state.visible && !state.frame) state.frame = requestAnimationFrame(draw);
    };

    const texture = new Image();
    texture.decoding = 'async';
    texture.onload = () => {
        const tex = gl.createTexture();
        gl.bindTexture(gl.TEXTURE_2D, tex);
        gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);
        gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGB, gl.RGB, gl.UNSIGNED_BYTE, texture);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
        gl.uniform1i(u.uTex, 0);
        gl.uniform2f(u.uImg, texture.naturalWidth, texture.naturalHeight);

        const mount = () => {
            img.after(canvas);
            resize();
            state.ready = true;
            draw(performance.now());
            canvas.classList.add('is-ready');
        };

        // The landing page opens its photograph with a zoom (scenes.js); the
        // canvas only goes over it once that has finished. 3.5s is the cap in
        // case the opening never runs.
        if (window.__heroOpened) {
            mount();
        } else {
            let mounted = false;
            const once = () => {
                if (mounted) return;
                mounted = true;
                mount();
            };
            window.addEventListener('hero:opened', once, { once: true });
            setTimeout(once, 3500);
        }
    };
    texture.src = img.currentSrc || img.src;

    new ResizeObserver(() => {
        if (!state.ready) return;
        resize();
        draw(performance.now());
    }).observe(media);

    // A lost context leaves a black rectangle; hand the hero back to the <img>.
    canvas.addEventListener('webglcontextlost', () => {
        state.ready = false;
        canvas.remove();
    });

    let last = null;
    hero.addEventListener('pointermove', (event) => {
        if (event.pointerType !== 'mouse') return;

        const rect = media.getBoundingClientRect();
        state.target.x = (event.clientX - rect.left) / rect.width;
        state.target.y = 1 - (event.clientY - rect.top) / rect.height;
        state.hoverTarget = 1;

        if (last) {
            const moved = Math.hypot(event.clientX - last.x, event.clientY - last.y);
            state.speed = Math.max(state.speed, Math.min(1, moved / 60));
        }
        last = { x: event.clientX, y: event.clientY };
        wake();
    });

    hero.addEventListener('pointerleave', () => {
        state.hoverTarget = 0;
        last = null;
        wake();
    });

    let lastY = window.scrollY;
    subscribeToScroll(({ y, vh }) => {
        const delta = y - lastY;
        lastY = y;
        state.visible = y < vh * 1.2;
        if (delta === 0 || !state.visible) return;
        state.scroll = Math.max(-1, Math.min(1, state.scroll + delta / 120));
        wake();
    });
}

export function initHeroGl() {
    const hero = document.querySelector('[data-hero]');
    const media = hero?.querySelector('[data-hero-media]');
    const img = media?.querySelector('img');
    if (!hero || !media || !img || prefersReducedMotion()) return;
    if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

    const layers = [
        [hero.querySelector('h1'), 14],
        [hero.querySelector('.border-t'), 6],
    ].filter(([node]) => node);

    initDepth(hero, media, layers);
    initShader(hero, media, img);
}
