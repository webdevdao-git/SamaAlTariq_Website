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

            // Each picture may set its own travel — `data-drift="0.01"` — so a
            // composition can move at more than one rate. The enlargement is
            // derived from that number rather than fixed, which keeps the
            // slower elements closer to their designed crop.
            const fraction = Number(node.dataset.drift) || DRIFT_RANGE;

            // offsetHeight, not rect.height: the latter already has the scale
            // in it, which would feed the range back into itself.
            const range = node.offsetHeight * fraction;
            const offset = (viewportProgress(rect, vh) - 0.5) * -2 * range;
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
