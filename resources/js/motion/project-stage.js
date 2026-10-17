import * as THREE from 'three';
import { RoundedBoxGeometry } from 'three/examples/jsm/geometries/RoundedBoxGeometry.js';
import { RoomEnvironment } from 'three/examples/jsm/environments/RoomEnvironment.js';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { prefersReducedMotion } from './scroll-engine';

gsap.registerPlugin(ScrollTrigger);

/**
 * The featured projects as a 3D stage: every project a thick, lit panel —
 * the photograph on its face, stone on its edges — standing in a line on a
 * dark floor and walked through by the scroll.
 *
 * The section pins for as long as it takes to bring each project to the
 * front once. The one in front is the largest and turns square to the
 * viewer; its neighbours lean away and recede into fog, so the line reads as
 * depth rather than as a row. The scroll snaps project to project, so a stop
 * always lands on one, named underneath: category and location, and the
 * title as a link. The line closes on a teal panel that leads to the rest.
 *
 * As the section first arrives the panels fly in from above and behind,
 * turning as they come, and settle into the line one after another.
 *
 * Built only when WebGL is there and motion is wanted. Otherwise the section
 * stays the grid it is drawn as, and nothing here runs.
 */
const PANEL_W = 2.4;
// Close to the covers' own proportion (about 1.2:1), so a panel crops
// little off a photograph rather than a band from top and bottom.
const PANEL_H = 1.82;
const PANEL_D = 0.16;
const FACE = PANEL_W / PANEL_H;

const clamp = (v, lo, hi) => Math.min(hi, Math.max(lo, v));
const easeOut = (t) => 1 - Math.pow(1 - t, 3);

function webgl() {
    try {
        const canvas = document.createElement('canvas');
        return Boolean(canvas.getContext('webgl2') || canvas.getContext('webgl'));
    } catch {
        return false;
    }
}

/** object-fit: cover for a texture on a face of a known aspect. */
function coverFit(texture) {
    const image = texture.image;
    const aspect = image.width / image.height;
    texture.repeat.set(1, 1);
    texture.offset.set(0, 0);
    if (aspect > FACE) {
        texture.repeat.x = FACE / aspect;
        texture.offset.x = (1 - texture.repeat.x) / 2;
    } else {
        texture.repeat.y = aspect / FACE;
        texture.offset.y = (1 - texture.repeat.y) / 2;
    }
}

/**
 * The closing panel's face: teal, with the count of further projects large
 * in the display face and the button's label beneath it, an arrow ruled out
 * after. Drawn to a canvas at the panel's own proportion.
 */
function ctaTexture(item, renderer) {
    const w = 1500;
    const h = Math.round(w / FACE);
    const canvas = document.createElement('canvas');
    canvas.width = w;
    canvas.height = h;
    const ctx = canvas.getContext('2d');

    const ground = ctx.createLinearGradient(0, 0, w, h);
    ground.addColorStop(0, '#46b3bf');
    ground.addColorStop(1, '#2b7c86');
    ctx.fillStyle = ground;
    ctx.fillRect(0, 0, w, h);

    // A fine rule inset all round, like the hairlines the page is set on.
    ctx.strokeStyle = 'rgba(255,255,255,0.35)';
    ctx.lineWidth = 3;
    ctx.strokeRect(48, 48, w - 96, h - 96);

    const root = getComputedStyle(document.documentElement);
    const display = root.getPropertyValue('--font-display') || 'Georgia, serif';
    const sans = root.getPropertyValue('--font-sans') || 'sans-serif';

    // The invitation, two lines in the display face, and the button's own
    // label beneath with an arrow ruled out after it. No count of what is
    // left — the panel only says there is more.
    ctx.fillStyle = '#fff';
    ctx.textBaseline = 'alphabetic';
    ctx.font = `500 ${Math.round(h * 0.2)}px ${display}`;
    ctx.fillText('EXPLORE', 110, h * 0.42);
    ctx.fillText('MORE', 110, h * 0.62);

    ctx.font = `600 ${Math.round(h * 0.05)}px ${sans}`;
    const label = item.title.toUpperCase().split('').join(String.fromCharCode(8202));
    ctx.fillText(label, 116, h * 0.82);

    const y = h * 0.805;
    const textEnd = 116 + ctx.measureText(label).width + 40;
    ctx.strokeStyle = '#fff';
    ctx.lineWidth = 5;
    ctx.beginPath();
    ctx.moveTo(textEnd, y);
    ctx.lineTo(w - 140, y);
    ctx.moveTo(w - 170, y - 26);
    ctx.lineTo(w - 140, y);
    ctx.lineTo(w - 170, y + 26);
    ctx.stroke();

    const texture = new THREE.CanvasTexture(canvas);
    texture.colorSpace = THREE.SRGBColorSpace;
    texture.anisotropy = renderer.capabilities.getMaxAnisotropy();
    return texture;
}

export function initProjectStage() {
    const section = document.querySelector('#projects');
    const stage = section?.querySelector('[data-stage]');
    if (!stage || prefersReducedMotion() || !webgl()) return;

    let items;
    try {
        items = JSON.parse(stage.dataset.items);
    } catch {
        return;
    }
    if (!items?.length) return;

    /*
     * The closing panel. After the featured projects the line ends on a slab
     * of the brand's teal carrying how many more there are and the section's
     * own button label; brought to the front, the caption becomes that link.
     */
    const featured = items.length;
    // The closing panel only where there is somewhere further to go.
    if (stage.dataset.moreLabel) items.push({
        cta: true,
        title: stage.dataset.moreLabel || 'View All Projects',
        category: '',
        location: null,
        href: stage.dataset.moreHref || '/projects',
    });

    const canvas = stage.querySelector('[data-stage-canvas]');
    const title = stage.querySelector('[data-stage-title]');
    const category = stage.querySelector('[data-stage-category]');
    const location = stage.querySelector('[data-stage-location]');
    const link = stage.querySelector('[data-stage-link]');
    const index = stage.querySelector('[data-stage-index]');
    const count = stage.querySelector('.project-stage__count');
    const info = stage.querySelector('.project-stage__info');

    stage.hidden = false;
    section.classList.add('has-stage');

    /* ---------------- scene ---------------- */

    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true, powerPreference: 'high-performance' });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.75));
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.05;
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;

    const scene = new THREE.Scene();
    // Fog the page's own off-white, so far panels fade into the ground.
    scene.fog = new THREE.Fog(0xf9f9f9, 9, 24);

    const pmrem = new THREE.PMREMGenerator(renderer);
    scene.environment = pmrem.fromScene(new RoomEnvironment(), 0.04).texture;
    pmrem.dispose();

    const camera = new THREE.PerspectiveCamera(32, 1, 0.1, 60);
    // Aimed a little below the panels' centre, which lifts the line up the
    // screen and clear of the caption underneath it.
    const look = new THREE.Vector3(0, -0.2, 0);

    scene.add(new THREE.AmbientLight(0xffffff, 0.35));

    const key = new THREE.DirectionalLight(0xfff1e0, 2.4);
    key.position.set(3.5, 6, 5);
    key.castShadow = true;
    key.shadow.mapSize.set(2048, 2048);
    key.shadow.camera.left = -14;
    key.shadow.camera.right = 14;
    key.shadow.camera.top = 6;
    key.shadow.camera.bottom = -6;
    key.shadow.bias = -0.0004;
    key.shadow.radius = 6;
    scene.add(key);

    // The brand's teal, as a rim from behind and to the left: it is what
    // catches the stone edges and separates the panels from the dark.
    const rim = new THREE.DirectionalLight(0x3fa7b3, 2.2);
    rim.position.set(-5, 2.5, -4);
    scene.add(rim);

    const floor = new THREE.Mesh(
        new THREE.PlaneGeometry(80, 40),
        new THREE.ShadowMaterial({ opacity: 0.45 }),
    );
    floor.rotation.x = -Math.PI / 2;
    floor.position.y = -PANEL_H / 2 - 0.42;
    floor.receiveShadow = true;
    scene.add(floor);

    const geometry = new RoundedBoxGeometry(PANEL_W, PANEL_H, PANEL_D, 4, 0.035);
    const edge = new THREE.MeshStandardMaterial({ color: 0x4a4440, roughness: 0.48, metalness: 0.3 });
    const loader = new THREE.TextureLoader();
    const maxAniso = renderer.capabilities.getMaxAnisotropy();

    const panels = items.map((item, i) => {
        /*
         * The photograph is not lit. Through the scene's lights and the
         * filmic tone mapping it came out washed and grey — a white room went
         * to paper — so the face shows the picture as it was taken, and only
         * the stone edges take the light.
         */
        const face = new THREE.MeshBasicMaterial({ color: 0x1b1b1b, toneMapped: false });

        if (item.cta) {
            const paint = () => {
                face.map = ctaTexture(item, renderer);
                face.color.set(0xffffff);
                face.needsUpdate = true;
                wake();
            };
            // Next frame, not now: the render loop this wakes is declared
            // further down, and is not there yet while the panels are built.
            requestAnimationFrame(paint);
            document.fonts?.ready.then(() => requestAnimationFrame(paint));
        } else loader.load(item.cover, (texture) => {
            texture.colorSpace = THREE.SRGBColorSpace;
            texture.anisotropy = maxAniso;
            coverFit(texture);
            face.map = texture;
            face.color.set(0xffffff);
            face.needsUpdate = true;
            wake();
        });

        // Box face order: +x, -x, +y, -y, +z (front), -z (back).
        const mesh = new THREE.Mesh(geometry, [edge, edge, edge, edge, face, edge]);
        mesh.castShadow = true;
        mesh.userData.index = i;

        const group = new THREE.Group();
        group.add(mesh);
        scene.add(group);

        return { group, mesh, seed: i * 1.37 };
    });

    /* ---------------- state ---------------- */

    const state = {
        target: 0,
        current: 0,
        intro: 0,
        mx: 0,
        my: 0,
        tmx: 0,
        tmy: 0,
        visible: false,
        shown: -1,
        hover: false,
    };

    let wide = window.innerWidth >= 1024;

    const resize = () => {
        const w = stage.clientWidth;
        const h = stage.clientHeight;
        wide = window.innerWidth >= 1024;
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        // Narrow screens stand further back so the front panel still fits.
        camera.position.set(0, wide ? 1.15 : 1.3, wide ? 8.6 : 12);
        camera.updateProjectionMatrix();
        wake();
    };

    /* ---------------- caption ---------------- */

    const show = (i) => {
        if (i === state.shown) return;
        const first = state.shown === -1;
        state.shown = i;

        const item = items[i];
        const write = () => {
            title.textContent = item.title;
            category.textContent = item.category;
            location.textContent = item.location ?? '';
            location.hidden = !item.location;
            link.href = item.href;
            index.textContent = String(Math.min(i + 1, featured)).padStart(2, '0');
            count.style.visibility = item.cta ? 'hidden' : '';
            info.classList.toggle('is-cta', Boolean(item.cta));
        };

        if (first) {
            write();
            return;
        }

        gsap.timeline()
            .to(info, { y: -18, opacity: 0, duration: 0.22, ease: 'power2.in', onComplete: write })
            .fromTo(info, { y: 22, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, ease: 'expo.out' });
    };

    /* ---------------- frame ---------------- */

    let frame = 0;
    const clock = new THREE.Clock();

    const render = () => {
        frame = 0;
        const t = clock.getElapsedTime();

        state.current += (state.target - state.current) * 0.085;
        state.mx += (state.tmx - state.mx) * 0.05;
        state.my += (state.tmy - state.my) * 0.05;

        const f = state.current;
        const spacing = wide ? 2.95 : 2.75;

        panels.forEach((panel, i) => {
            const d = i - f;
            const a = Math.abs(d);
            const front = 1 - clamp(a, 0, 1);

            // Spread that tightens with distance, so far panels bunch into
            // the fog instead of running off the side of the screen.
            const x = Math.sign(d) * Math.pow(a, 0.8) * spacing;
            const z = -a * 1.6 + front * 0.7;
            const y = Math.sin(t * 0.9 + panel.seed) * 0.035;

            /*
             * Every panel is turned, the front one included — about 15
             * degrees, enough that its stone edge shows and it reads as an
             * object standing on the floor rather than a picture facing the
             * screen. The ones either side swing further away, and the whole
             * line leans a few degrees, as if set down by hand.
             */
            // Nearly square to the viewer in front — just enough turn to show
            // the stone edge — and the ones either side swung away evenly, so
            // the line reads as a fan round the front panel, not a slope.
            let rx = -0.05 + Math.sin(t * 0.7 + panel.seed) * 0.01;
            let ry = -0.12 * front - clamp(d, -1.6, 1.6) * 0.42;
            let rz = Math.sin(t * 0.5 + panel.seed) * 0.008;
            const s = 1 + front * 0.18;

            // The fly-in: from up and behind, turning, one after another
            // outward from the front.
            const order = Math.min(a, 6) / 6;
            const e = easeOut(clamp((state.intro - order * 0.45) / 0.55, 0, 1));
            const k = 1 - e;

            panel.group.position.set(x - k * 5.5, y + k * 4.2, z - k * 6);
            rx += k * 1.1;
            ry += k * -2.2;
            rz += k * 0.7;
            panel.group.rotation.set(rx, ry, rz);
            panel.group.scale.setScalar(s * (0.6 + 0.4 * e));
        });

        // The camera drifts with the pointer, so the line is seen from a
        // little to one side or above as it moves.
        camera.position.x = state.mx * 0.55;
        camera.position.y = (wide ? 1.15 : 1.3) + state.my * -0.3;
        camera.lookAt(look);

        renderer.render(scene, camera);

        show(clamp(Math.round(f), 0, items.length - 1));

        // Idle motion keeps it alive while it is on screen; off screen it
        // stops drawing altogether.
        if (state.visible) frame = requestAnimationFrame(render);
    };

    function wake() {
        if (!frame) frame = requestAnimationFrame(render);
    }

    /* ---------------- scroll ---------------- */

    const steps = Math.max(1, items.length - 1);

    const trigger = ScrollTrigger.create({
        trigger: section,
        start: 'top top',
        // Shorter per project when there are many, so a long list stays a
        // reasonable scroll: about a quarter-screen each past eight.
        end: () => `+=${window.innerHeight * steps * (steps > 8 ? 0.26 : (wide ? 0.38 : 0.32))}`,
        pin: true,
        scrub: true,
        snap: {
            snapTo: 1 / steps,
            duration: { min: 0.25, max: 0.7 },
            delay: 0.08,
            ease: 'power2.inOut',
        },
        invalidateOnRefresh: true,
        onUpdate: (self) => {
            state.target = self.progress * steps;
            wake();
        },
    });

    // Visible a little before the pin and a little after it, so the panels
    // are already moving as the section slides into view.
    const isOnScreen = () => {
        const r = section.getBoundingClientRect();
        return r.bottom > 0 && r.top < window.innerHeight;
    };

    ScrollTrigger.create({
        trigger: section,
        start: 'top bottom',
        // To the end of the pin and a screen past it, not the section's own
        // foot: measured unpinned, the section is one screen tall, and the
        // stage was taken for off-screen most of the way through its pin —
        // it then crept a single eased step per scroll and stalled short of
        // the last project.
        end: () => `+=${trigger.end - trigger.start + window.innerHeight * 2}`,
        onToggle: (self) => {
            state.visible = self.isActive;
            if (state.visible) wake();
        },
        onEnter: () => {
            if (state.intro === 0) gsap.to(state, { intro: 1, duration: 2.2, ease: 'power2.out', onUpdate: wake });
        },
        onEnterBack: () => {
            if (state.intro === 0) state.intro = 1;
        },
    });

    /* ---------------- pointer ---------------- */

    const raycaster = new THREE.Raycaster();
    const pointer = new THREE.Vector2();
    const meshes = panels.map((p) => p.mesh);

    stage.addEventListener('pointermove', (event) => {
        const rect = canvas.getBoundingClientRect();
        pointer.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
        pointer.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;
        state.tmx = pointer.x;
        state.tmy = pointer.y;

        raycaster.setFromCamera(pointer, camera);
        const hit = raycaster.intersectObjects(meshes)[0];
        state.hover = Boolean(hit);
        canvas.style.cursor = hit ? 'pointer' : '';
        wake();
    });

    stage.addEventListener('pointerleave', () => {
        state.tmx = 0;
        state.tmy = 0;
        canvas.style.cursor = '';
    });

    // A click on a panel: the front one opens its project; any other is
    // brought to the front first.
    canvas.addEventListener('click', (event) => {
        const rect = canvas.getBoundingClientRect();
        pointer.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
        pointer.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;
        raycaster.setFromCamera(pointer, camera);
        const hit = raycaster.intersectObjects(meshes)[0];
        if (!hit) return;

        const i = hit.object.userData.index;
        if (i === state.shown) {
            link.click();
        } else {
            goTo(i);
        }
    });

    const goTo = (i) => {
        const y = trigger.start + (trigger.end - trigger.start) * (i / steps);
        window.scrollTo({ top: y, behavior: 'smooth' });
    };

    /* ---------------- boot ---------------- */

    resize();
    new ResizeObserver(resize).observe(stage);

    /*
     * This module loads after the page's other scenes, so its pin is the last
     * one made while it sits between the hero's and the services'. Pins have
     * to be measured top to bottom — each one's start depends on the space
     * every pin above it adds — so the list is put back into page order and
     * measured again. A priority flag was tried first and measured this pin
     * before the hero's, which started it a screen and a half early, in the
     * middle of the About section.
     */
    ScrollTrigger.sort();
    ScrollTrigger.refresh();

    show(0);
    state.visible = isOnScreen();
    if (state.visible && state.intro === 0) {
        gsap.to(state, { intro: 1, duration: 2.2, ease: 'power2.out', delay: 0.3, onUpdate: wake });
    }
    wake();
}
