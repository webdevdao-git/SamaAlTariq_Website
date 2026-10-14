import { subscribeToScroll, prefersReducedMotion } from './scroll-engine';

/**
 * The projects tower: each project a floor slab of one building, drawn at an
 * axonometric and walked up by the scroll.
 *
 * Two movements, both pure functions of where the section is on screen, so a
 * jump-scroll or a restored position lands on the right frame with nothing
 * to catch up:
 *
 *   BUILD   while the section rises into view the slabs drop onto the stack
 *           one after another, bottom floor first, until the tower stands.
 *
 *   CLIMB   once pinned, a cursor runs up the floors. The floor it reaches
 *           slides out of the stack, swings upright to face the viewer and
 *           comes forward; as the cursor moves on it swings back into its
 *           place. The tower sinks as it climbs so the floor being shown is
 *           always drawn from the middle of the screen.
 *
 * The swing is exact rather than approximate: the tower is turned by
 * rotateX · rotateZ, and a floor fully out applies the inverse of those same
 * two turns, so it ends square to the screen whatever the pointer has leaned
 * the tower to. Between the two it interpolates the angles, which is what
 * reads as the slab pivoting out of the building.
 */
const TILT_X = 58;
const TILT_Z = -32;
const DROP = 900;

const clamp01 = (v) => Math.min(1, Math.max(0, v));
const smooth = (t) => t * t * (3 - 2 * t);
const easeOut = (t) => 1 - Math.pow(1 - t, 3);

export function initProjectTower() {
    const section = document.querySelector('[data-tower]');
    if (!section) return;

    const body = section.querySelector('[data-tower-body]');
    const floors = Array.from(section.querySelectorAll('[data-tower-floor]'));
    const title = section.querySelector('[data-tower-title]');
    const category = section.querySelector('[data-tower-category]');
    const index = section.querySelector('[data-tower-index]');
    const count = floors.length;
    if (!body || count < 2 || prefersReducedMotion()) return;

    section.classList.add('is-pinned');

    const wideQuery = window.matchMedia('(width >= 64rem)');

    const lean = { x: 0, y: 0, tx: 0, ty: 0 };
    const scroll = { build: 0, climb: 0 };
    let frame = 0;
    let shown = 0;

    const render = () => {
        frame = 0;
        lean.x += (lean.tx - lean.x) * 0.06;
        lean.y += (lean.ty - lean.y) * 0.06;

        const width = floors[0].offsetWidth;
        const gap = width * 0.085;
        /*
         * Where a floor goes when it is out. On a wide screen the tower stands
         * left of centre (the CSS shifts it) and the floor slides out to the
         * right like a drawer, clear of the stack, so it never passes through
         * the slabs above it. On a narrow one there is no room beside the
         * building, so it comes straight forward instead, far enough to clear
         * the top of the stack.
         */
        const wide = wideQuery.matches;
        const slide = wide ? window.innerWidth * 0.34 : 0;
        const lift = wide ? width * 0.55 : width * 0.95;

        // Starts and ends with every floor home: half a floor of run-in at the
        // bottom, half of run-out past the top.
        const cursor = scroll.climb * (count - 1 + 1.4) - 0.7;
        const follow = Math.min(count - 1, Math.max(0, cursor));

        const tiltX = TILT_X + lean.y * 5;
        // A slow quarter orbit across the whole climb, so the building is seen
        // from a little further round by the time the top floor comes out.
        const tiltZ = TILT_Z + lean.x * 10 + scroll.climb * 24;

        body.style.transform =
            `rotateX(${tiltX.toFixed(3)}deg) rotateZ(${tiltZ.toFixed(3)}deg) translateZ(${(-follow * gap).toFixed(2)}px)`;

        let peak = 0;
        let front = shown;

        floors.forEach((floor, i) => {
            // BUILD: each slab gets its own slice of the arrival, overlapping
            // the next, so they land as a quick sequence rather than together.
            const start = (i / count) * 0.65;
            const placed = easeOut(clamp01((scroll.build - start) / 0.35));

            // CLIMB: 1 when the cursor is on this floor, falling to 0 a floor
            // and a bit either side.
            const out = smooth(clamp01(1 - Math.abs(cursor - i) * 1.25));
            if (out > peak) {
                peak = out;
                front = i;
            }

            const z = i * gap * (1 - out) + follow * gap * out + (1 - placed) * DROP;

            /*
             * The drawer opens before the slab turns: the slide runs ahead on
             * a square root and the swing lags on a square, so the floor is
             * clear of the building by the time it pivots and its corners
             * never cut through the slabs above.
             */
            const across = Math.sqrt(out);
            const turn = out * out;

            floor.style.transform =
                `translateZ(${z.toFixed(2)}px) rotateZ(${(-tiltZ * turn).toFixed(3)}deg) ` +
                `rotateX(${(-tiltX * turn).toFixed(3)}deg) ` +
                `translate3d(${(slide * across).toFixed(2)}px, 0, ${(lift * across).toFixed(2)}px)`;
            floor.style.opacity = placed.toFixed(3);
            floor.style.setProperty('--out', out.toFixed(3));
        });

        // The rest of the building steps back while a floor is out.
        section.style.setProperty('--peak', peak.toFixed(3));

        if (peak > 0.35 && front !== shown) {
            shown = front;
            const floor = floors[front];
            if (title) title.textContent = floor.dataset.title;
            if (category) category.textContent = floor.dataset.category;
            if (index) index.textContent = String(front + 1).padStart(2, '0');
        }

        if (Math.abs(lean.tx - lean.x) > 0.001 || Math.abs(lean.ty - lean.y) > 0.001) {
            frame = requestAnimationFrame(render);
        }
    };

    const wake = () => {
        if (!frame) frame = requestAnimationFrame(render);
    };

    subscribeToScroll(({ vh }) => {
        const rect = section.getBoundingClientRect();
        if (rect.bottom < -vh || rect.top > vh * 1.5) return;

        scroll.build = clamp01((vh * 0.95 - rect.top) / (vh * 0.95));
        scroll.climb = clamp01(-rect.top / Math.max(1, section.offsetHeight - vh));
        wake();
    });

    if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        section.addEventListener('pointermove', (event) => {
            lean.tx = (event.clientX / window.innerWidth - 0.5) * 2;
            lean.ty = (event.clientY / window.innerHeight - 0.5) * 2;
            wake();
        });

        section.addEventListener('pointerleave', () => {
            lean.tx = 0;
            lean.ty = 0;
            wake();
        });
    }
}
