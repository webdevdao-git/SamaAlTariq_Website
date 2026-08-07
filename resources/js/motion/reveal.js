import { subscribeToScroll, prefersReducedMotion } from './scroll-engine';

/**
 * Fades blocks up as they enter the viewport.
 *
 * Deliberately not IntersectionObserver: IO samples threshold crossings, so a
 * jump-scroll — an anchor click, a restored scroll position, a fast fling — can
 * skip a block entirely and leave it stuck at opacity 0. Testing "is its top
 * above the viewport bottom" reads the resting position instead, and cannot
 * miss.
 */
export function initReveal() {
    const nodes = Array.from(document.querySelectorAll('.reveal'));
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
        }

        if (pending.size === 0) unsubscribe();
    });
}
