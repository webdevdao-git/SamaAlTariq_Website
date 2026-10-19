import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

/*
 * The landing page story: one photograph held behind the cards. Each window
 * owns a chapter that runs to the next window; while a chapter is on screen
 * its photograph is the one shown, crossfading in, and it settles from a
 * slight zoom to true size across the chapter.
 */
export function initStoryBackdrop() {
    const roots = document.querySelectorAll('[data-story]');
    if (roots.length === 0) return;

    gsap.registerPlugin(ScrollTrigger);
    // A page can hold more than one (the landing page breaks for its pinned
    // projects stack); each runs on its own.
    roots.forEach(storyBackdrop);
}

function storyBackdrop(root) {
    const layers = new Map(
        Array.from(root.querySelectorAll('[data-story-bg]')).map((el) => [el.dataset.storyBg, el]),
    );
    const wins = Array.from(root.querySelectorAll('[data-story-win]'));
    const flow = root.querySelector('.story__flow');
    const still = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    let current = wins[0]?.dataset.storyWin;
    const show = (key) => {
        if (key === current || !layers.has(key)) return;
        current = key;
        layers.forEach((el, k) => el.classList.toggle('is-on', k === key));
    };

    wins.forEach((win, i) => {
        const key = win.dataset.storyWin;
        const next = wins[i + 1];

        // The chapter: from this window arriving to the next one arriving.
        ScrollTrigger.create({
            trigger: win,
            start: 'top 65%',
            endTrigger: next ?? flow,
            end: next ? 'top 65%' : 'bottom top',
            onToggle: (self) => { if (self.isActive) show(key); },
        });

        if (still) return;
        const img = layers.get(key)?.querySelector('img');
        if (!img) return;
        gsap.fromTo(img, { scale: 1.22 }, {
            scale: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: win,
                start: 'top bottom',
                endTrigger: next ?? flow,
                end: next ? 'top 65%' : 'bottom bottom',
                scrub: true,
            },
        });
    });
}
