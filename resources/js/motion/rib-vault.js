import * as THREE from 'three';
import { RoomEnvironment } from 'three/examples/jsm/environments/RoomEnvironment.js';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * From a flat board to the curved, ribbed vault in the About photograph —
 * the hardest kind of piece the workshop makes — on one held screen.
 *
 *   Flat board   a sheet lies on the bed, seen from above.
 *   CNC cut      a laser point traces every rib's profile across it, one
 *                after another, each leaving its cut line.
 *   Assembled    the cut pieces lift off the sheet and stand up, one after
 *                another, into their places in the vault, while the camera
 *                comes down from above to look in under it.
 *   Built        the photograph of the real one rises in the corner.
 *
 * No two ribs are the same: the curve breathes along the run, so together
 * their edges make the wave the real one has.
 */

const RIBS = 30;
const GAP = 0.1;
const BOARD = 0.055;

// Where each phase sits in the scroll, 0..1.
const CUT = [0.08, 0.42];
const LIFT = [0.42, 0.78];
const PHOTO = [0.8, 0.94];

const clamp01 = (v) => Math.min(1, Math.max(0, v));
const span = (v, [a, b]) => clamp01((v - a) / (b - a));
const ease = (t) => (t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2);

function oak() {
    const w = 1024;
    const h = 256;
    const c = document.createElement('canvas');
    c.width = w;
    c.height = h;
    const g = c.getContext('2d');

    const base = g.createLinearGradient(0, 0, 0, h);
    base.addColorStop(0, '#dcc19e');
    base.addColorStop(0.5, '#d1b18c');
    base.addColorStop(1, '#dfc4a2');
    g.fillStyle = base;
    g.fillRect(0, 0, w, h);

    for (let i = 0; i < 140; i++) {
        const y0 = Math.random() * h;
        const amp = 1.5 + Math.random() * 5;
        const freq = 0.004 + Math.random() * 0.01;
        const phase = Math.random() * 10;
        g.beginPath();
        for (let x = 0; x <= w; x += 8) {
            const y = y0 + Math.sin(x * freq + phase) * amp;
            if (x === 0) g.moveTo(x, y);
            else g.lineTo(x, y);
        }
        const dark = Math.random() > 0.5;
        g.strokeStyle = dark ? `rgba(120,80,45,${0.05 + Math.random() * 0.12})` : `rgba(250,232,205,${0.05 + Math.random() * 0.12})`;
        g.lineWidth = 0.5 + Math.random() * 1.5;
        g.stroke();
    }

    const tex = new THREE.CanvasTexture(c);
    tex.colorSpace = THREE.SRGBColorSpace;
    tex.wrapS = THREE.RepeatWrapping;
    tex.wrapT = THREE.RepeatWrapping;
    tex.repeat.set(0.6, 0.6);
    tex.anisotropy = 8;
    return tex;
}

/*
 * One rib's profile: up the wall from the floor, over the shoulder and
 * along the ceiling to the opening. `wave` (-1..1) moves the shoulder and
 * the end, which is what ripples the edges along the vault. Returns the
 * solid shape and the outer edge, which is the line the CNC cuts.
 */
function rib(wave) {
    const curve = new THREE.CubicBezierCurve(
        new THREE.Vector2(-1.35, 0),
        new THREE.Vector2(-1.35 + wave * 0.12, 2.05 + wave * 0.1),
        new THREE.Vector2(-0.95 + wave * 0.1, 2.62 + wave * 0.08),
        new THREE.Vector2(0.95 + wave * 0.45, 2.5 - wave * 0.06),
    );

    const steps = 48;
    const outer = curve.getSpacedPoints(steps);
    const inner = outer.map((p, i) => {
        const t = curve.getTangentAt(i / steps);
        const n = new THREE.Vector2(t.y, -t.x);
        const depth = 0.09 + 0.14 * Math.sin(Math.PI * Math.min(1, (i / steps) * 1.15));
        return p.clone().add(n.multiplyScalar(depth));
    });

    const shape = new THREE.Shape();
    shape.moveTo(outer[0].x, outer[0].y);
    outer.slice(1).forEach((p) => shape.lineTo(p.x, p.y));
    [...inner].reverse().forEach((p) => shape.lineTo(p.x, p.y));
    shape.closePath();

    // The full cut path: round the outside and back along the inside.
    return { shape, path: [...outer, ...[...inner].reverse(), outer[0]] };
}

export function initRibVault() {
    const host = document.querySelector('[data-rvault]');
    const section = host?.closest('section');
    const canvas = host?.querySelector('canvas');
    const photo = host?.querySelector('[data-rv-photo]');
    const phases = Array.from(host?.querySelectorAll('[data-rv-phases] li') ?? []);
    if (!host || !section || !canvas) return;

    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.75));
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.05;
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;

    const scene = new THREE.Scene();
    const pmrem = new THREE.PMREMGenerator(renderer);
    scene.environment = pmrem.fromScene(new RoomEnvironment(), 0.04).texture;
    pmrem.dispose();

    const camera = new THREE.PerspectiveCamera(36, 1, 0.1, 60);

    scene.add(new THREE.AmbientLight(0xffffff, 0.35));
    const sun = new THREE.DirectionalLight(0xfff1dd, 2.5);
    sun.position.set(4, 7, 3);
    sun.castShadow = true;
    sun.shadow.mapSize.set(2048, 2048);
    Object.assign(sun.shadow.camera, { left: -6, right: 6, top: 6, bottom: -6 });
    sun.shadow.bias = -0.0004;
    sun.shadow.radius = 4;
    scene.add(sun);
    const rim = new THREE.DirectionalLight(0x3fa7b3, 0.5);
    rim.position.set(-5, 2, -4);
    scene.add(rim);

    const floor = new THREE.Mesh(new THREE.PlaneGeometry(30, 30), new THREE.ShadowMaterial({ opacity: 0.18 }));
    floor.rotation.x = -Math.PI / 2;
    floor.receiveShadow = true;
    scene.add(floor);

    const grain = oak();

    // The sheet on the bed.
    const sheetMat = new THREE.MeshStandardMaterial({ map: grain, roughness: 0.7, transparent: true });
    const sheet = new THREE.Mesh(new THREE.BoxGeometry(4.6, 0.05, 3.9), sheetMat);
    sheet.position.y = 0.025;
    sheet.receiveShadow = true;
    scene.add(sheet);

    const laser = new THREE.Mesh(
        new THREE.SphereGeometry(0.035, 16, 16),
        new THREE.MeshBasicMaterial({ color: 0xffffff }),
    );
    const glow = new THREE.Mesh(
        new THREE.SphereGeometry(0.12, 16, 16),
        new THREE.MeshBasicMaterial({ color: 0x3fa7b3, transparent: true, opacity: 0.45 }),
    );
    laser.add(glow);
    laser.visible = false;
    scene.add(laser);

    const lineMat = new THREE.LineBasicMaterial({ color: 0x2f8e99, transparent: true });

    /*
     * Flat on the sheet a rib is turned down onto the bed: rotating -90°
     * about x lays its (x, y) on the floor at (x, -y). Each sits a little
     * along from the last, nested across the sheet.
     */
    const flat = (k) => new THREE.Vector3(
        (k - (RIBS - 1) / 2) * 0.046,
        0.055,
        1.3 + (k - (RIBS - 1) / 2) * 0.03,
    );

    const pieces = [];
    for (let k = 0; k < RIBS; k++) {
        const u = k / (RIBS - 1);
        const wave = Math.sin(u * Math.PI * 2.2 + 0.5) * 0.9 + Math.sin(u * Math.PI * 5.1) * 0.1;
        const { shape, path } = rib(wave);

        const mesh = new THREE.Mesh(
            new THREE.ExtrudeGeometry(shape, {
                depth: BOARD, bevelEnabled: true, bevelThickness: 0.006, bevelSize: 0.006, bevelSegments: 1, curveSegments: 1,
            }),
            new THREE.MeshStandardMaterial({ map: grain, roughness: 0.62, transparent: true }),
        );
        mesh.castShadow = true;
        mesh.receiveShadow = true;
        scene.add(mesh);

        const at = flat(k);
        const points = path.map((p) => new THREE.Vector3(at.x + p.x, at.y + 0.002, at.z - p.y));
        const line = new THREE.Line(new THREE.BufferGeometry().setFromPoints(points), lineMat);
        line.geometry.setDrawRange(0, 0);
        scene.add(line);

        pieces.push({ mesh, line, points, flat: at, slot: (k - (RIBS - 1) / 2) * GAP });
    }

    /* ---------- the camera's two stations ---------- */

    const above = { pos: new THREE.Vector3(0.2, 6.6, 3.6), look: new THREE.Vector3(0, 0, 0.2) };
    const under = { pos: new THREE.Vector3(4.1, 1.75, 4.6), look: new THREE.Vector3(-0.1, 1.25, 0) };
    const camPos = new THREE.Vector3();
    const camLook = new THREE.Vector3();

    /* ---------- state ---------- */

    const state = { build: 0, lx: 0, ly: 0, tx: 0, ty: 0, visible: false };
    let frame = 0;
    let phaseShown = -1;

    const pose = () => {
        const b = state.build;
        const cut = span(b, CUT);
        const lift = span(b, LIFT);

        // CNC: the cuts in turn, the laser riding the current one.
        const per = 1 / RIBS;
        laser.visible = cut > 0 && cut < 1;
        pieces.forEach((piece, k) => {
            const own = clamp01((cut - k * per) / per);
            const count = piece.points.length;
            piece.line.geometry.setDrawRange(0, Math.max(0, Math.floor(own * count)));
            if (own > 0 && own < 1) {
                const i = Math.min(count - 1, Math.floor(own * (count - 1)));
                laser.position.copy(piece.points[i]);
            }
        });
        lineMat.opacity = 1 - span(b, [LIFT[0], LIFT[0] + 0.08]);

        // The pieces: lying in their cuts, then lifting and standing up.
        pieces.forEach((piece, k) => {
            const appear = span(b, [LIFT[0] - 0.03, LIFT[0] + 0.02]);
            const start = (k / RIBS) * 0.7;
            const p = ease(clamp01((lift - start) / 0.3));
            const m = piece.mesh;
            m.visible = appear > 0;
            m.material.opacity = appear;
            m.rotation.set(-Math.PI / 2 * (1 - p), 0, 0);
            m.position.set(
                piece.flat.x * (1 - p),
                piece.flat.y * (1 - p) + Math.sin(p * Math.PI) * 1.1,
                piece.flat.z * (1 - p) + piece.slot * p,
            );
        });

        // The sheet goes once the pieces are off it.
        sheetMat.opacity = 1 - span(b, [LIFT[0] + 0.05, LIFT[0] + 0.22]);
        sheet.visible = sheetMat.opacity > 0.01;

        // Down from above to look in under the vault.
        const c = ease(span(b, [LIFT[0] - 0.04, LIFT[1]]));
        camPos.lerpVectors(above.pos, under.pos, c);
        camLook.lerpVectors(above.look, under.look, c);
        camPos.x += state.lx * 0.5;
        camPos.y -= state.ly * 0.3;
        camera.position.copy(camPos);
        camera.lookAt(camLook);

        // Photo of the real one.
        if (photo) {
            const ph = ease(span(b, PHOTO));
            photo.style.clipPath = `inset(${(1 - ph) * 100}% 0 0 0 round 16px)`;
            photo.firstElementChild.style.scale = String(1.2 - ph * 0.2);
        }

        const phase = b >= PHOTO[0] ? 3 : b >= LIFT[0] ? 2 : b >= CUT[0] ? 1 : 0;
        if (phase !== phaseShown) {
            phaseShown = phase;
            phases.forEach((li, i) => li.classList.toggle('is-on', i === phase));
        }
    };

    const render = () => {
        frame = 0;
        state.lx += (state.tx - state.lx) * 0.06;
        state.ly += (state.ty - state.ly) * 0.06;
        pose();
        renderer.render(scene, camera);
        const moving = Math.abs(state.tx - state.lx) > 0.001 || Math.abs(state.ty - state.ly) > 0.001;
        if (moving && state.visible) frame = requestAnimationFrame(render);
    };
    const wake = () => { if (!frame) frame = requestAnimationFrame(render); };

    const resize = () => {
        const w = host.clientWidth;
        const h = host.clientHeight;
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
        wake();
    };

    section.classList.add('is-live');
    host.classList.add('is-live');
    resize();
    new ResizeObserver(resize).observe(host);

    gsap.to(state, {
        build: 1,
        ease: 'none',
        onUpdate: wake,
        scrollTrigger: {
            trigger: section,
            start: 'top top',
            end: () => `+=${window.innerHeight * 3.2}`,
            pin: true,
            scrub: 0.8,
            invalidateOnRefresh: true,
            onToggle: (self) => { state.visible = self.isActive; if (self.isActive) wake(); },
        },
    });

    ScrollTrigger.create({
        trigger: section,
        start: 'top bottom',
        end: 'bottom top',
        onToggle: (self) => { if (self.isActive) { state.visible = true; wake(); } },
    });

    host.addEventListener('pointermove', (event) => {
        const r = host.getBoundingClientRect();
        state.tx = ((event.clientX - r.left) / r.width - 0.5) * 2;
        state.ty = ((event.clientY - r.top) / r.height - 0.5) * 2;
        wake();
    });
    host.addEventListener('pointerleave', () => { state.tx = 0; state.ty = 0; wake(); });

    // Pins further down the page were measured before this one existed.
    ScrollTrigger.sort();
    ScrollTrigger.refresh();

    pose();
    wake();
}
