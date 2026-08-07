/**
 * One rAF loop for every scroll-driven effect on the page.
 *
 * The hero sink, the hero zoom, the card parallax, the reveals and the split
 * lines all need the same two numbers each frame — scrollY and viewport height.
 * Each running its own listener would mean five reads of `scrollY` per frame and
 * five chances to force a layout at a different moment. Here the values are read
 * once and handed to every subscriber, and the loop only runs while something is
 * subscribed.
 *
 * Effects are expected to write only `transform` and `opacity`, so animation
 * stays on the compositor.
 */

const subscribers = new Set();
const state = { y: 0, vh: 0 };

let frame = 0;
let listening = false;

function tick() {
    frame = 0;
    state.y = window.scrollY;
    state.vh = window.innerHeight;
    for (const fn of subscribers) fn(state);
}

export function pumpScroll() {
    if (frame) return;
    frame = requestAnimationFrame(tick);
}

function start() {
    if (listening) return;
    listening = true;
    window.addEventListener('scroll', pumpScroll, { passive: true });
    window.addEventListener('resize', pumpScroll, { passive: true });
}

function stop() {
    if (!listening) return;
    listening = false;
    window.removeEventListener('scroll', pumpScroll);
    window.removeEventListener('resize', pumpScroll);
}

export function subscribeToScroll(fn) {
    subscribers.add(fn);
    start();
    pumpScroll();

    return () => {
        subscribers.delete(fn);
        if (subscribers.size === 0) stop();
    };
}

export function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/** 0 → 1 as `rect` travels from just below the viewport to just above it. */
export function viewportProgress(rect, vh) {
    return Math.min(1, Math.max(0, (vh - rect.top) / (vh + rect.height)));
}
