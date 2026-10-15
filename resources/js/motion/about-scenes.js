import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { prefersReducedMotion } from './scroll-engine';

gsap.registerPlugin(ScrollTrigger);

/**
 * The About page's scenes, one to a section and none the same as another or
 * as the landing page's:
 *
 *   HERO      the photograph opens behind vertical blinds that turn away
 *             from the centre outwards as the curtain lifts
 *   INTRO     the stair photograph grows out of a small inset window to its
 *             full frame as it crosses the screen
 *   VISION    the picture swings round from edge-on, like a door opening
 *   APPROACH  the collage assembles: the villa opens from its centre while
 *             the two side pictures come in from out of the page
 *   VALUES    the section holds and the four values light up one at a time
 *   PURPOSE   the three words of the heading slide in from alternate sides,
 *             and the photo strip below turns like a carousel of prints
 *
 * Built from the page's own markup, found by section order; nothing is added
 * to the copy. gsap.matchMedia undoes all of it under reduced motion, and
 * the pinned values scene runs only where the screen is wide enough to hold
 * the list.
 */
const EASE = 'expo.inOut';

/**
 * Zoom a picture through the individual `scale` property, by way of a custom
 * property, rather than through `transform`.
 *
 * Several of this page's pictures already have their transform spoken for:
 * the intro's carries the reveal's 0.9s transition, so every frame written to
 * it was eased in a second behind and the picture rubber-banded; the values
 * backdrop is moved by the media drift on every scroll, and two writers on
 * one property fought frame by frame. `scale` composes with both and is
 * touched by neither.
 */
function zoom(img, from, to, scrollTrigger, ease = 'none') {
    img.classList.add('fx-zoom');
    return gsap.fromTo(img, { '--zoom': from }, { '--zoom': to, ease, scrollTrigger });
}

function sections() {
    return Array.from(document.querySelectorAll('main > section'));
}

/* ------------------------------------------------------------------ */

function heroBlinds(hero) {
    const pin = hero.querySelector('[data-hero-pin]');
    const img = pin?.querySelector('img');
    if (!pin || !img) return;

    const count = window.innerWidth >= 1024 ? 9 : 5;
    const blinds = document.createElement('div');
    blinds.className = 'about-blinds';
    blinds.setAttribute('aria-hidden', 'true');
    for (let i = 0; i < count; i++) {
        const slat = document.createElement('span');
        // Each slat folds toward the nearer edge, so the opening runs out from
        // the middle of the picture in both directions at once.
        slat.style.transformOrigin = i < count / 2 ? '0% 50%' : '100% 50%';
        blinds.append(slat);
    }
    img.after(blinds);
    const slats = Array.from(blinds.children);

    gsap.set(img, { scale: 1.35 });

    const play = () => {
        gsap.to(slats, {
            scaleX: 0,
            duration: 1.4,
            ease: EASE,
            stagger: { each: 0.07, from: 'center' },
            delay: 0.55,
            onComplete: () => blinds.remove(),
        });
        gsap.to(img, { scale: 1, duration: 2.6, ease: 'expo.out', delay: 0.55 });
    };

    if (document.querySelector('.intro-curtain')) {
        window.addEventListener('curtain:lift', play, { once: true });
    } else {
        play();
    }
}

function introGrow(section) {
    const frame = section.querySelector('.relative.overflow-hidden');
    const img = frame?.querySelector('img');
    if (!frame) return;

    const scrollTrigger = { trigger: frame, start: 'top 95%', end: 'center 55%', scrub: 1 };
    gsap.fromTo(frame,
        { clipPath: 'inset(16% 34% 16% 0% round 28px)' },
        { clipPath: 'inset(0% 0% 0% 0% round 0px)', ease: 'power2.inOut', scrollTrigger });
    if (img) zoom(img, 1.45, 1, scrollTrigger, 'power2.out');
}

function visionDoor(section) {
    const frame = section.querySelector('.relative.overflow-hidden');
    if (!frame) return;

    frame.parentElement.style.perspective = '1600px';

    gsap.fromTo(frame,
        { rotateY: -62, transformOrigin: '100% 50%', opacity: 0.4 },
        {
            rotateY: 0,
            opacity: 1,
            ease: 'power3.out',
            scrollTrigger: { trigger: frame, start: 'top 90%', end: 'top 30%', scrub: 1 },
        });
}

function approachAssemble(section) {
    const frames = Array.from(section.querySelectorAll('.relative.mt-\\[clamp\\(2\\.5rem\\,4\\.63vw\\,80px\\)\\] > div'));
    if (frames.length < 3) return;
    const [main, left, right] = frames;
    const stage = main.parentElement;
    stage.style.perspective = '1800px';

    gsap.timeline({
        scrollTrigger: { trigger: stage, start: 'top 85%', end: 'top 25%', scrub: 1 },
    })
        .fromTo(main,
            { clipPath: 'inset(30% 35% 30% 35%)' },
            { clipPath: 'inset(0% 0% 0% 0%)', ease: 'power2.inOut' }, 0)
        // From outside the frame and turned away, but never nearer the viewer
        // than the page: brought forward in z they grew over the heading.
        .fromTo(left,
            { x: '-45%', rotateY: 28, scale: 0.85, opacity: 0 },
            { x: '0%', rotateY: 0, scale: 1, opacity: 1, ease: 'power3.out' }, 0.15)
        .fromTo(right,
            { x: '45%', rotateY: -28, scale: 0.85, opacity: 0 },
            { x: '0%', rotateY: 0, scale: 1, opacity: 1, ease: 'power3.out' }, 0.25);
}

/**
 * The values rows rise in one after another, each hinged on its top edge so
 * it turns down into place. No pin and no dimming: the rows are the copy, and
 * they stay readable at every point of the scroll.
 */
function valuesRise(section) {
    const rows = Array.from(section.querySelectorAll('li'));
    const list = rows[0]?.parentElement;
    if (!list) return;
    list.style.perspective = '1200px';
    rows.forEach((row, i) => {
        row.classList.add('is-visible');
        gsap.fromTo(row,
            { rotateX: -70, y: 30, opacity: 0, transformOrigin: '50% 0%' },
            { rotateX: 0, y: 0, opacity: 1, duration: 1.1, ease: 'power3.out', delay: i * 0.12,
              scrollTrigger: { trigger: list, start: 'top 80%' } });
    });
}

/** The six figures: each row's rule draws and its figure slides in. */
function factsRows() {
    const rows = Array.from(document.querySelectorAll('.about-facts__row'));
    rows.forEach((row) => {
        gsap.fromTo(row, { '--rule': 0 }, {
            '--rule': 1,
            ease: 'power2.out',
            scrollTrigger: { trigger: row, start: 'top 90%', end: 'top 60%', scrub: 0.6 },
        });
        gsap.fromTo(row.querySelector('.about-facts__value'), { xPercent: 30, opacity: 0 }, {
            xPercent: 0,
            opacity: 1,
            ease: 'power3.out',
            scrollTrigger: { trigger: row, start: 'top 90%', end: 'top 60%', scrub: 0.6 },
        });
    });

    const pic = document.querySelector('.about-facts__pic');
    if (pic) {
        gsap.fromTo(pic, { clipPath: 'inset(100% 0% 0% 0%)' }, {
            clipPath: 'inset(0% 0% 0% 0%)',
            duration: 1.4,
            ease: 'expo.inOut',
            scrollTrigger: { trigger: pic, start: 'top 85%' },
        });
    }
}

/** In Good Company: the tiles turn up into place, row by row. */
function companyTiles() {
    const grid = document.querySelector('.about-company__grid');
    const tiles = Array.from(grid?.children ?? []);
    if (!grid || tiles.length === 0) return;
    gsap.fromTo(tiles,
        { rotateX: -80, y: 40, opacity: 0, transformOrigin: '50% 100%' },
        {
            rotateX: 0,
            y: 0,
            opacity: 1,
            duration: 0.9,
            ease: 'power3.out',
            stagger: { each: 0.035, grid: 'auto', from: 'start' },
            scrollTrigger: { trigger: grid, start: 'top 80%' },
        });
}

function purposeSlide(section) {
    // The photo strip as a carousel of prints: each picture turns to face the
    // middle of the screen, flat at the centre and swung away toward the
    // edges, while the strip keeps running on its own clock.
    const strip = section.querySelector('.marquee');
    const cards = Array.from(strip?.querySelectorAll('li') ?? []);
    if (!strip || cards.length === 0) return;
    strip.classList.add('is-coverflow');

    let frame = 0;
    let visible = false;
    const turn = () => {
        frame = 0;
        const vw = window.innerWidth;
        for (const card of cards) {
            const r = card.getBoundingClientRect();
            if (r.right < -200 || r.left > vw + 200) continue;
            const d = ((r.left + r.width / 2) - vw / 2) / (vw / 2);
            const c = Math.max(-1.2, Math.min(1.2, d));
            card.style.transform =
                `perspective(1200px) rotateY(${(-c * 38).toFixed(2)}deg) scale(${(1 - Math.abs(c) * 0.12).toFixed(3)})`;
        }
        if (visible) frame = requestAnimationFrame(turn);
    };

    ScrollTrigger.create({
        trigger: strip,
        start: 'top bottom',
        end: 'bottom top',
        onToggle: (self) => {
            visible = self.isActive;
            if (visible && !frame) frame = requestAnimationFrame(turn);
        },
    });

    return () => {
        visible = false;
        strip.classList.remove('is-coverflow');
        cards.forEach((card) => (card.style.transform = ''));
    };
}

/* ------------------------------------------------------------------ */

export function initAboutScenes() {
    if (!document.querySelector('main[data-page="about"]') || prefersReducedMotion()) return;

    const all = sections();
    const hero = all[0];
    const intro = all[1];
    const vision = all.find((s) => s.classList.contains('bg-haze'));
    const approach = all.find((s) => s.querySelector('[data-drift]') && s.classList.contains('bg-white'));
    const values = all.find((s) => s.classList.contains('bg-night') && s.querySelector('li'));
    const purpose = all.find((s) => s.querySelector('.marquee'));
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
        const undo = [];
        if (hero) heroBlinds(hero);
        if (intro) introGrow(intro);
        if (vision) visionDoor(vision);
        if (approach) approachAssemble(approach);
        if (purpose) undo.push(purposeSlide(purpose));
        if (values) valuesRise(values);
        factsRows();
        companyTiles();
        return () => undo.forEach((fn) => fn?.());
    });

    window.addEventListener('load', () => ScrollTrigger.refresh());
}
