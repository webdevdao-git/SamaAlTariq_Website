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
 * The title is ink over the drawing and white over the photograph, and the
 * white copy lives inside a second window on the same transform. Fading it in
 * on a curve instead — the first attempt — turns the whole title white at once
 * while the picture is still climbing, so the upper line goes white against the
 * pale drawing and cannot be read. Masked, each word changes exactly as the
 * picture reaches it.
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
        /*
         * Two windows, not one: the photograph's sits behind the page's
         * content and the white title's in front of it, so the ink title can
         * lie between them. Both take the same scale, so they are the same
         * window — the type is revealed by exactly the edge that reveals the
         * picture.
         */
        const windows = Array.from(scene.querySelectorAll('[data-reveal-window]'));
        const media = Array.from(scene.querySelectorAll('[data-reveal-media]'));
        const ink = scene.querySelector('[data-reveal-title-ink]');
        const closing = scene.querySelector('[data-reveal-title-white]');
        if (windows.length === 0 || media.length === 0) continue;

        const apply = (t) => {
            const x = FROM_X + (1 - FROM_X) * t;
            const y = FROM_Y + (1 - FROM_Y) * t;
            const scale = `scale(${x.toFixed(4)}, ${y.toFixed(4)})`;
            const inverse = `scale(${(1 / x).toFixed(4)}, ${(1 / y).toFixed(4)})`;

            for (const w of windows) w.style.transform = scale;
            for (const m of media) m.style.transform = inverse;

            /*
             * The opening line belongs to the drawing, so it leaves as the
             * picture climbs rather than sitting in ink on a photograph. Gone
             * by halfway, which is before the window reaches its own height.
             */
            if (ink) ink.style.opacity = Math.max(0, 1 - t / 0.5).toFixed(3);

            /*
             * And the closing line waits for the picture to start climbing.
             * The window at rest is a strip across the foot of the frame and
             * that line sits in the foot, so masking alone would show a slice
             * of it before anything has happened — a word cut through the
             * middle. It is off until the opening is under way, and the mask
             * governs it from there.
             */
            if (closing) closing.style.opacity = Math.max(0, Math.min(1, (t - 0.06) / 0.12)).toFixed(3);

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
