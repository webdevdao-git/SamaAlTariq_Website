import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

/*
 * Home, the gypsum chapter: the finished ceiling wipes across the framed one
 * as the card crosses the screen, a white seam leading it. Without the
 * script the finished photograph simply covers the framed one.
 */
export function initGypsumWipe() {
    const card = document.querySelector('[data-gyp]');
    const top = card?.querySelector('[data-gyp-top]');
    const edge = card?.querySelector('[data-gyp-edge]');
    if (!card || !top || !edge) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    gsap.registerPlugin(ScrollTrigger);

    const wipe = { p: 0 };
    const draw = () => {
        top.style.clipPath = `inset(0 ${100 - wipe.p}% 0 0)`;
        edge.style.left = `${wipe.p}%`;
        edge.style.opacity = wipe.p > 0.5 && wipe.p < 99.5 ? '1' : '0';
    };
    draw();

    gsap.to(wipe, {
        p: 100,
        ease: 'none',
        onUpdate: draw,
        scrollTrigger: { trigger: card, start: 'top 70%', end: 'bottom 55%', scrub: 0.6 },
    });
}
