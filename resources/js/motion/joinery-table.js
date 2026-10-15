import * as THREE from 'three';
import { RoundedBoxGeometry } from 'three/examples/jsm/geometries/RoundedBoxGeometry.js';
import { RoomEnvironment } from 'three/examples/jsm/environments/RoomEnvironment.js';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * A walnut dining table, assembled in front of the visitor.
 *
 * Modelled from boxes: a thick top with a softened edge, four tapered legs
 * splayed a few degrees, a stretcher between each pair, and a recessed apron
 * under the top. The timber is a canvas-drawn walnut grain, so nothing is
 * downloaded but the module.
 *
 * As the section comes up the parts arrive in the order a joiner would fit
 * them — legs up from the floor, stretchers in from the sides, the apron
 * down onto them, the top last — while the table turns a quarter. Assembled,
 * it follows the pointer.
 */

function walnut() {
    const w = 1024;
    const h = 256;
    const c = document.createElement('canvas');
    c.width = w;
    c.height = h;
    const g = c.getContext('2d');

    const base = g.createLinearGradient(0, 0, 0, h);
    base.addColorStop(0, '#6b4429');
    base.addColorStop(0.5, '#5a3820');
    base.addColorStop(1, '#6e472b');
    g.fillStyle = base;
    g.fillRect(0, 0, w, h);

    // Long grain lines that wander, darker and lighter.
    for (let i = 0; i < 160; i++) {
        const y0 = Math.random() * h;
        const amp = 2 + Math.random() * 7;
        const freq = 0.004 + Math.random() * 0.01;
        const phase = Math.random() * 10;
        g.beginPath();
        for (let x = 0; x <= w; x += 8) {
            const y = y0 + Math.sin(x * freq + phase) * amp + Math.sin(x * freq * 3.1) * amp * 0.3;
            if (x === 0) g.moveTo(x, y);
            else g.lineTo(x, y);
        }
        const dark = Math.random() > 0.45;
        g.strokeStyle = dark ? `rgba(35,20,10,${0.08 + Math.random() * 0.18})` : `rgba(160,110,70,${0.05 + Math.random() * 0.12})`;
        g.lineWidth = 0.6 + Math.random() * 1.8;
        g.stroke();
    }

    const tex = new THREE.CanvasTexture(c);
    tex.colorSpace = THREE.SRGBColorSpace;
    tex.wrapS = THREE.RepeatWrapping;
    tex.wrapT = THREE.RepeatWrapping;
    tex.anisotropy = 8;
    return tex;
}

export function initJoineryTable() {
    const host = document.querySelector('[data-jtable]');
    const canvas = host?.querySelector('canvas');
    if (!host || !canvas) return;

    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.75));
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.1;
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;

    const scene = new THREE.Scene();
    const pmrem = new THREE.PMREMGenerator(renderer);
    scene.environment = pmrem.fromScene(new RoomEnvironment(), 0.04).texture;
    pmrem.dispose();

    const camera = new THREE.PerspectiveCamera(30, 1, 0.1, 50);
    // Close in, so the table fills its frame rather than standing small in it.
    camera.position.set(0, 1.85, 5.1);
    const look = new THREE.Vector3(-0.45, 0.5, 0);

    scene.add(new THREE.AmbientLight(0xffffff, 0.4));
    const sun = new THREE.DirectionalLight(0xfff0dc, 2.2);
    sun.position.set(3, 6, 4);
    sun.castShadow = true;
    sun.shadow.mapSize.set(1024, 1024);
    sun.shadow.camera.left = -4;
    sun.shadow.camera.right = 4;
    sun.shadow.camera.top = 4;
    sun.shadow.camera.bottom = -4;
    sun.shadow.radius = 8;
    scene.add(sun);
    const rim = new THREE.DirectionalLight(0x3fa7b3, 0.9);
    rim.position.set(-4, 2, -3);
    scene.add(rim);

    const floor = new THREE.Mesh(new THREE.PlaneGeometry(20, 20), new THREE.ShadowMaterial({ opacity: 0.22 }));
    floor.rotation.x = -Math.PI / 2;
    floor.receiveShadow = true;
    scene.add(floor);

    const grain = walnut();
    const wood = (rx = 1, ry = 1, rot = 0) => {
        const map = grain.clone();
        map.needsUpdate = true;
        map.repeat.set(rx, ry);
        map.rotation = rot;
        return new THREE.MeshStandardMaterial({ map, roughness: 0.48, metalness: 0.02 });
    };

    const table = new THREE.Group();
    scene.add(table);

    const L = 2.6;
    const W = 1.15;
    const H = 1.0;
    const T = 0.09;

    const mesh = (geo, mat) => {
        const m = new THREE.Mesh(geo, mat);
        m.castShadow = true;
        m.receiveShadow = true;
        return m;
    };

    // The parts, each with where it ends and where it starts.
    const parts = [];
    const add = (obj, from, order) => {
        // Its own material, so each part can fade in on its own clock.
        obj.material = obj.material.clone();
        table.add(obj);
        parts.push({ obj, to: obj.position.clone(), toRot: obj.rotation.clone(), from, order });
    };

    const top = mesh(new RoundedBoxGeometry(L, T, W, 4, 0.03), wood(1, 1));
    top.position.set(0, H - T / 2, 0);
    add(top, { y: 2.6, ry: 0.4 }, 3);

    const apronMat = wood(0.6, 0.2);
    const apronF = mesh(new THREE.BoxGeometry(L - 0.5, 0.12, 0.04), apronMat);
    apronF.position.set(0, H - T - 0.06, W / 2 - 0.2);
    add(apronF, { y: 1.6, z: 1.2 }, 2);
    const apronB = apronF.clone();
    apronB.position.set(0, H - T - 0.06, -(W / 2 - 0.2));
    add(apronB, { y: 1.6, z: -1.2 }, 2);

    const legGeo = new THREE.CylinderGeometry(0.035, 0.055, H - T, 18);
    const legMat = wood(0.1, 1, Math.PI / 2);
    const splay = 0.07;
    [[-1, -1], [1, -1], [-1, 1], [1, 1]].forEach(([sx, sz], i) => {
        const leg = mesh(legGeo, legMat);
        leg.position.set(sx * (L / 2 - 0.3), (H - T) / 2, sz * (W / 2 - 0.2));
        leg.rotation.set(sz * splay, 0, -sx * splay);
        add(leg, { y: -1.8, x: sx * 0.6 }, 0 + i * 0.08);
    });

    const stretchGeo = new THREE.BoxGeometry(0.035, 0.035, W - 0.4);
    [-1, 1].forEach((sx) => {
        const st = mesh(stretchGeo, legMat);
        st.position.set(sx * (L / 2 - 0.3), 0.28, 0);
        add(st, { x: sx * 2.2 }, 1);
    });
    const spine = mesh(new THREE.BoxGeometry(L - 0.6, 0.035, 0.035), legMat);
    spine.position.set(0, 0.28, 0);
    add(spine, { y: -1.2 }, 1.2);

    /* ---------- state ---------- */

    const state = { build: 0, spin: 0, lx: 0, ly: 0, tx: 0, ty: 0, visible: false };
    let frame = 0;

    const pose = () => {
        parts.forEach(({ obj, to, toRot, from, order }) => {
            // Each part has its own slice of the build, in fitting order.
            const start = order / 4.2;
            const p = Math.min(1, Math.max(0, (state.build - start) / 0.38));
            const e = 1 - Math.pow(1 - p, 3);
            const k = 1 - e;
            obj.position.set(to.x + (from.x ?? 0) * k, to.y + (from.y ?? 0) * k, to.z + (from.z ?? 0) * k);
            obj.rotation.set(toRot.x, toRot.y + (from.ry ?? 0) * k, toRot.z);
            obj.visible = p > 0.001;
            obj.material.transparent = p < 1;
            obj.material.opacity = Math.min(1, p * 3);
        });
    };

    const render = () => {
        frame = 0;
        state.lx += (state.tx - state.lx) * 0.06;
        state.ly += (state.ty - state.ly) * 0.06;
        table.rotation.y = -0.55 + state.spin + state.lx * 0.5;
        camera.position.y = 1.75 + state.ly * -0.4;
        camera.lookAt(look);
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

    host.classList.add('is-live');
    resize();
    new ResizeObserver(resize).observe(host);

    gsap.to(state, {
        build: 1,
        spin: Math.PI / 2,
        ease: 'none',
        onUpdate: wake,
        scrollTrigger: { trigger: host, start: 'top 90%', end: 'center 45%', scrub: 0.8 },
    });

    ScrollTrigger.create({
        trigger: host,
        start: 'top bottom',
        end: 'bottom top',
        onToggle: (self) => { state.visible = self.isActive; if (self.isActive) wake(); },
    });

    host.addEventListener('pointermove', (event) => {
        const r = host.getBoundingClientRect();
        state.tx = ((event.clientX - r.left) / r.width - 0.5) * 2;
        state.ty = ((event.clientY - r.top) / r.height - 0.5) * 2;
        wake();
    });
    host.addEventListener('pointerleave', () => { state.tx = 0; state.ty = 0; wake(); });

    pose();
    wake();
}
