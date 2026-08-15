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
        const light = scene.querySelector('[data-reveal-title-light]');
        if (!window_ || !media) continue;

        const apply = (t) => {
            const x = FROM_X + (1 - FROM_X) * t;
            const y = FROM_Y + (1 - FROM_Y) * t;

            window_.style.transform = `scale(${x.toFixed(4)}, ${y.toFixed(4)})`;
            media.style.transform = `scale(${(1 / x).toFixed(4)}, ${(1 / y).toFixed(4)})`;

            /*
             * The title changes hands rather than sitting behind a veil, which
             * is what the reference does: its own is dark on the drawing and
             * the photograph opens underneath it. Ours sits low in the frame,
             * so the window reaches it almost at once — the white copy is
             * therefore brought up between a twelfth and a third of the way
             * through, which is when the picture passes the type.
             *
             * Two copies cross-fading rather than one changing colour: this
             * loop writes transforms and opacity, and recolouring 108px of
             * display type would repaint it on every frame.
             */
            if (light) light.style.opacity = Math.max(0, Math.min(1, (t - 0.08) / 0.22)).toFixed(3);
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
