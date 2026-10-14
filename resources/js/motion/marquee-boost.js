import { subscribeToScroll, prefersReducedMotion } from './scroll-engine';

/**
 * The client strip picks up speed with the page.
 *
 * The strip's own CSS animation keeps running exactly as it is; this only
 * turns its playbackRate up while the page is moving and lets it ease back
 * to 1 when it stops. Scrolling up runs it backwards, so the row reads as
 * attached to the scroll rather than merely excited by it.
 */
export function initMarqueeBoost() {
    const tracks = Array.from(document.querySelectorAll('.marquee__track'));
    if (tracks.length === 0 || prefersReducedMotion()) return;

    let lastY = window.scrollY;
    let rate = 1;
    let target = 1;
    let frame = 0;

    const apply = () => {
        rate += (target - rate) * 0.08;
        target += (1 - target) * 0.06;

        for (const track of tracks) {
            for (const animation of track.getAnimations()) animation.playbackRate = rate;
        }

        frame = Math.abs(rate - 1) > 0.01 || Math.abs(target - 1) > 0.01 ? requestAnimationFrame(apply) : 0;
        if (!frame) {
            rate = 1;
            for (const track of tracks) for (const animation of track.getAnimations()) animation.playbackRate = 1;
        }
    };

    subscribeToScroll(({ y }) => {
        const delta = y - lastY;
        lastY = y;
        if (delta === 0) return;

        // About 1 extra unit of speed per 10px a frame, capped so a fling
        // blurs the logos past rather than turning them into a smear.
        target = Math.max(-5, Math.min(6, 1 + delta / 10));
        if (!frame) frame = requestAnimationFrame(apply);
    });
}
