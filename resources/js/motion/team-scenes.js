import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { prefersReducedMotion } from './scroll-engine';

gsap.registerPlugin(ScrollTrigger);

/**
 * The Team page and the landing page's team band.
 *
 *   HERO      the team photograph opens from a narrow band to the full frame
 *             as it scrolls in
 *   DEPTS     each department's picture opens from its own side while its
 *             roles step in one under another
 *   TREE      the staffing tree builds top down: a row of roles, the line
 *             drawing down, the next row, the line, the heads
 *   BAND      the six department pictures rise in as a wave
 */
export function initTeamScenes() {
    if (prefersReducedMotion()) return;

    const frame = document.querySelector('[data-team-reveal]');
    if (frame) {
        gsap.fromTo(frame, { clipPath: 'inset(38% 0% 38% 0% round 20px)' }, {
            clipPath: 'inset(0% 0% 0% 0% round 20px)',
            ease: 'power2.inOut',
            scrollTrigger: { trigger: frame, start: 'top 90%', end: 'center 50%', scrub: 0.8 },
        });
        gsap.fromTo(frame.querySelector('img'), { scale: 1.3 }, {
            scale: 1,
            ease: 'none',
            scrollTrigger: { trigger: frame, start: 'top bottom', end: 'bottom top', scrub: true },
        });
    }

    for (const dept of document.querySelectorAll('.team-dept')) {
        const flip = dept.classList.contains('is-flip');
        gsap.fromTo(dept.querySelector('.team-dept__pic'),
            { clipPath: flip ? 'inset(0% 0% 0% 100%)' : 'inset(0% 100% 0% 0%)' },
            { clipPath: 'inset(0% 0% 0% 0%)', duration: 1.4, ease: 'expo.inOut', scrollTrigger: { trigger: dept, start: 'top 80%' } });
        gsap.fromTo(dept.querySelectorAll('.team-dept__num, .team-dept__name, .team-dept__roles li'),
            { y: 30, opacity: 0 },
            { y: 0, opacity: 1, duration: 0.9, ease: 'expo.out', stagger: 0.07, delay: 0.3, scrollTrigger: { trigger: dept, start: 'top 80%' } });
    }

    const tree = document.querySelector('[data-team-tree]');
    if (tree) {
        const tl = gsap.timeline({ scrollTrigger: { trigger: tree, start: 'top 75%' } });
        for (const part of tree.children) {
            if (part.classList.contains('team-tree__line')) {
                tl.fromTo(part, { scaleY: 0 }, { scaleY: 1, duration: 0.5, ease: 'power2.inOut' });
            } else {
                tl.fromTo(part.children, { y: 24, opacity: 0, scale: 0.9 }, { y: 0, opacity: 1, scale: 1, duration: 0.6, ease: 'back.out(1.6)', stagger: 0.06 });
            }
        }
    }

    const band = document.querySelectorAll('.team-band__item');
    if (band.length) {
        gsap.fromTo(band, { y: 80, opacity: 0, rotateX: -20 }, {
            y: 0, opacity: 1, rotateX: 0, duration: 1.1, ease: 'expo.out', stagger: 0.08,
            scrollTrigger: { trigger: band[0].parentElement, start: 'top 85%' },
        });
    }
}
