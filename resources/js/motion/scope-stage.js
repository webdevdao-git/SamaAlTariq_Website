import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

/*
 * Home "We Do Everything" — one held screen, scrubbed.
 *
 *   Intro   the first photograph opens out of a frame under the heading to
 *           fill the screen; the heading leaves and the title, card, preview
 *           and progress arrive.
 *   Then    for each next service, the corner preview grows into the new
 *           background (the old one pushes back a little), the preview hands
 *           over to the one after, the title rolls up to the new one and the
 *           card changes over.
 *
 * The backgrounds animate their box rather than a clip, so the photograph
 * recrops as it grows — from exactly the preview's crop to the full screen.
 */
export function initScopeStage() {
    const root = document.querySelector('[data-sx]');
    if (!root) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    gsap.registerPlugin(ScrollTrigger);

    const stage = root.querySelector('.sx__stage');
    const bgs = gsap.utils.toArray(root.querySelectorAll('[data-sx-bg]'));
    const imgs = bgs.map((bg) => bg.querySelector('img'));
    const titles = gsap.utils.toArray(root.querySelectorAll('[data-sx-title] span'));
    const cards = gsap.utils.toArray(root.querySelectorAll('[data-sx-card]'));
    const bars = gsap.utils.toArray(root.querySelectorAll('[data-sx-bar]'));
    const nextBox = root.querySelector('[data-sx-next-box]');
    const nexts = gsap.utils.toArray(root.querySelectorAll('[data-sx-next]'));
    const names = gsap.utils.toArray(root.querySelectorAll('[data-sx-next-name]'));
    const count = root.querySelector('[data-sx-count]');
    const intro = root.querySelector('[data-sx-intro]');
    const ui = root.querySelector('[data-sx-ui]');
    const shade = root.querySelector('[data-sx-shade]');
    const n = bgs.length;

    root.classList.add('is-live');

    // Where things start, measured against the stage.
    const W = () => stage.clientWidth;
    const H = () => stage.clientHeight;
    const box = () => {
        const s = stage.getBoundingClientRect();
        const r = nextBox.getBoundingClientRect();
        return { left: r.left - s.left, top: r.top - s.top, width: r.width, height: r.height };
    };
    const frame = () => {
        const w = W() < 900 ? W() * 0.78 : W() * 0.36;
        const h = H() * (W() < 900 ? 0.42 : 0.5);
        return { left: (W() - w) / 2, top: H() * 0.4, width: w, height: h };
    };

    const full = { left: 0, top: 0, width: () => W(), height: () => H(), borderRadius: 0 };

    gsap.set(bgs, { inset: 'auto' });
    gsap.set(bgs.slice(1), { autoAlpha: 0 });
    gsap.set(titles.slice(1), { yPercent: 110 });
    gsap.set(cards.slice(1), { autoAlpha: 0, y: 30 });
    gsap.set(bars, { scaleX: 0 });

    const tl = gsap.timeline({ defaults: { ease: 'none' } });

    // Intro: the frame opens to the screen.
    tl.fromTo(bgs[0], {
        left: () => frame().left, top: () => frame().top,
        width: () => frame().width, height: () => frame().height,
        borderRadius: 18,
    }, { ...full, duration: 1, ease: 'power2.inOut' }, 0)
        .fromTo(imgs[0], { scale: 1.3 }, { scale: 1, duration: 1, ease: 'power2.out' }, 0)
        .to(intro, { autoAlpha: 0, y: -40, duration: 0.45 }, 0)
        .fromTo(shade, { autoAlpha: 0 }, { autoAlpha: 1, duration: 0.4 }, 0.6)
        .fromTo(ui, { autoAlpha: 0 }, { autoAlpha: 1, duration: 0.3 }, 0.7)
        .fromTo(titles[0], { yPercent: 110 }, { yPercent: 0, duration: 0.35, ease: 'power3.out' }, 0.75)
        .fromTo(cards[0], { autoAlpha: 0, y: 40 }, { autoAlpha: 1, y: 0, duration: 0.35, ease: 'power3.out' }, 0.8)
        .to(bars[0], { scaleX: 1, duration: 0.4 }, 0.7);

    // The moment each service becomes current, for the counter.
    const marks = [0];
    let t = 1.6;

    for (let i = 1; i < n; i++) {
        const bg = bgs[i];
        tl.fromTo(bg, {
            autoAlpha: 1,
            left: () => box().left, top: () => box().top,
            width: () => box().width, height: () => box().height,
            borderRadius: 12,
        }, { ...full, duration: 1, ease: 'power3.inOut', immediateRender: false }, t)
            .fromTo(imgs[i], { scale: 1.25 }, { scale: 1, duration: 1.1, ease: 'power2.out', immediateRender: false }, t)
            .to(imgs[i - 1], { scale: 1.12, duration: 1 }, t)
            // The preview has become the background: hand it on.
            .set(nexts[i - 1], { autoAlpha: 0 }, t)
            .to(names[i - 1], { autoAlpha: 0, y: -10, duration: 0.2 }, t)
            .to(titles[i - 1], { yPercent: -110, duration: 0.4, ease: 'power2.in' }, t + 0.25)
            .to(cards[i - 1], { autoAlpha: 0, y: -24, duration: 0.3, ease: 'power2.in' }, t + 0.2)
            .fromTo(titles[i], { yPercent: 110 }, { yPercent: 0, duration: 0.45, ease: 'power3.out', immediateRender: false }, t + 0.55)
            .fromTo(cards[i], { autoAlpha: 0, y: 30 }, { autoAlpha: 1, y: 0, duration: 0.4, ease: 'power3.out', immediateRender: false }, t + 0.6)
            .to(bars[i], { scaleX: 1, duration: 1 }, t);

        if (nexts[i]) {
            tl.fromTo(nexts[i], { autoAlpha: 0, scale: 0.85 }, { autoAlpha: 1, scale: 1, duration: 0.35, ease: 'power2.out', immediateRender: false }, t + 0.75)
                .fromTo(names[i], { autoAlpha: 0, y: 10 }, { autoAlpha: 1, y: 0, duration: 0.3, immediateRender: false }, t + 0.8);
        } else {
            // Nothing after the last: the preview goes.
            tl.to(nextBox.parentElement, { autoAlpha: 0, duration: 0.3 }, t + 0.1);
        }

        marks.push(t + 0.5);
        t += 1.6;
    }
    tl.to({}, { duration: 0.4 });

    let shown = 0;
    ScrollTrigger.create({
        trigger: root,
        start: 'top top',
        end: () => `+=${H() * (0.9 + (n - 1) * 1.1)}`,
        pin: stage,
        scrub: 0.6,
        animation: tl,
        invalidateOnRefresh: true,
        onUpdate: () => {
            const now = tl.time();
            const index = Math.max(0, marks.filter((m) => now >= m).length - 1);
            if (index !== shown && count) {
                shown = index;
                count.textContent = String(index + 1).padStart(2, '0');
            }
        },
    });

    // Pins further down were measured before this one existed.
    ScrollTrigger.sort();
    ScrollTrigger.refresh();
}
