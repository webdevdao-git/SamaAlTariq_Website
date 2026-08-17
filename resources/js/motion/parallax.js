import { subscribeToScroll, viewportProgress, prefersReducedMotion } from './scroll-engine';

/**
 * Vertical drift tied to how far an element has travelled through the viewport.
 *
 * The sweep is split either side of centre, so an element sits at its designed
 * position when centred and is only ever offset by half the distance. That keeps
 * a card from looking misplaced on a short screen or in a screenshot.
 */
export function initParallax() {
    const nodes = Array.from(document.querySelectorAll('[data-parallax]'));
    if (nodes.length === 0 || prefersReducedMotion()) return;

    subscribeToScroll(({ vh }) => {
        for (const node of nodes) {
            const rect = node.getBoundingClientRect();
            // Skip anything off screen — no layout thrash for the whole page.
            if (rect.bottom < -vh || rect.top > vh * 2) continue;

            const distance = Number(node.dataset.parallax) || 60;
            const offset = (viewportProgress(rect, vh) - 0.5) * -distance;
            node.style.transform = `translate3d(0, ${offset.toFixed(2)}px, 0)`;
        }
    });
}

/**
 * Scroll-linked drift for a picture inside a frame it must not leave.
 *
 * `data-drift` goes on an <img> in a clipping frame. The picture is enlarged
 * just enough to give itself headroom, then slid within it as the frame crosses
 * the viewport — the frame keeps its exact box, ratio and position throughout,
 * so the layout never learns this is happening.
 *
 * Travel is a fraction of the element's own height rather than a pixel figure,
 * and the enlargement is computed from that fraction, so the two can never fall
 * out of step: a picture always has exactly the headroom its travel needs and
 * no more. Hand it a fixed pixel range instead and a short image would show a
 * bare edge at the ends of its travel while a tall one would be over-enlarged.
 *
 * Applied sparingly and deliberately. The full-bleed backdrop behind the values
 * band takes it because a backdrop has no designed crop to read the enlargement
 * against, and the three pictures of the approach collage take it at three
 * different rates because that difference between them is the depth — matched
 * rates would read as one flat picture sliding. Everything else on the page
 * keeps its crop exactly and reveals instead.
 */
const DRIFT_RANGE = 0.014; // of the element's height, each way
const DRIFT_MARGIN = 0.002; // a hair of slack so no edge can show at the ends

/** The enlargement a given travel needs, and not a scrap more. */
const driftScale = (range) => 1 + 2 * range + DRIFT_MARGIN;

export function initMediaDrift() {
    const nodes = Array.from(document.querySelectorAll('[data-drift]'));
    if (nodes.length === 0 || prefersReducedMotion()) return;

    // Phones sit this out. The range is a fraction of a much smaller picture,
    // so there is little to see, and it is the last thing a low-power device
    // needs to be compositing while the user flicks down the page.
    if (window.matchMedia('(width < 48rem)').matches) return;

    for (const node of nodes) node.style.willChange = 'transform';

    subscribeToScroll(({ vh }) => {
        for (const node of nodes) {
            const rect = node.getBoundingClientRect();
            if (rect.bottom < -vh || rect.top > vh * 2) continue;

            /*
             * Prefer headroom the markup already provides. Where a picture is
             * laid out taller than the frame that clips it — the reference's
             * own device, a 140%-tall image hung at top:-20% — the slack is
             * simply there to be used, and the transform is a plain translate.
             * Measured rather than assumed, so changing the CSS changes the
             * travel and the two can never disagree.
             */
            const frame = node.parentElement;
            const headroom = (node.offsetHeight - frame.clientHeight) / 2;

            if (headroom > 1) {
                /*
                 * Keyed to the frame's pass across the screen, not the layer's.
                 * The layer is taller than the frame by exactly the slack, so
                 * measuring the sweep against it lengthens the sweep by that
                 * same slack and the picture spends only part of what it has —
                 * 0.105 per px here where the frame's own pass gives 0.134,
                 * which is the reference's 0.192 at the reference's taller
                 * frame. The frame is the thing crossing the viewport; the
                 * layer is only how the crossing is drawn.
                 */
                const progress = viewportProgress(frame.getBoundingClientRect(), vh) - 0.5;

                /*
                 * Down the frame as the page goes down, which is the sign the
                 * reference uses and the opposite of what this had.
                 *
                 * Measured on halston's services listing at 1440x900: the
                 * picture's top runs -406 to -49 inside its frame while the
                 * frame crosses the screen — it starts flush with the frame's
                 * foot and ends flush with its head, moving 0.192px per px of
                 * scroll. Screen-wise that is 0.81 of the page's own speed, so
                 * the picture lags and reads as depth. Negated, as it was here,
                 * it ran at 1.14 and drove against the scroll instead.
                 *
                 * 0.9 keeps the travel just inside the slack, so a rounding
                 * error at the ends of the sweep cannot expose a bare edge.
                 */
                const offset = progress * 2 * (headroom * 0.9);
                node.style.transform = `translate3d(0, ${offset.toFixed(2)}px, 0)`;
                continue;
            }

            /*
             * Otherwise the picture fills its frame exactly and the headroom
             * has to be bought by enlarging it. `data-drift="0.01"` sets the
             * travel as a fraction of the element's own height and the
             * enlargement is derived from it, so a picture is never scaled by
             * more than its travel needs.
             *
             * offsetHeight, not rect.height: the latter already has the scale
             * in it, which would feed the range back into itself.
             */
            const fraction = Number(node.dataset.drift) || DRIFT_RANGE;
            const offset = (viewportProgress(rect, vh) - 0.5) * 2 * (node.offsetHeight * fraction);
            node.style.transform =
                `translate3d(0, ${offset.toFixed(2)}px, 0) scale(${driftScale(fraction).toFixed(4)})`;
        }
    });
}

/**
 * The hero's two scroll behaviours, measured off the reference design:
 *
 *   section  translateY = scrollY × 0.25      the hero sinks at a quarter of
 *                                             scroll speed, so the section
 *                                             below slides up over it
 *   media    scale = 1 + (scrollY / vh) × 0.1 a slow push-in as it leaves
 */
export function initHeroParallax() {
    const section = document.querySelector('[data-hero]');
    const media = document.querySelector('[data-hero-media]');
    if (!section || !media || prefersReducedMotion()) return;

    subscribeToScroll(({ y, vh }) => {
        // Stop recalculating once the hero is well past — the page is ~9,500px.
        if (y > section.offsetHeight + vh) return;

        const scrolled = Math.max(0, y);
        section.style.transform = `translate3d(0, ${(scrolled * 0.25).toFixed(2)}px, 0)`;
        media.style.transform = `scale(${(1 + Math.min(scrolled / vh, 1.4) * 0.1).toFixed(4)})`;
    });
}
