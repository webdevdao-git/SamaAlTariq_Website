import Lenis from 'lenis';
import { pumpScroll, prefersReducedMotion } from './scroll-engine';

/**
 * Lenis smooth scroll.
 *
 * The parallax only reads as weight — the hero lagging, the cards drifting —
 * when scroll position eases instead of jumping in wheel-sized steps. On native
 * scroll the same transforms look like jitter.
 *
 * Skipped entirely under prefers-reduced-motion, falling back to native scroll.
 */
export function initSmoothScroll() {
    if (prefersReducedMotion()) return;

    const lenis = new Lenis({
        duration: 1.05,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smoothWheel: true,
        // Touch devices already have momentum scrolling; overriding it there
        // feels laggy and breaks browser scroll handoff.
        syncTouch: false,
    });

    document.documentElement.classList.add('lenis-active');

    const raf = (time) => {
        lenis.raf(time);
        pumpScroll();
        requestAnimationFrame(raf);
    };
    requestAnimationFrame(raf);

    // Route in-page anchors through Lenis so they ease to the target instead of
    // teleporting — and so they do not fight the CSS smooth-scroll we disabled.
    document.addEventListener('click', (event) => {
        const anchor = event.target?.closest?.('a[href^="#"]');
        if (!anchor) return;

        const id = anchor.getAttribute('href');
        if (!id || id === '#') return;

        const target = document.querySelector(id);
        if (!target) return;

        event.preventDefault();
        lenis.scrollTo(target);
    });
}
