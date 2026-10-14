import { prefersReducedMotion } from './scroll-engine';

/**
 * Project pictures that turn in space under the pointer.
 *
 * `data-tilt` goes on a picture's clipping frame. The frame rotates about its
 * own centre toward the pointer — a few degrees, never enough to show an edge
 * of the photograph behind it — and a soft highlight tracks the pointer across
 * the surface, which is what sells the tilt as light falling on a print rather
 * than a flat picture skewing.
 *
 * The frame, not the <img>: the picture inside already carries the scroll
 * drift and the hover push-in, and its transform is theirs. The frame has none
 * of its own, so rotating it composes with both without either knowing.
 *
 * Fine pointers only, like every hover effect here.
 */
const MAX_DEG = 5;

export function initTilt() {
    if (prefersReducedMotion()) return;
    if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

    for (const frame of document.querySelectorAll('[data-tilt]')) {
        const glare = document.createElement('span');
        glare.className = 'tilt-glare';
        glare.setAttribute('aria-hidden', 'true');
        frame.append(glare);

        const state = { rx: 0, ry: 0, tx: 0, ty: 0, frame: 0 };

        const render = () => {
            state.rx += (state.tx - state.rx) * 0.12;
            state.ry += (state.ty - state.ry) * 0.12;
            frame.style.transform =
                `perspective(1100px) rotateX(${state.rx.toFixed(3)}deg) rotateY(${state.ry.toFixed(3)}deg)`;

            const settled = Math.abs(state.tx - state.rx) < 0.01 && Math.abs(state.ty - state.ry) < 0.01;
            if (settled) {
                state.frame = 0;
                if (state.tx === 0 && state.ty === 0) frame.style.transform = '';
                return;
            }
            state.frame = requestAnimationFrame(render);
        };

        const wake = () => {
            if (!state.frame) state.frame = requestAnimationFrame(render);
        };

        // The hover target is the whole card, which is where the link is laid,
        // so the caption below the picture still turns it.
        const zone = frame.closest('figure') ?? frame;

        zone.addEventListener('pointermove', (event) => {
            if (event.pointerType !== 'mouse') return;

            // A tile still rising in owns its transform until it lands.
            if (frame.classList.contains('reveal-rise') && !frame.classList.contains('is-settled')) return;

            const rect = frame.getBoundingClientRect();
            const px = Math.min(1, Math.max(0, (event.clientX - rect.left) / rect.width));
            const py = Math.min(1, Math.max(0, (event.clientY - rect.top) / rect.height));

            state.ty = (px - 0.5) * 2 * MAX_DEG;
            state.tx = (0.5 - py) * 2 * MAX_DEG;

            frame.style.setProperty('--glare-x', `${(px * 100).toFixed(1)}%`);
            frame.style.setProperty('--glare-y', `${(py * 100).toFixed(1)}%`);
            frame.classList.add('is-tilting');
            wake();
        });

        zone.addEventListener('pointerleave', () => {
            state.tx = 0;
            state.ty = 0;
            frame.classList.remove('is-tilting');
            wake();
        });
    }
}
