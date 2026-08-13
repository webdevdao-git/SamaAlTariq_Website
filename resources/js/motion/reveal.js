import { subscribeToScroll, prefersReducedMotion } from './scroll-engine';

/**
 * Fades blocks up as they enter the viewport, and fades media in behind it.
 *
 * Two classes share this loop because they share the trigger and differ only in
 * what they animate: `.reveal` moves a block of content, `.reveal-media` settles
 * a picture inside a frame that never moves. Running them off one pass keeps a
 * section's copy and its photograph on the same clock.
 *
 * Deliberately not IntersectionObserver: IO samples threshold crossings, so a
 * jump-scroll — an anchor click, a restored scroll position, a fast fling — can
 * skip a block entirely and leave it stuck at opacity 0. Testing "is its top
 * above the viewport bottom" reads the resting position instead, and cannot
 * miss.
 */
export function initReveal() {
    const nodes = Array.from(document.querySelectorAll('.reveal, .reveal-media'));
    if (nodes.length === 0) return;

    if (prefersReducedMotion()) {
        nodes.forEach((node) => node.classList.add('is-visible'));
        return;
    }

    const pending = new Set(nodes);

    const unsubscribe = subscribeToScroll(({ vh }) => {
        const limit = vh * 0.94;

        for (const node of pending) {
            if (node.getBoundingClientRect().top >= limit) continue;
            node.classList.add('is-visible');
            pending.delete(node);

            /*
             * Drop the compositor hint once the transition it was for has run.
             * A page of images left on will-change holds a layer each for the
             * rest of the visit, which costs memory for no further benefit.
             */
            if (node.classList.contains('reveal-media')) {
                node.addEventListener(
                    'transitionend',
                    () => node.classList.add('is-settled'),
                    { once: true },
                );
            }
        }

        if (pending.size === 0) unsubscribe();
    });
}
