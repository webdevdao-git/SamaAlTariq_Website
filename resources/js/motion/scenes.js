import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { prefersReducedMotion } from './scroll-engine';

gsap.registerPlugin(ScrollTrigger);

/**
 * The landing page's scroll choreography, after the reference set: the page
 * is not one long column that slides past, it is a run of scenes that hold,
 * play, and hand over to the next.
 *
 *   OPEN       the hero photograph opens out of a rounded window to the full
 *              screen as the entry curtain rises
 *   CLOSE      scrolling on, it gathers back into an inset card on the night
 *              ground while the next section comes up over it
 *   READ       section intros ink in word by word as they cross the screen
 *   RAIL       the featured projects run sideways on a pinned rail
 *   SERVICES   the three service panels change with the scroll, not the tabs
 *   STACK      each scene holds as the next slides up over it, settling back
 *              and darkening beneath it
 *   PRECISION  the word starts the size of the screen and sets into its line
 *   FOOTER     the page lifts off the footer, which was waiting underneath
 *
 * Everything is built from markup the page already renders — no copy is
 * added, moved or hidden — and gsap.matchMedia reverts every scene cleanly
 * when the screen crosses a breakpoint or reduced motion is switched on, so
 * the page underneath is always the plain one.
 *
 * Desktop gets the pinned scenes. Phones keep the opening, the reading and
 * the footer, and scroll through the rest normally: pinning on a touch screen
 * fights the browser's own momentum.
 */
const EASE = 'expo.inOut';

function words(node) {
    // Wrap each word once, keeping the element's own line structure intact.
    if (node.dataset.words) return Array.from(node.querySelectorAll('.scene-word'));
    node.dataset.words = '1';
    node.setAttribute('aria-label', node.textContent.replace(/\s+/g, ' ').trim());

    const walker = document.createTreeWalker(node, NodeFilter.SHOW_TEXT);
    const texts = [];
    while (walker.nextNode()) texts.push(walker.currentNode);

    for (const text of texts) {
        const parts = text.textContent.split(/(\s+)/);
        const fragment = document.createDocumentFragment();
        for (const part of parts) {
            if (!part) continue;
            if (/^\s+$/.test(part)) {
                fragment.append(part);
                continue;
            }
            const span = document.createElement('span');
            span.className = 'scene-word';
            span.setAttribute('aria-hidden', 'true');
            span.textContent = part;
            fragment.append(span);
        }
        text.replaceWith(fragment);
    }

    return Array.from(node.querySelectorAll('.scene-word'));
}

/* ------------------------------------------------------------------ */

/**
 * Writes the hero's clip from four insets and two corner radii.
 *
 * Built by hand rather than tweened as a clip-path string: the browser
 * shortens a symmetric inset — "22% 39% 10% 39%" comes back as
 * "22% 39% 10%" — and GSAP, reading that back as the start of a tween, pairs
 * the values up wrongly and sends the left edge across the screen. Numbers
 * in, string out, and there is nothing for it to mis-read.
 */
function clipper(media) {
    const box = { t: 0, r: 0, b: 0, l: 0, arch: 0, round: 0 };
    const write = () => {
        media.style.clipPath =
            `inset(${box.t}% ${box.r}% ${box.b}% ${box.l}% round ${box.arch}px ${box.arch}px ${box.round}px ${box.round}px)`;
    };
    return { box, write };
}

function heroOpen() {
    const media = document.querySelector('[data-hero-media]');
    if (!media) return;

    const img = media.querySelector('img');
    const wide = window.innerWidth >= 1024;
    const { box, write } = clipper(media);

    /*
     * An arch, not a circle: the round goes on the two top corners only, so
     * the first thing the visitor sees is a doorway with the room beyond it —
     * a building's opening rather than a porthole.
     */
    Object.assign(box, wide
        ? { t: 22, r: 39, b: 10, l: 39, arch: window.innerWidth * 0.12, round: 0 }
        : { t: 24, r: 22, b: 12, l: 22, arch: window.innerWidth * 0.28, round: 0 });
    write();
    if (img) gsap.set(img, { scale: 1.45 });

    const play = () => {
        gsap.to(box, {
            t: 0, r: 0, b: 0, l: 0, arch: 0,
            duration: 2.4,
            ease: EASE,
            // Most of the way through the curtain's wipe, so the arch is seen
            // standing for a beat before it starts to open.
            delay: 0.75,
            onUpdate: write,
            // hero-gl.js waits for this before laying its canvas over the
            // photograph, so the shader never covers the zoom.
            onComplete: () => {
                media.style.clipPath = '';
                window.__heroOpened = true;
                window.dispatchEvent(new CustomEvent('hero:opened'));
            },
        });
        if (img) gsap.to(img, { scale: 1, duration: 3, ease: 'expo.out', delay: 0.75 });
    };

    // No curtain on the page (it has already gone, or never ran): open now.
    if (document.querySelector('.intro-curtain')) {
        window.addEventListener('curtain:lift', play, { once: true });
    } else {
        play();
    }
}

function heroClose() {
    const hero = document.querySelector('#top');
    const media = document.querySelector('[data-hero-media]');
    if (!hero || !media) return;

    const veil = document.createElement('div');
    veil.className = 'scene-veil';
    media.append(veil);

    const { box, write } = clipper(media);

    ScrollTrigger.create({
        trigger: hero,
        start: 'top top',
        end: 'bottom top',
        scrub: true,
        onUpdate: (self) => {
            // Left alone until the opening has finished with the clip.
            if (!window.__heroOpened) return;

            const p = self.progress;
            Object.assign(box, { t: 6 * p, r: 4 * p, b: 14 * p, l: 4 * p, arch: 36 * p, round: 36 * p });
            if (p === 0) media.style.clipPath = '';
            else write();
            veil.style.opacity = (0.55 * p).toFixed(3);
        },
    });
}

/* ------------------------------------------------------------------ */

/**
 * The landing hero: zoom through the word.
 *
 * The page opens on the night ground with the company's name — SAMA — set
 * across it, and the word is a window: the photograph is seen only through
 * its letters. Scrolling pins the hero and flies the camera into one stroke
 * of that word (an I if it has one, otherwise its middle letter, aimed at
 * the widest run of ink, so the opening is photograph and never the dark
 * between letters) until the word is larger than the screen and the
 * photograph is all that is left. Only then does the
 * headline and the intro come up over the room.
 *
 * Drawn as an SVG mask — a night-coloured sheet with the word cut out of it
 * — laid over the photograph and under the header, so MENU and the lock-up
 * stay put throughout. The word is read from the section, and the type is
 * the page's display face.
 */
function heroZoom() {
    const hero = document.querySelector('#top[data-hero-zoom]');
    const media = hero?.querySelector('[data-hero-media]');
    const content = hero?.querySelector('[data-hero] > .mt-auto');
    if (!hero || !media || !content) return;

    const img = media.querySelector('img');
    const word = (hero.dataset.word || 'Sama').toUpperCase();
    const NS = 'http://www.w3.org/2000/svg';
    const id = 'hero-hole';

    const svg = document.createElementNS(NS, 'svg');
    svg.setAttribute('class', 'hero-mask');
    svg.setAttribute('aria-hidden', 'true');
    svg.innerHTML = `
        <defs>
            <mask id="${id}" maskUnits="userSpaceOnUse">
                <rect data-sheet fill="#fff" x="0" y="0"/>
                <g data-word><text fill="#000" text-anchor="middle">${word.replace(/[<&>]/g, '')}</text></g>
            </mask>
        </defs>
        <rect data-ground fill="#161616" x="0" y="0" mask="url(#${id})"/>`;

    const scrim = document.createElement('div');
    scrim.className = 'hero-mask__cue';
    scrim.setAttribute('aria-hidden', 'true');

    hero.append(svg, scrim);

    const sheet = svg.querySelector('[data-sheet]');
    const ground = svg.querySelector('[data-ground]');
    const group = svg.querySelector('[data-word]');
    const text = svg.querySelector('text');

    const origin = { x: 0, y: 0 };

    const layout = () => {
        const w = hero.clientWidth;
        const h = hero.clientHeight;
        svg.setAttribute('viewBox', `0 0 ${w} ${h}`);
        for (const r of [sheet, ground]) {
            r.setAttribute('width', w);
            r.setAttribute('height', h);
        }

        // Fit the word to 88% of the width, capped so it never runs taller
        // than two-fifths of the screen.
        text.setAttribute('font-size', '100');
        text.setAttribute('x', w / 2);
        const measured = text.getComputedTextLength() || 1;
        const size = Math.min((w * 0.88 * 100) / measured, h * 0.42);
        text.setAttribute('font-size', size.toFixed(2));
        text.setAttribute('y', (h / 2 + size * 0.34).toFixed(2));

        // The I nearest the middle of the word; failing that, the middle
        // letter. Aimed a little below its centre, inside the stem.
        const letters = [...word];
        const middle = (letters.length - 1) / 2;
        let pick = Math.round(middle);
        let best = Infinity;
        letters.forEach((ch, i) => {
            if (ch === 'I' && Math.abs(i - middle) < best) {
                best = Math.abs(i - middle);
                pick = i;
            }
        });
        try {
            const box = text.getExtentOfChar(pick);
            origin.x = box.x + box.width / 2;
            origin.y = box.y + box.height * 0.55;
            aimAtStroke(box, size);
        } catch {
            origin.x = w / 2;
            origin.y = h / 2;
        }
        draw();
    };

    /*
     * A letter's box is its advance, not its ink: the stem of a serif I does
     * not sit in the middle of it, and aiming at the middle sent the zoom
     * past the stroke's edge into the dark beside it. So the word is drawn
     * once, off screen, in the same face and size, and the widest run of ink
     * across the letter at mid-height is what the camera flies into.
     */
    function aimAtStroke(box, size) {
        const probe = document.createElement('canvas');
        const pad = Math.ceil(box.width);
        probe.width = Math.ceil(box.width + pad * 2);
        probe.height = Math.ceil(box.height);
        const ctx = probe.getContext('2d');
        if (!ctx) return;

        const style = getComputedStyle(text);
        ctx.font = `${style.fontWeight} ${size}px ${style.fontFamily}`;
        ctx.textBaseline = 'alphabetic';
        ctx.fillStyle = '#000';

        // The same glyph, at the same place in the probe as in its box.
        const baseline = Number(text.getAttribute('y')) - box.y;
        ctx.fillText(word[pick], pad, baseline);

        const y = Math.round(origin.y - box.y);
        const row = ctx.getImageData(0, y, probe.width, 1).data;
        let best = null;
        let start = -1;
        for (let x = 0; x <= probe.width; x++) {
            const ink = x < probe.width && row[x * 4 + 3] > 128;
            if (ink && start < 0) start = x;
            if (!ink && start >= 0) {
                if (!best || x - start > best[1] - best[0]) best = [start, x];
                start = -1;
            }
        }
        if (best) origin.x = box.x + (best[0] + best[1]) / 2 - pad;
    }

    /*
     * The word's transform is written by hand, about the origin as it stands
     * now. GSAP's svgOrigin is fixed when first set, and the first layout runs
     * before the display face has loaded — the stroke it aimed at then is not
     * where the I is once the real type is in, and the zoom flew into the dark
     * between two letters.
     */
    const zoom = { s: 1, o: 1 };
    function draw() {
        group.setAttribute('transform',
            `translate(${origin.x} ${origin.y}) scale(${zoom.s}) translate(${-origin.x} ${-origin.y})`);
        group.style.opacity = zoom.o;
    }

    layout();
    document.fonts?.ready.then(layout);
    ScrollTrigger.addEventListener('refreshInit', layout);

    // Held back until the zoom has opened the room.
    gsap.set(content, { autoAlpha: 0, y: 70 });

    // The entrance: the word settles in from slightly large as the curtain
    // lifts, and the room behind it drifts, so the letters read as windows
    // onto a real place rather than a texture.
    const enter = () => {
        gsap.fromTo(zoom, { s: 1.12, o: 0 }, { s: 1, o: 1, duration: 1.8, ease: 'expo.out', delay: 0.35, onUpdate: draw });
        if (img) gsap.fromTo(img, { scale: 1.45 }, { scale: 1.25, duration: 3, ease: 'expo.out', delay: 0.35 });
    };
    if (document.querySelector('.intro-curtain')) {
        window.addEventListener('curtain:lift', enter, { once: true });
    } else {
        enter();
    }

    gsap.timeline({
        scrollTrigger: {
            trigger: hero,
            start: 'top top',
            end: () => `+=${window.innerHeight * 1.7}`,
            pin: true,
            scrub: 1,
            invalidateOnRefresh: true,
        },
    })
        .fromTo(zoom, { s: 1 }, { s: 95, ease: 'power3.in', duration: 1, onUpdate: draw, immediateRender: false }, 0)
        .to(scrim, { autoAlpha: 0, duration: 0.15 }, 0)
        .fromTo(img, { scale: 1.25 }, { scale: 1, ease: 'none', duration: 1, immediateRender: false }, 0)
        .to(svg, { autoAlpha: 0, duration: 0.08 }, 0.9)
        .to(content, { autoAlpha: 1, y: 0, duration: 0.4, ease: 'power2.out' }, 0.92);

    // The shader can lay its canvas over the photograph straight away: it
    // sits under the mask, so it bends the room inside the letters too.
    window.__heroOpened = true;
    window.dispatchEvent(new CustomEvent('hero:opened'));
}

/* ------------------------------------------------------------------ */

function read() {
    const heads = document.querySelectorAll('#about h2.display, #services h2.display, #contact h2.display');

    for (const head of heads) {
        const list = words(head);
        gsap.fromTo(list,
            { opacity: 0.12, y: '0.25em' },
            {
                opacity: 1,
                y: 0,
                stagger: 0.08,
                ease: 'none',
                scrollTrigger: { trigger: head, start: 'top 85%', end: 'bottom 45%', scrub: true },
            });
    }
}

/* ------------------------------------------------------------------ */

function rail() {
    const section = document.querySelector('#projects');
    const shell = section?.querySelector('.grid')?.parentElement;
    if (!section || !shell) return;

    section.classList.add('is-rail');

    const track = shell;
    track.setAttribute('data-rail', '');
    const distance = () => Math.max(0, track.scrollWidth - window.innerWidth + track.getBoundingClientRect().left * 2);

    const tween = gsap.to(track, {
        x: () => -distance(),
        ease: 'none',
        scrollTrigger: {
            trigger: section,
            start: 'top top',
            end: () => `+=${distance()}`,
            pin: true,
            scrub: 1,
            invalidateOnRefresh: true,
        },
    });

    // Each card rises out of a tilt as the rail brings it in from the right.
    for (const card of track.querySelectorAll('figure')) {
        gsap.fromTo(card,
            { rotateY: -18, z: -200, opacity: 0.4 },
            {
                rotateY: 0,
                z: 0,
                opacity: 1,
                ease: 'none',
                scrollTrigger: {
                    trigger: card,
                    containerAnimation: tween,
                    start: 'left 100%',
                    end: 'left 45%',
                    scrub: true,
                },
            });
    }

    return () => {
        section.classList.remove('is-rail');
        track.removeAttribute('data-rail');
    };
}

/* ------------------------------------------------------------------ */

function services() {
    const box = document.querySelector('#services .service-slides');
    if (!box) return;

    const slides = Array.from(box.querySelectorAll('.service-slide'));
    const n = slides.length;
    if (n < 2) return;

    // Above <main>, which the footer reveal lifts onto layer 1: once the box is
    // reparented to <body> for its pin it would otherwise sit underneath it.
    box.style.zIndex = '20';

    /*
     * The panels become the faces of one prism — three services, three faces
     * — and the scroll turns it. Each face is the panel exactly as it is
     * drawn, tab row and all; they are only moved into a turning frame and
     * stood round its axis. The frame pulls back from the screen as it turns,
     * so the edge between two faces is seen coming past, and each face darkens
     * as it turns away.
     */
    const prism = document.createElement('div');
    prism.className = 'service-prism';
    box.prepend(prism);
    for (const slide of slides) prism.append(slide);
    box.classList.add('is-prism');

    const step = 360 / n;
    const veils = slides.map((slide) => {
        const veil = document.createElement('div');
        veil.className = 'service-prism__veil';
        veil.setAttribute('aria-hidden', 'true');
        slide.append(veil);
        return veil;
    });

    // The distance from the axis to the middle of a face — the apothem of a
    // regular n-gon whose side is the panel's height.
    const apothem = () => box.clientHeight / (2 * Math.tan(Math.PI / n));

    const place = () => {
        const a = apothem();
        slides.forEach((slide, i) => {
            slide.style.transform = `rotateX(${-i * step}deg) translateZ(${a.toFixed(1)}px)`;
        });
    };
    place();
    window.addEventListener('resize', place);

    let current = -1;
    const turn = (f) => {
        const a = apothem();
        const between = Math.abs(f - Math.round(f));
        const back = Math.sin(Math.min(between, 0.5) * Math.PI);
        prism.style.transform =
            `translateZ(${(-a - back * a * 0.9).toFixed(1)}px) rotateX(${(f * step).toFixed(3)}deg)`;

        slides.forEach((_, i) => {
            const off = Math.min(1, Math.abs(f - i));
            veils[i].style.opacity = (off * 0.85).toFixed(3);
        });

        // Keep the tab rows marking the face in front.
        const i = Math.max(0, Math.min(n - 1, Math.round(f)));
        if (i !== current) {
            current = i;
            for (const slide of slides) {
                for (const tab of slide.querySelectorAll('nav a')) {
                    const on = tab.getAttribute('href') === `#${slides[i].id}`;
                    tab.toggleAttribute('aria-current', on);
                    tab.classList.toggle('is-current', on);
                }
            }
        }
    };

    const trigger = ScrollTrigger.create({
        trigger: box,
        start: 'top top',
        end: () => `+=${window.innerHeight * (n - 1) * 1.1}`,
        pin: true,
        /*
         * Lifted out to <body> while it is pinned. The services section is
         * itself a layer in the stack below, and GSAP keeps a transform on it
         * for that — which turns it into the containing block for anything
         * fixed inside it. Pinned in place, the box was fixed to the section
         * instead of the screen and scrolled away under a blank page.
         */
        pinReparent: true,
        scrub: 1,
        snap: { snapTo: 1 / (n - 1), duration: { min: 0.3, max: 0.8 }, delay: 0.05, ease: 'power2.inOut' },
        invalidateOnRefresh: true,
        onUpdate: (self) => turn(self.progress * (n - 1)),
        onRefresh: (self) => turn(self.progress * (n - 1)),
    });
    turn(0);

    // A tab turns the prism to its face by scrolling there, rather than
    // switching panels in place.
    box.addEventListener('click', (event) => {
        const tab = event.target.closest('a[href^="#service-"]');
        if (!tab) return;
        const i = slides.findIndex((s) => `#${s.id}` === tab.getAttribute('href'));
        if (i < 0) return;
        event.preventDefault();
        event.stopImmediatePropagation();
        const y = trigger.start + (trigger.end - trigger.start) * (i / (n - 1));
        window.scrollTo({ top: y, behavior: 'smooth' });
    }, true);
}

/* ------------------------------------------------------------------ */

function precision() {
    const section = Array.from(document.querySelectorAll('main > section.bg-night')).find((s) => s.querySelector('.text-fluid-mega'));
    if (!section) return;

    const word = section.querySelector('h2.editorial-heading');
    const photo = section.querySelector('img')?.parentElement;
    const copy = section.querySelector('.pill')?.parentElement;
    if (!word) return;

    word.classList.add('is-visible');
    word.style.transition = 'none';

    gsap.timeline({
        scrollTrigger: {
            trigger: section,
            start: 'top top',
            end: () => `+=${window.innerHeight * 1.1}`,
            pin: true,
            scrub: 1,
        },
    })
        .fromTo(word, { scale: 3.6, xPercent: 6, transformOrigin: '0% 100%' }, { scale: 1, xPercent: 0, ease: 'power3.inOut' }, 0)
        .fromTo(photo, { yPercent: 40, opacity: 0, scale: 0.8 }, { yPercent: 0, opacity: 1, scale: 1, ease: 'power3.out' }, 0.15)
        .fromTo(copy, { y: 60, opacity: 0 }, { y: 0, opacity: 1, ease: 'power2.out' }, 0.45);
}

/* ------------------------------------------------------------------ */

/**
 * About: the photograph arrives laid back in space, as a print lying on a
 * table seen from its edge, and stands up to face the reader as the section
 * crosses the screen — its picture settling inside the frame as it does.
 */
function aboutUnfold() {
    const frame = document.querySelector('#about .aspect-\\[389\\/272\\]');
    if (!frame) return;

    const img = frame.querySelector('img');
    frame.parentElement.style.perspective = '1200px';

    gsap.timeline({
        scrollTrigger: { trigger: frame, start: 'top 95%', end: 'top 35%', scrub: 1 },
    })
        .fromTo(frame,
            { rotateX: 62, rotateZ: -6, y: 120, scale: 0.78, transformOrigin: '50% 100%', boxShadow: '0 0 0 rgba(0,0,0,0)' },
            { rotateX: 0, rotateZ: 0, y: 0, scale: 1, boxShadow: '0 40px 80px -40px rgba(0,0,0,0.45)', ease: 'power2.out' }, 0)
        .fromTo(img, { scale: 1.35 }, { scale: 1, ease: 'power2.out' }, 0);
}

/**
 * Clients: the logo strip becomes a band wrapped round a cylinder, turning
 * slowly, the marks on the near side full and those swinging round to the
 * far side falling away. The flat strip stays in the markup underneath for a
 * screen reader and for anything that cannot run this.
 */
function clientsRing() {
    const marquee = document.querySelector('.marquee');
    const list = marquee?.querySelector('ul');
    if (!marquee || !list) return;

    const logos = Array.from(list.querySelectorAll('img'));
    const n = logos.length;
    if (n < 6) return;

    const ring = document.createElement('div');
    ring.className = 'logo-ring';
    ring.setAttribute('aria-hidden', 'true');
    const spin = document.createElement('div');
    spin.className = 'logo-ring__spin marquee__track';
    ring.append(spin);

    logos.forEach((logo, i) => {
        const cell = document.createElement('div');
        cell.className = 'logo-ring__cell';
        cell.style.setProperty('--i', i);
        const copy = logo.cloneNode();
        copy.alt = '';
        copy.removeAttribute('loading');
        cell.append(copy);
        spin.append(cell);
    });

    // On the strip, not the ring: the radius is worked out there from the
    // count, and a custom property only reaches down the tree.
    marquee.style.setProperty('--n', n);
    marquee.classList.add('has-ring');
    marquee.append(ring);

    return () => {
        ring.remove();
        marquee.classList.remove('has-ring');
        marquee.style.removeProperty('--n');
    };
}

/**
 * Process as a timelapse of the one house it photographs going up: an empty
 * plot, the set-out, the frame, the finished villa. The section holds and the
 * four pictures share one frame; each next stage rises up into it from the
 * ground line, settling from a push-in as the one beneath sinks back into
 * shadow. The step on the left changes with it, a rail beside the steps fills
 * as the build goes on, and the stage number stands large in the frame's
 * corner.
 */
function processTimelapse() {
    const root = document.querySelector('#process[data-process]');
    const figures = Array.from(root?.querySelectorAll('[data-process-image]') ?? []);
    const steps = Array.from(root?.querySelectorAll('[data-process-step]') ?? []);
    const stack = root?.querySelector('[data-process-stack]');
    const n = figures.length;
    if (!root || n < 2 || steps.length !== n || !stack) return;

    root.classList.add('is-timelapse');
    const film = figures[0].parentElement;
    film.classList.add('process-film');

    const images = figures.map((f) => f.querySelector('img'));
    const shades = figures.map((figure) => {
        const shade = document.createElement('div');
        shade.className = 'process-film__shade';
        shade.setAttribute('aria-hidden', 'true');
        figure.append(shade);
        return shade;
    });

    // The stage number in the frame's corner: the steps' own numbers.
    const count = document.createElement('div');
    count.className = 'process-film__count';
    count.setAttribute('aria-hidden', 'true');
    count.innerHTML = steps.map((step) => `<span>${step.querySelector('p')?.textContent.trim() ?? ''}</span>`).join('');
    film.append(count);
    const numbers = Array.from(count.children);

    // The rail beside the steps: one track, one fill, a tick per stage.
    const rail = document.createElement('div');
    rail.className = 'process-rail';
    rail.setAttribute('aria-hidden', 'true');
    rail.innerHTML = `<span class="process-rail__fill"></span>${steps.map(() => '<i></i>').join('')}`;
    stack.parentElement.insertBefore(rail, stack);
    const fill = rail.firstElementChild;
    const ticks = Array.from(rail.querySelectorAll('i'));

    let current = -1;
    const play = (f) => {
        figures.forEach((figure, i) => {
            const rise = i === 0 ? 1 : Math.min(1, Math.max(0, f - (i - 1)));
            const e = 1 - Math.pow(1 - rise, 3);
            figure.style.clipPath = i === 0 ? '' : `inset(${((1 - e) * 100).toFixed(2)}% 0 0 0)`;
            images[i].style.transform = `scale(${(1.22 - 0.22 * e).toFixed(4)})`;

            // Sunk back once the next stage has risen over it.
            const over = Math.min(1, Math.max(0, f - i));
            shades[i].style.opacity = (over * 0.6).toFixed(3);
            figure.style.transform = `scale(${(1 - over * 0.04).toFixed(4)})`;
        });

        fill.style.transform = `scaleY(${(f / (n - 1)).toFixed(4)})`;

        const i = Math.max(0, Math.min(n - 1, Math.round(f)));
        if (i === current) return;
        current = i;
        steps.forEach((step, k) => {
            step.style.opacity = k === i ? '1' : '0';
            step.style.transform = `translate3d(0, ${k === i ? 0 : (k < i ? -26 : 26)}px, 0)`;
            step.toggleAttribute('aria-hidden', k !== i);
        });
        ticks.forEach((tick, k) => tick.classList.toggle('is-done', k <= i));
        numbers.forEach((num, k) => num.classList.toggle('is-current', k === i));
    };

    ScrollTrigger.create({
        trigger: root,
        start: 'top top',
        end: () => `+=${window.innerHeight * (n - 1) * 0.85}`,
        pin: true,
        scrub: 1,
        snap: { snapTo: 1 / (n - 1), duration: { min: 0.3, max: 0.8 }, delay: 0.05, ease: 'power2.inOut' },
        invalidateOnRefresh: true,
        onUpdate: (self) => play(self.progress * (n - 1)),
        onRefresh: (self) => play(self.progress * (n - 1)),
    });
    play(0);

    return () => {
        root.classList.remove('is-timelapse');
        film.classList.remove('process-film');
        rail.remove();
        count.remove();
        shades.forEach((shade) => shade.remove());
    };
}

/**
 * Contact: the photograph behind the card pushes in as the section rises, and
 * the card itself comes up out of the floor on a tilt — then its fields step
 * in one after another as it settles.
 */
function contactRise() {
    const section = document.querySelector('#contact');
    const card = section?.querySelector('.rounded-\\[clamp\\(20px\\,2\\.31vw\\,40px\\)\\]');
    const bg = section?.querySelector(':scope > img');
    if (!section || !card) return;

    section.style.perspective = '1600px';
    card.classList.add('is-visible');
    card.style.transition = 'none';

    gsap.timeline({
        scrollTrigger: { trigger: section, start: 'top bottom', end: 'top 15%', scrub: 1 },
    })
        .fromTo(card,
            { rotateX: 24, y: 220, scale: 0.9, opacity: 0.2, transformOrigin: '50% 100%' },
            { rotateX: 0, y: 0, scale: 1, opacity: 1, ease: 'power3.out' }, 0)
        .fromTo(bg, { scale: 1.3 }, { scale: 1, ease: 'none' }, 0);

    const fields = card.querySelectorAll('label, .field, .field-select, button, h2, p');
    gsap.fromTo(fields, { y: 30, opacity: 0 }, {
        y: 0,
        opacity: 1,
        stagger: 0.04,
        duration: 0.9,
        ease: 'expo.out',
        scrollTrigger: { trigger: card, start: 'top 70%' },
    });
}

/* ------------------------------------------------------------------ */

/**
 * Each section in the list holds once its foot reaches the foot of the
 * screen, and the next — positioned above it — rides up over it for one
 * screen. Underneath, it settles back and a veil darkens it, so the two read
 * as layers rather than as one sheet sliding past.
 */
function stack(selectors) {
    const sections = selectors.map((sel) => document.querySelector(sel)).filter(Boolean);

    /*
     * The layer order is handed over as a custom property the stylesheet
     * applies, not as an inline z-index: a section that is pinned by a scene
     * of its own (the process timelapse) has its inline style restored by
     * that pin on every refresh, and the z-index went with it — the section
     * coming up then slid in under the one it was meant to cover.
     */
    sections.forEach((section, i) => {
        section.classList.add('scene-layer');
        section.style.setProperty('--layer', String(10 + i));
    });

    sections.slice(0, -1).forEach((section) => {
        const veil = document.createElement('div');
        veil.className = 'scene-veil';
        section.append(veil);

        const tall = () => section.offsetHeight > window.innerHeight;

        gsap.timeline({
            scrollTrigger: {
                trigger: section,
                start: () => (tall() ? 'bottom bottom' : 'top top'),
                end: () => `+=${window.innerHeight}`,
                pin: true,
                pinSpacing: false,
                scrub: true,
                invalidateOnRefresh: true,
                /*
                 * Measured after every other pin. Pinning fixes the section's
                 * height at whatever it measures when this is refreshed, and
                 * the services section holds a pin of its own — measured
                 * first, this froze the section at its height without that
                 * pin's room, and the process section rode up over the
                 * panels while they were still playing.
                 */
                refreshPriority: -1,
            },
        })
            /*
             * immediateRender off, and it matters: a transform on the section
             * makes it the containing block for anything position: fixed
             * inside it, and the services box inside is pinned that way while
             * it plays. Written from the first frame, the transform knocked
             * that pin out and the box scrolled away under a blank screen.
             * Held back, the section carries no transform until its own turn.
             */
            .fromTo(section,
                { scale: 1, borderRadius: 0 },
                {
                    scale: 0.9,
                    borderRadius: 32,
                    ease: 'none',
                    immediateRender: false,
                    transformOrigin: () => `50% ${tall() ? section.offsetHeight - window.innerHeight / 2 : section.offsetHeight / 2}px`,
                }, 0)
            .fromTo(veil, { opacity: 0 }, { opacity: 0.6, ease: 'none', immediateRender: false }, 0);
    });
}

/* ------------------------------------------------------------------ */

function footerReveal() {
    const footer = document.querySelector('main ~ footer');
    const main = document.querySelector('main');
    if (!footer || !main) return;

    /*
     * Everything in the page's flow before the footer has to sit above it,
     * not only <main>: the project pages open with their hero outside <main>,
     * and a sticky footer is pulled up the whole height of <body> — with
     * nothing on a higher layer over the hero, it showed straight through it
     * at the top of the page. Fixed and absolute overlays (the menu, the
     * profile button) are left alone; giving them a position would undo
     * theirs.
     */
    const layers = [];
    for (let node = footer.previousElementSibling; node; node = node.previousElementSibling) {
        if (node === main) continue;
        const style = getComputedStyle(node);
        if (style.display === 'none' || ['fixed', 'absolute'].includes(style.position)) continue;
        layers.push(node);
    }

    const fits = () => footer.offsetHeight < window.innerHeight * 0.96;
    const apply = () => {
        const on = fits();
        footer.classList.toggle('scene-footer', on);
        main.classList.toggle('scene-main', on);
        for (const node of layers) node.classList.toggle('scene-above', on);
    };
    apply();
    window.addEventListener('resize', apply);

    return () => {
        window.removeEventListener('resize', apply);
        footer.classList.remove('scene-footer');
        main.classList.remove('scene-main');
        for (const node of layers) node.classList.remove('scene-above');
    };
}

/* ------------------------------------------------------------------ */

export function initScenes() {
    if (prefersReducedMotion()) return;

    const home = Boolean(document.querySelector('[data-hero]'));
    const mm = gsap.matchMedia();

    mm.add('(prefers-reduced-motion: no-preference)', () => {
        const undo = [];
        if (home) {
            if (document.querySelector('#top[data-hero-zoom]')) heroZoom();
            else heroOpen();
            aboutUnfold();
            undo.push(clientsRing());
            contactRise();
        }
        read();
        return () => undo.forEach((fn) => fn?.());
    });

    mm.add('(min-width: 1024px) and (prefers-reduced-motion: no-preference)', () => {
        const undo = [];

        if (home) {
            if (!document.querySelector('#top[data-hero-zoom]')) heroClose();
            // The projects section is the 3D stage now (project-stage.js);
            // the rail only runs where the stage could not be built.
            if (!document.querySelector('#projects.has-stage')) undo.push(rail());
            services();
            undo.push(processTimelapse());
            stack(['#services', '#process']);
            precision();
        }

        undo.push(footerReveal());

        return () => undo.forEach((fn) => fn?.());
    });

    // Images and fonts settle the layout after boot; re-measure every pin once
    // they have.
    window.addEventListener('load', () => ScrollTrigger.refresh());
    document.fonts?.ready.then(() => ScrollTrigger.refresh());
}
