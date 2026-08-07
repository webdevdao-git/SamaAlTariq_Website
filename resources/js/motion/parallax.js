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
