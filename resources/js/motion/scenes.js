import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { prefersReducedMotion } from './scroll-engine';
import { LOGO } from './logo-paths';

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
 * The landing hero: zoom through the mark.
 *
 * The page opens on the night ground with the company's mark — the building
 * of six bands — standing large in the middle of it, and the mark is a
 * window: the hero photograph is seen only through its bands. Scrolling pins
 * the hero and flies the camera into the peak of the top band, the one place
 * in the mark solid enough to fill the screen, until the mark is larger than
 * the screen and the room is all that is left. Then the headline and the
 * intro come up over it.
 *
 * Drawn as an SVG mask — a night-coloured sheet with the mark cut out of it,
 * from the vector trace in logo-paths.js so it stays sharp at any size — laid
 * over the photograph and under the header, so MENU and the lock-up hold
 * their place throughout.
 */
function heroZoom() {
    const hero = document.querySelector('#top[data-hero-zoom]');
    const media = hero?.querySelector('[data-hero-media]');
    const content = hero?.querySelector('[data-hero] > .mt-auto');
    if (!hero || !media || !content) return;

    const img = media.querySelector('img');
    const NS = 'http://www.w3.org/2000/svg';
    const id = 'hero-hole';

    const svg = document.createElementNS(NS, 'svg');
    svg.setAttribute('class', 'hero-mask');
    svg.setAttribute('aria-hidden', 'true');
    svg.innerHTML = `
        <defs>
            <mask id="${id}" maskUnits="userSpaceOnUse">
                <rect data-sheet fill="#fff" x="0" y="0"/>
                <g data-zoom><g data-fit>${LOGO.paths.map((d) => `<path fill="#000" d="${d}"/>`).join('')}</g></g>
            </mask>
        </defs>
        <rect data-ground fill="#3fa7b3" x="0" y="0" mask="url(#${id})"/>`;

    const cue = document.createElement('div');
    cue.className = 'hero-mask__cue';
    cue.setAttribute('aria-hidden', 'true');

    hero.append(svg, cue);

    const sheet = svg.querySelector('[data-sheet]');
    const ground = svg.querySelector('[data-ground]');
    const zoomGroup = svg.querySelector('[data-zoom]');
    const fit = svg.querySelector('[data-fit]');

    const origin = { x: 0, y: 0 };
    const zoom = { s: 1, o: 1, lift: 0 };

    function draw() {
        zoomGroup.setAttribute('transform',
            `translate(${origin.x} ${origin.y - zoom.lift}) scale(${zoom.s}) translate(${-origin.x} ${-origin.y})`);
        zoomGroup.style.opacity = zoom.o;
    }

    const layout = () => {
        const w = hero.clientWidth;
        const h = hero.clientHeight;
        svg.setAttribute('viewBox', `0 0 ${w} ${h}`);
        for (const r of [sheet, ground]) {
            r.setAttribute('width', w);
            r.setAttribute('height', h);
        }

        // As large as the screen allows while leaving room round it: half the
        // width on a wide screen, held to three-fifths of the height on a
        // short one, and most of the width on a phone.
        const aspect = LOGO.width / LOGO.height;
        const lw = Math.min(w * (w < 768 ? 0.78 : 0.46), h * 0.6 * aspect);
        const k = lw / LOGO.width;
        const lh = LOGO.height * k;
        const tx = (w - lw) / 2;
        const ty = (h - lh) / 2;
        fit.setAttribute('transform', `translate(${tx.toFixed(2)} ${ty.toFixed(2)}) scale(${k.toFixed(5)})`);

        origin.x = tx + LOGO.origin.x * k;
        origin.y = ty + LOGO.origin.y * k;
        draw();
    };

    layout();
    ScrollTrigger.addEventListener('refreshInit', layout);
    window.addEventListener('resize', layout);

    // Held back until the zoom has opened the room.
    gsap.set(content, { autoAlpha: 0, y: 70 });

    // The entrance: the mark rises into place band by band as the curtain
    // lifts, and the room behind it drifts, so the bands read as windows onto
    // a real place rather than a pattern.
    const paths = Array.from(fit.querySelectorAll('path'));
    const enter = () => {
        gsap.fromTo(paths,
            { y: 60, opacity: 0 },
            { y: 0, opacity: 1, duration: 1.4, ease: 'expo.out', stagger: { each: 0.08, from: 'end' }, delay: 0.3 });
        if (img) gsap.fromTo(img, { scale: 1.45 }, { scale: 1.25, duration: 3, ease: 'expo.out', delay: 0.3 });
    };
    if (document.querySelector('.intro-curtain')) {
        window.addEventListener('curtain:lift', enter, { once: true });
    } else {
        enter();
    }

    /*
     * The copy is not scrubbed. Tied to the scroll it faded in across the
     * last tenth of the pin and finished only at its very end, so a visitor
     * who stopped scrolling a little short read the headline at half opacity
     * over the photograph — which looks like blurred type. It is shown in
     * full, on its own clock, once the room is mostly open, and hidden again
     * only if they scroll back into the mark.
     */
    let shown = false;
    const reveal = (on) => {
        if (on === shown) return;
        shown = on;
        gsap.to(content, on
            ? { autoAlpha: 1, y: 0, duration: 0.7, ease: 'power3.out', overwrite: true }
            : { autoAlpha: 0, y: 70, duration: 0.3, ease: 'power2.in', overwrite: true });
    };

    gsap.timeline({
        scrollTrigger: {
            trigger: hero,
            start: 'top top',
            end: () => `+=${window.innerHeight * 0.9}`,
            pin: true,
            scrub: 1,
            invalidateOnRefresh: true,
            onUpdate: (self) => reveal(self.progress > 0.7),
            onLeave: () => reveal(true),
        },
    })
        .fromTo(zoom, { s: 1 }, { s: 70, ease: 'power3.in', duration: 1, onUpdate: draw, immediateRender: false }, 0)
        .to(cue, { autoAlpha: 0, duration: 0.15 }, 0)
        .fromTo(img, { scale: 1.25 }, { scale: 1, ease: 'none', duration: 1, immediateRender: false }, 0)
        .to(svg, { autoAlpha: 0, duration: 0.08 }, 0.9);

    // The shader can lay its canvas over the photograph straight away: it
    // sits under the mask, so it bends the room inside the bands too.
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

/**
 * Our Expertise: from drawing to building.
 *
 * Each service's photograph is first shown as a sketch — the same picture
 * traced to pencil line-work on the page's own paper — and a scan line
 * crosses it left to right as the scroll moves, turning drawing into
 * photograph wherever it has passed. The next service's drawing then lays
 * itself over the finished picture and is scanned in turn. Beside the frame
 * the service's number, name and description change with it, and a rail
 * marks how far through the three the visitor is.
 *
 * Built from the panels' own markup: each carries its photograph and its
 * blueprint as data attributes, and its title and description are read from
 * it. The panels stay in the page, out of sight, as what a screen reader
 * reads and what a narrow screen shows instead.
 */
function services() {
    const box = document.querySelector('#services .service-slides');
    if (!box) return;

    const slides = Array.from(box.querySelectorAll('.service-slide'));
    const items = slides.map((slide) => ({
        photo: slide.dataset.photo,
        blueprint: slide.dataset.blueprint,
        // The panel's superscript number is left out — the scene sets the
        // number large of its own.
        title: Array.from(slide.querySelectorAll('h3 span')).map((n) => {
            const copy = n.cloneNode(true);
            copy.querySelectorAll('sup').forEach((sup) => sup.remove());
            return copy.textContent.replace(/\s+/g, ' ').trim();
        }).filter(Boolean),
        titleText: slide.querySelector('h3')?.textContent.replace(/\s+/g, ' ').trim() ?? '',
        body: slide.querySelector('p')?.textContent.trim() ?? '',
    }));
    const n = items.length;
    if (n < 2 || items.some((item) => !item.photo || !item.blueprint)) return;

    const pad = (i) => String(i + 1).padStart(2, '0');
    const scene = document.createElement('div');
    scene.className = 'bp-scene';
    scene.setAttribute('aria-hidden', 'true');
    scene.innerHTML = `
        <div class="bp-scene__inner shell">
            <div class="bp-copy">
                <div class="bp-num">${items.map((_, i) => `<span>${pad(i)}</span>`).join('')}</div>
                <div class="bp-titles">${items.map((item) => `<h3 class="service-title">${(item.title.length ? item.title : [item.titleText]).map((line) => `<span>${line}</span>`).join('')}</h3>`).join('')}</div>
                <div class="bp-bodies">${items.map((item) => `<p>${item.body}</p>`).join('')}</div>
                <ol class="bp-rail">${items.map((item, i) => `<li><span>${pad(i)}</span><i></i></li>`).join('')}</ol>
            </div>
            <div class="bp-frame">
                ${items.map((item) => `
                    <div class="bp-layer">
                        <img class="bp-layer__draw" src="${item.blueprint}" alt="" decoding="async">
                        <img class="bp-layer__photo" src="${item.photo}" alt="" decoding="async">
                    </div>`).join('')}
                <span class="bp-scan"></span>
                <span class="bp-corner is-tl"></span><span class="bp-corner is-tr"></span>
                <span class="bp-corner is-bl"></span><span class="bp-corner is-br"></span>
            </div>
        </div>`;

    box.before(scene);
    box.classList.add('is-drawn');

    // Above <main>, which the footer reveal lifts onto layer 1: once the scene
    // is reparented to <body> for its pin it would otherwise sit underneath.
    scene.style.zIndex = '20';

    const layers = Array.from(scene.querySelectorAll('.bp-layer'));
    const photos = layers.map((layer) => layer.querySelector('.bp-layer__photo'));
    const scan = scene.querySelector('.bp-scan');
    const nums = Array.from(scene.querySelectorAll('.bp-num span'));
    const titles = Array.from(scene.querySelectorAll('.bp-titles h3'));
    const bodies = Array.from(scene.querySelectorAll('.bp-bodies p'));
    const ticks = Array.from(scene.querySelectorAll('.bp-rail li'));
    const fills = ticks.map((tick) => tick.querySelector('i'));

    let current = -1;
    const show = (i) => {
        if (i === current) return;
        const from = current;
        current = i;
        [nums, titles, bodies].forEach((list) => list.forEach((node, k) => {
            node.classList.toggle('is-current', k === i);
            node.classList.toggle('is-past', k < i);
        }));
        ticks.forEach((tick, k) => tick.classList.toggle('is-current', k === i));
        if (from >= 0) {
            gsap.fromTo(titles[i].children, { yPercent: 100 }, { yPercent: 0, duration: 0.9, ease: 'expo.out', stagger: 0.07 });
        }
    };

    const play = (f) => {
        layers.forEach((layer, i) => {
            // Each drawing lays itself over the finished picture before it.
            layer.style.opacity = i === 0 ? '1' : Math.min(1, Math.max(0, (f - i + 0.12) / 0.12)).toFixed(3);
            const local = Math.min(1, Math.max(0, f - i));
            const s = Math.min(1, Math.max(0, (local - 0.12) / 0.7));
            photos[i].style.clipPath = `inset(0 ${((1 - s) * 100).toFixed(2)}% 0 0)`;
            fills[i].style.transform = `scaleX(${s.toFixed(3)})`;
        });

        const i = Math.max(0, Math.min(n - 1, Math.floor(f)));
        const local = Math.min(1, Math.max(0, f - i));
        const s = Math.min(1, Math.max(0, (local - 0.12) / 0.7));
        scan.style.left = `${(s * 100).toFixed(2)}%`;
        scan.style.opacity = s > 0.005 && s < 0.995 ? '1' : '0';
        show(i);
    };

    ScrollTrigger.create({
        trigger: scene,
        start: 'top top',
        end: () => `+=${window.innerHeight * n * 0.9}`,
        pin: true,
        // Lifted out to <body> while pinned: the services section is a layer
        // in the stack below and carries a transform for it, which would make
        // it the containing block of anything fixed inside.
        pinReparent: true,
        scrub: 0.6,
        invalidateOnRefresh: true,
        onUpdate: (self) => play(self.progress * n * 0.999),
        onRefresh: (self) => play(self.progress * n * 0.999),
    });
    play(0);

    return () => {
        scene.remove();
        box.classList.remove('is-drawn');
    };
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
            end: () => `+=${window.innerHeight * 0.6}`,
            pin: true,
            scrub: 1,
        },
    })
        .fromTo(word, { scale: 2, xPercent: 4, transformOrigin: '0% 100%' }, { scale: 1, xPercent: 0, ease: 'power3.inOut' }, 0)
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
 * Process as a timeline that runs sideways.
 *
 * The section holds, its heading and button stay at the top, and beneath them
 * the four stages stand as cards on one line that crosses the screen. The
 * scroll walks the line from left to right: it draws itself as it goes, the
 * card reaching the middle of the screen grows to full size and lights its
 * marker on the line, and the photographs drift inside their frames against
 * the travel. Unlike the services above it — one frame that changes in place
 * — this is a path the visitor moves along.
 *
 * Built from the section's own steps and pictures; the original layout stays
 * in the page, out of sight, for a screen reader and for narrow screens.
 */
function processTrack() {
    const root = document.querySelector('#process[data-process]');
    const steps = Array.from(root?.querySelectorAll('[data-process-step]') ?? []);
    const figures = Array.from(root?.querySelectorAll('[data-process-image]') ?? []);
    const heading = root?.querySelector('h2');
    const cta = root?.querySelector('.pill');
    const n = steps.length;
    if (!root || n < 2 || figures.length !== n || !heading) return;

    const items = steps.map((step, i) => ({
        number: step.querySelector('p')?.textContent.trim() ?? String(i + 1).padStart(2, '0'),
        title: step.querySelector('h3')?.textContent.trim() ?? '',
        body: step.querySelectorAll('p')[1]?.textContent.trim() ?? '',
        src: figures[i].querySelector('img')?.currentSrc || figures[i].querySelector('img')?.src,
    }));

    const scene = document.createElement('div');
    scene.className = 'pt-scene';
    scene.innerHTML = `
        <div class="pt-head shell">
            <h2 class="editorial-heading text-fluid-section uppercase text-ink" aria-hidden="true">${heading.innerHTML}</h2>
        </div>
        <div class="pt-viewport" aria-hidden="true">
            <div class="pt-track">
                <span class="pt-line"><span class="pt-line__fill"></span></span>
                ${items.map((item) => `
                    <article class="pt-card">
                        <div class="pt-card__media"><img src="${item.src}" alt="" decoding="async"></div>
                        <span class="pt-card__dot"></span>
                        <div class="pt-card__text">
                            <p class="pt-card__num">${item.number}</p>
                            <h3 class="pt-card__title">${item.title}</h3>
                            <p class="pt-card__body">${item.body}</p>
                        </div>
                    </article>`).join('')}
            </div>
        </div>`;

    // The section's own button moves into the scene's head, so it is still
    // the one link and keeps its place in the tab order.
    const ctaRow = cta?.parentElement;
    if (cta) scene.querySelector('.pt-head').append(cta);

    root.classList.add('is-track');
    root.prepend(scene);

    const track = scene.querySelector('.pt-track');
    const fill = scene.querySelector('.pt-line__fill');
    const cards = Array.from(scene.querySelectorAll('.pt-card'));
    const imgs = cards.map((card) => card.querySelector('img'));

    const distance = () => Math.max(0, track.scrollWidth - window.innerWidth);

    const play = (p) => {
        const x = -distance() * p;
        track.style.transform = `translate3d(${x.toFixed(1)}px, 0, 0)`;
        fill.style.transform = `scaleX(${p.toFixed(4)})`;

        const mid = window.innerWidth / 2;
        cards.forEach((card, i) => {
            const r = card.getBoundingClientRect();
            const d = (r.left + r.width / 2 - mid) / window.innerWidth;
            const near = Math.max(0, 1 - Math.abs(d) * 2.2);
            card.style.setProperty('--near', near.toFixed(3));
            card.classList.toggle('is-current', near > 0.6);
            imgs[i].style.transform = `translate3d(${(d * -60).toFixed(1)}px, 0, 0) scale(1.15)`;
        });
    };

    ScrollTrigger.create({
        trigger: root,
        start: 'top top',
        end: () => `+=${distance() * 1.1}`,
        pin: true,
        scrub: 0.8,
        invalidateOnRefresh: true,
        onUpdate: (self) => play(self.progress),
        onRefresh: (self) => play(self.progress),
    });
    play(0);

    return () => {
        if (cta && ctaRow) ctaRow.append(cta);
        scene.remove();
        root.classList.remove('is-track');
    };
}

/**
 * We Do Everything: each row turns down into place like a leaf of a
 * split-flap board — hinged on its top edge, from edge-on to flat — one
 * after another as the list crosses the screen. The sectors below ink in
 * word by word, from outline to solid.
 */
function scopeScene() {
    const section = document.querySelector('#scope');
    const list = section?.querySelector('.scope__list');
    if (!section || !list) return;

    list.style.perspective = '1200px';
    const rows = Array.from(list.querySelectorAll('.scope__row'));
    rows.forEach((row) => {
        gsap.fromTo(row,
            { rotateX: -88, opacity: 0, transformOrigin: '50% 0%' },
            {
                rotateX: 0,
                opacity: 1,
                ease: 'power3.out',
                scrollTrigger: { trigger: row, start: 'top 92%', end: 'top 62%', scrub: 0.6 },
            });
    });
}

/**
 * Sectors We Serve: the list runs past a fixed frame. Whichever sector is at
 * the middle of the screen lights, and the frame turns about its vertical
 * axis to that sector's picture — the old one swinging away as the new one
 * swings in.
 */
function sectorsScene() {
    const section = document.querySelector('#sectors');
    const items = Array.from(section?.querySelectorAll('[data-sector]') ?? []);
    const pics = Array.from(section?.querySelectorAll('[data-sector-pic]') ?? []);
    const n = items.length;
    if (!section || n === 0) return;

    let current = 0;
    const show = (i) => {
        if (i === current) return;
        const forward = i > current;
        const from = pics[current];
        const to = pics[i];
        items.forEach((item, k) => item.classList.toggle('is-current', k === i));
        if (from) {
            gsap.to(from, { rotateY: forward ? -70 : 70, opacity: 0, duration: 0.6, ease: 'power3.in', overwrite: true,
                onComplete: () => from.classList.remove('is-current') });
        }
        if (to) {
            to.classList.add('is-current');
            gsap.fromTo(to, { rotateY: forward ? 70 : -70, opacity: 0 },
                { rotateY: 0, opacity: 1, duration: 0.8, ease: 'power3.out', delay: 0.12, overwrite: true });
        }
        current = i;
    };

    // One trigger for the whole section: the scroll through the pin is cut
    // into six equal stretches and the stretch decides the sector. Earlier
    // each row had its own trigger, and a quick scroll fired them all in a
    // burst — whichever came last won, which was always the last row.
    if (window.matchMedia('(min-width: 1024px)').matches) {
        ScrollTrigger.create({
            trigger: section,
            start: 'top top',
            end: () => `+=${window.innerHeight * (n - 1) * 0.45}`,
            pin: true,
            scrub: true,
            snap: { snapTo: 1 / (n - 1), duration: { min: 0.2, max: 0.5 }, ease: 'power2.inOut' },
            invalidateOnRefresh: true,
            onUpdate: (self) => show(Math.min(n - 1, Math.round(self.progress * (n - 1)))),
        });
    }

    gsap.fromTo(items, { xPercent: 8, opacity: 0 }, {
        xPercent: 0,
        opacity: 1,
        duration: 1,
        ease: 'power3.out',
        stagger: 0.07,
        scrollTrigger: { trigger: section, start: 'top 70%' },
    });
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
        }
        // These two run wherever their sections are — the services page
        // carries both as well as the landing page.
        scopeScene();
        sectorsScene();
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
            undo.push(services());
            undo.push(processTrack());
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
