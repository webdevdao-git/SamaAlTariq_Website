import { subscribeToScroll, prefersReducedMotion } from './scroll-engine';

/**
 * A picture that opens out to fill the screen as the page scrolls through it.
 *
 * Traced off the reference's own closing section rather than guessed at: there,
 * a 720x360 window grows to the full 1440x900 while the section holds against
 * the top of the screen, and the photograph inside runs about a tenth larger
 * than its frame at the start and settles to 1:1 as the frame catches up. That
 * last part is what stops it reading as a box being stretched — the picture is
 * already moving before the frame reaches it.
 *
 * The frame's resting state is the design's: 958x522 centred in the band. That
 * is what a visitor sees before scrolling and what they are left with under
 * reduced motion, so nothing here invents a layout the file does not draw.
 *
 * Scale rather than width/height: writing width would lay the page out again on
 * every frame, and this loop is shared with the reveals and the parallax. The
 * frame is drawn at its final size and scaled *down* to its resting size, so
 * the picture is never resampled upward.
 */
export function initGrowScene() {
    const scenes = Array.from(document.querySelectorAll('[data-grow-scene]'));
    if (scenes.length === 0) return;

    const still = prefersReducedMotion();

    for (const scene of scenes) {
        const frame = scene.querySelector('[data-grow-frame]');
        const media = scene.querySelector('[data-grow-media]');
        const word = scene.querySelector('[data-grow-word]');
        const copy = scene.querySelector('[data-grow-copy]');
        if (!frame) continue;

        /*
         * The design's resting picture, as a fraction of the width it sits in
         * and its own proportion — 958 of 1728, at 958x522. The height is
         * worked out from those two rather than fixed, because a fraction of
         * the frame's 980 is only right on a screen that happens to be 980
         * tall: everywhere else the picture would rest at the wrong shape.
         */
        const restX = Number(scene.dataset.growFromX ?? 958 / 1728);
        const ratio = Number(scene.dataset.growRatio ?? 958 / 522);

        const apply = (t) => {
            const restY = Math.min(1, (window.innerWidth * restX) / ratio / window.innerHeight);
            // Eased so the opening is quick and the last stretch settles.
            const e = t < 0.5 ? 2 * t * t : 1 - ((-2 * t + 2) ** 2) / 2;
            const x = restX + (1 - restX) * e;
            const y = restY + (1 - restY) * e;

            frame.style.transform = `scale(${x.toFixed(4)}, ${y.toFixed(4)})`;
            if (media) media.style.transform = `scale(${(1 + 0.1 * (1 - e)).toFixed(4)})`;
            // The word belongs to the resting state; it goes as the picture opens.
            if (word) word.style.opacity = String(Math.max(0, 1 - e * 2.2).toFixed(3));

            /*
             * And the invitation belongs to the opened one, so it arrives over
             * the last third rather than crossfading with the word — at no
             * point are both on the picture.
             */
            if (copy) {
                const shown = Math.max(0, Math.min(1, (e - 0.62) / 0.3));
                copy.style.opacity = shown.toFixed(3);
                copy.style.transform = `translateY(${((1 - shown) * 24).toFixed(1)}px)`;
            }
        };

        if (still) {
            apply(0);
            continue;
        }

        subscribeToScroll(({ y, vh }) => {
            const rect = scene.getBoundingClientRect();
            // 0 when the scene's top reaches the top of the screen, 1 when it
            // has been scrolled by its own height less one screen.
            const travel = Math.max(1, rect.height - vh);
            const progress = Math.min(1, Math.max(0, -rect.top / travel));
            apply(progress);
        });

        apply(0);
    }
}
