import { subscribeToScroll, prefersReducedMotion } from './scroll-engine';

/**
 * A photograph revealed over a drawing of itself as the page scrolls.
 *
 * Traced off for-living.it/services, whose hero stacks two pictures and opens a
 * window in the upper one: a clip-path polygon that starts as a strip half the
 * width and a tenth the height, sitting on the foot of the frame, and grows to
 * the whole frame. Measured there: the left edge runs 25% to 0 and the top 90%
 * to 0, both linearly, both landing after about two screens of scrolling.
 *
 * WRITTEN AS TRANSFORMS RATHER THAN AS THE CLIP-PATH IT COPIES. This loop is
 * shared with the reveals and the parallax, and clip-path repaints the element
 * every frame where a transform does not — on a full-bleed photograph that is
 * the difference between compositing and repainting a screen of pixels sixty
 * times a second. The window is scaled from its own foot and the picture inside
 * it is scaled by exactly the inverse, so the picture stands still while the
 * window opens over it. Same movement, no paint.
 */
export function initRevealScene() {
    const scenes = Array.from(document.querySelectorAll('[data-reveal-scene]'));
    if (scenes.length === 0) return;

    // The reference's own starting window: half the width, a tenth the height.
    const FROM_X = 0.5;
    const FROM_Y = 0.1;

    for (const scene of scenes) {
        const window_ = scene.querySelector('[data-reveal-window]');
        const media = scene.querySelector('[data-reveal-media]');
        const veil = scene.querySelector('[data-reveal-veil]');
        if (!window_ || !media) continue;

        const apply = (t) => {
            const x = FROM_X + (1 - FROM_X) * t;
            const y = FROM_Y + (1 - FROM_Y) * t;

            window_.style.transform = `scale(${x.toFixed(4)}, ${y.toFixed(4)})`;
            media.style.transform = `scale(${(1 / x).toFixed(4)}, ${(1 / y).toFixed(4)})`;

            /*
             * The drawing is white and so is the title over it, so the title
             * has nothing to sit against until the photograph arrives. This
             * veil covers the drawing only — it is under the window — and
             * lifts as the picture takes over the frame.
             */
            if (veil) veil.style.opacity = (0.45 * (1 - t)).toFixed(3);
        };

        // Opened, not closed: a visitor who has asked for less movement should
        // be given the photograph, not the drawing under it.
        if (prefersReducedMotion()) {
            apply(1);
            continue;
        }

        subscribeToScroll(({ vh }) => {
            const rect = scene.getBoundingClientRect();
            const travel = Math.max(1, rect.height - vh);
            apply(Math.min(1, Math.max(0, -rect.top / travel)));
        });

        apply(0);
    }
}
