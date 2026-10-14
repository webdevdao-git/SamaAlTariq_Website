import Lenis from 'lenis';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { pumpScroll, prefersReducedMotion } from './scroll-engine';

gsap.registerPlugin(ScrollTrigger);

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

    /*
     * One clock for Lenis and GSAP. ScrollTrigger reads the position Lenis
     * has eased to rather than the native one, so a pinned scene and the
     * scroll it is pinned to move on the same frame instead of one trailing
     * the other.
     */
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((time) => {
        lenis.raf(time * 1000);
        pumpScroll();
    });
    gsap.ticker.lagSmoothing(0);

    // Route in-page anchors through Lenis so they ease to the target instead of
    // teleporting — and so they do not fight the CSS smooth-scroll we disabled.
    document.addEventListener('click', (event) => {
        const anchor = event.target?.closest?.('a[href^="#"]');
        if (!anchor) return;

        // The services tabs are anchors that switch a panel in place rather
        // than travel to one. Easing the page to a box it is already looking at
        // would be motion for nothing, and this handler's preventDefault is
        // also what used to stop :target from ever firing for them.
        if (anchor.closest('.service-slides')) return;

        const id = anchor.getAttribute('href');
        if (!id || id === '#') return;

        const target = document.querySelector(id);
        if (!target) return;

        event.preventDefault();
        lenis.scrollTo(target);
    });
}
