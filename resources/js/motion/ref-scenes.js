import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { prefersReducedMotion } from './scroll-engine';

gsap.registerPlugin(ScrollTrigger);

/**
 * The landing page's motion, each piece taken from one of the reference
 * sites and measured off it rather than guessed (the tweens were read out of
 * their own GSAP calls):
 *
 *   PROGRESS   era-residence.com     a hairline down the right edge filling
 *                                    with the page
 *   INLINE     Alwan                 a photograph opening up inside the about
 *                                    heading, between its first two lines
 *   READ       pelizzari.com         paragraphs read in word by word: each
 *                                    word 0.2 → 1, stagger 0.05, scrub 1,
 *                                    from "-100px 75%" to "100px 25%"
 *   COLLAGE    nabilissa.com +       the featured projects at different sizes
 *              era-residence.com     moving at their own rates, each picture
 *                                    drifting yPercent -15 → 15 in its frame,
 *                                    scrub 0.5
 *   MASK       Alwan (FACTORY)       the joinery word cut from a workshop
 *                                    photograph, grown until the picture
 *                                    fills the screen
 *   OPEN       era-residence.com     the enquiry backdrop opening out of
 *                                    inset(50%) to the full frame
 *   ROLL       pelizzari.com         a button's label rolling up and a copy
 *                                    rolling in under the pointer
 *
 * Nothing here adds copy; each works on markup the page already renders, and
 * gsap.matchMedia takes all of it back under reduced motion.
 */

/* ------------------------------------------------------------------ */

function progress() {
    const bar = document.createElement('div');
    bar.className = 'scroll-progress';
    bar.setAttribute('aria-hidden', 'true');
    bar.innerHTML = '<span></span>';
    document.body.append(bar);
    const fill = bar.firstElementChild;

    ScrollTrigger.create({
        start: 0,
        end: 'max',
        onUpdate: (self) => { fill.style.transform = `scaleY(${self.progress.toFixed(4)})`; },
    });

    return () => bar.remove();
}

/* ------------------------------------------------------------------ */

function aboutInline() {
    const heading = document.querySelector('#about h2');
    const lines = heading ? Array.from(heading.children) : [];
    const src = document.querySelector('#about img')?.getAttribute('src');
    if (lines.length < 2 || !src) return;

    const pic = document.createElement('span');
    pic.className = 'inline-pic';
    pic.setAttribute('aria-hidden', 'true');
    pic.innerHTML = `<img src="${src}" alt="">`;
    lines[0].append(pic);

    gsap.fromTo(pic, { width: '0em' }, {
        width: '2.6em',
        ease: 'power2.out',
        scrollTrigger: { trigger: heading, start: 'top 85%', end: 'top 40%', scrub: 1 },
    });
    gsap.fromTo(pic.firstChild, { scale: 1.6 }, {
        scale: 1,
        ease: 'none',
        scrollTrigger: { trigger: heading, start: 'top 85%', end: 'top 40%', scrub: 1 },
    });

    return () => pic.remove();
}

/* ------------------------------------------------------------------ */

function wrapWords(node) {
    if (node.dataset.read) return Array.from(node.querySelectorAll('.read-word'));
    node.dataset.read = '1';
    const walker = document.createTreeWalker(node, NodeFilter.SHOW_TEXT);
    const texts = [];
    while (walker.nextNode()) texts.push(walker.currentNode);
    for (const text of texts) {
        const frag = document.createDocumentFragment();
        for (const part of text.textContent.split(/(\s+)/)) {
            if (!part) continue;
            if (/^\s+$/.test(part)) { frag.append(part); continue; }
            const w = document.createElement('span');
            w.className = 'read-word';
            w.textContent = part;
            frag.append(w);
        }
        text.replaceWith(frag);
    }
    return Array.from(node.querySelectorAll('.read-word'));
}

function read() {
    /*
     * Any paragraph long enough to be read rather than glanced at. Forms,
     * the client strip, the 3D stage and the stat labels are left alone —
     * they are not prose.
     */
    const skip = 'form, .marquee, [data-stage], .numbers dd, .scope__row, .sstage, .sx, .rv, .sectors__item, footer, header, .service-slides, nav, li';
    const paragraphs = Array.from(document.querySelectorAll('main p'))
        .filter((p) => !p.closest(skip) && p.textContent.trim().length > 90 && !p.querySelector('a, input, img'));

    for (const p of paragraphs) {
        // The reveal class would fade the whole block over these; it has done
        // its job once the words carry the motion.
        p.classList.add('is-visible');
        const words = wrapWords(p);
        gsap.fromTo(words, { opacity: 0.2 }, {
            opacity: 1,
            stagger: 0.05,
            ease: 'none',
            scrollTrigger: { trigger: p, start: '-100px 75%', end: '100px 25%', scrub: 1 },
        });
    }
}

/**
 * era-residence.com's framed parallax, on every picture the site-wide unmask
 * has taken (scroll-fx.js) — the ones no other scene owns. Written to the
 * individual `translate` property so it composes with the unmask's own
 * clip and scale rather than fighting them; the unmask lands at a scale that
 * leaves the travel headroom.
 */
function drift() {
    const pictures = Array.from(document.querySelectorAll('img.fx-unmask'));
    const tweens = pictures.map((img) => gsap.fromTo(img, { '--drift': '-5%' }, {
        '--drift': '5%',
        ease: 'none',
        scrollTrigger: { trigger: img.parentElement, start: 'top bottom', end: 'bottom top', scrub: 0.5 },
    }));
    pictures.forEach((img) => img.classList.add('fx-drift'));
    return () => {
        tweens.forEach((t) => t.kill());
        pictures.forEach((img) => img.classList.remove('fx-drift'));
    };
}

/* ------------------------------------------------------------------ */

function projectsCollage() {
    const items = Array.from(document.querySelectorAll('#projects .collage__item'));
    for (const item of items) {
        const speed = Number(item.dataset.speed) || 0;
        gsap.fromTo(item, { yPercent: speed * 100 }, {
            yPercent: -speed * 100,
            ease: 'none',
            scrollTrigger: { trigger: item, start: 'top bottom', end: 'bottom top', scrub: 0.5 },
        });

        const img = item.querySelector('.collage__img');
        gsap.fromTo(img, { yPercent: -15 }, {
            yPercent: 15,
            ease: 'none',
            scrollTrigger: { trigger: item, start: 'top bottom', end: 'bottom top', scrub: 0.5 },
        });

        // The frame opens from its foot as it arrives, and the caption rises
        // after it on the reference's line ease.
        const frame = item.querySelector('.collage__frame');
        gsap.fromTo(frame, { clipPath: 'inset(100% 0% 0% 0%)' }, {
            clipPath: 'inset(0% 0% 0% 0%)',
            duration: 1.4,
            ease: 'expo.inOut',
            scrollTrigger: { trigger: item, start: 'top 88%' },
        });
        gsap.fromTo(item.querySelector('.collage__caption').children, { yPercent: 100, opacity: 0 }, {
            yPercent: 0,
            opacity: 1,
            duration: 1.5,
            ease: 'power4.out',
            stagger: 0.1,
            delay: 0.5,
            scrollTrigger: { trigger: item, start: 'top 88%' },
        });
    }
}

/* ------------------------------------------------------------------ */

function joineryMask() {
    const block = document.querySelector('[data-jmask]');
    const word = block?.querySelector('.jmask__word');
    const full = block?.querySelector('.jmask__full');
    if (!block || !word || !full) return;

    block.classList.add('is-live');

    gsap.timeline({
        scrollTrigger: {
            trigger: block,
            start: 'top top',
            end: () => `+=${window.innerHeight * 1.1}`,
            pin: true,
            scrub: 1,
            invalidateOnRefresh: true,
        },
    })
        .fromTo(word, { scale: 1 }, { scale: 14, ease: 'power3.in', duration: 1 }, 0)
        .fromTo(full, { opacity: 0, scale: 1.25 }, { opacity: 1, scale: 1, ease: 'power2.out', duration: 0.35 }, 0.65);

    return () => block.classList.remove('is-live');
}

/* ------------------------------------------------------------------ */

function inquiryOpen() {
    const section = document.querySelector('#contact');
    const bg = section?.querySelector(':scope > img');
    const card = section?.querySelector(':scope > .reveal');
    if (!section || !bg) return;

    gsap.fromTo(bg, { clipPath: 'inset(30% 30% 30% 30%)', scale: 1.2 }, {
        clipPath: 'inset(0% 0% 0% 0%)',
        scale: 1,
        ease: 'power2.out',
        scrollTrigger: { trigger: section, start: 'top 90%', end: 'top 20%', scrub: 0.5 },
    });

    if (card) {
        card.classList.add('is-visible');
        gsap.fromTo(card, { y: 120, opacity: 0 }, {
            y: 0,
            opacity: 1,
            duration: 1.5,
            ease: 'power4.out',
            scrollTrigger: { trigger: section, start: 'top 60%' },
        });
    }
}

/* ------------------------------------------------------------------ */

function pillRoll() {
    for (const pill of document.querySelectorAll('.pill')) {
        if (pill.querySelector('.roll')) continue;
        const textNode = Array.from(pill.childNodes).find((n) => n.nodeType === 3 && n.textContent.trim());
        if (!textNode) continue;
        const label = textNode.textContent.trim();
        const roll = document.createElement('span');
        roll.className = 'roll';
        roll.innerHTML = `<span class="roll__a">${label}</span><span class="roll__b" aria-hidden="true">${label}</span>`;
        textNode.replaceWith(roll);
    }
}

/* ------------------------------------------------------------------ */

export function initRefScenes() {
    if (prefersReducedMotion()) return;
    const home = document.querySelector('main[data-page="home"]');

    pillRoll();

    const mm = gsap.matchMedia();
    mm.add('(prefers-reduced-motion: no-preference)', () => {
        const undo = [progress()];
        if (home) {
            undo.push(aboutInline());
            if (!document.querySelector('#projects.has-stage')) projectsCollage();
        }
        // Every marketing page: paragraphs read in, framed pictures drift
        // against the scroll, and the enquiry band opens.
        read();
        undo.push(drift());
        inquiryOpen();
        return () => undo.forEach((fn) => fn?.());
    });

    window.addEventListener('load', () => ScrollTrigger.refresh());
}
