import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { prefersReducedMotion } from './scroll-engine';

gsap.registerPlugin(ScrollTrigger);

/**
 * The projects pyramid stack.
 *
 * Every card has a slot relative to the one in front: 0 is the front, ±1
 * the pair either side, ±2 the pair behind those, and anything further is
 * folded away behind the stack. Advancing moves every card one slot, so the
 * next card slides in from the side, grows into the front, and the old front
 * steps back down to its side — the depth transition the reference loops on.
 *
 * Plays on its own while it is on screen, holds while a card is under the
 * pointer, and steps
 * by the arrows or by clicking a side card. Clicking the front card follows
 * its link.
 */
const TALL = {
    0: { x: 0, y: 0, scale: 1, z: 50, brightness: 1 },
    1: { x: 0.62, y: 0.06, scale: 0.78, z: 40, brightness: 0.62 },
    2: { x: 1.08, y: 0.12, scale: 0.6, z: 30, brightness: 0.4 },
    3: { x: 1.36, y: 0.16, scale: 0.46, z: 20, brightness: 0.2 },
};
// Landscape cards: half the sideways step, a little more drop, so the wide
// pictures still stack into a pyramid inside the screen.
const WIDE = {
    0: { x: 0, y: 0, scale: 1, z: 50, brightness: 1 },
    1: { x: 0.36, y: 0.1, scale: 0.8, z: 40, brightness: 0.6 },
    2: { x: 0.64, y: 0.2, scale: 0.62, z: 30, brightness: 0.38 },
    3: { x: 0.84, y: 0.28, scale: 0.48, z: 20, brightness: 0.2 },
};
let SLOTS = TALL;

export function initProjectStack() {
    const root = document.querySelector('[data-pstack]');
    const cards = Array.from(root?.querySelectorAll('.pstack__card') ?? []);
    const n = cards.length;
    if (!root || n < 3) return;

    root.classList.add('is-live');
    SLOTS = cards[0].offsetWidth > cards[0].offsetHeight ? WIDE : TALL;

    const title = root.querySelector('[data-pstack-title]');
    const category = root.querySelector('[data-pstack-category]');
    const location = root.querySelector('[data-pstack-location]');
    const caption = root.querySelector('.pstack__caption');

    let front = 0;
    const reduced = prefersReducedMotion();

    // The shortest signed distance from the front, wrapping round the loop.
    const offset = (i) => {
        let d = (i - front) % n;
        if (d > n / 2) d -= n;
        if (d < -n / 2) d += n;
        return d;
    };

    const place = (animate) => {
        const w = cards[0].offsetWidth;
        cards.forEach((card, i) => {
            const d = offset(i);
            const a = Math.min(3, Math.abs(d));
            const s = SLOTS[a];
            const hidden = Math.abs(d) > 2;
            const vars = {
                x: Math.sign(d) * s.x * w,
                yPercent: s.y * 100,
                scale: s.scale,
                zIndex: s.z,
                opacity: hidden ? 0 : 1,
                filter: `brightness(${s.brightness})`,
                rotationY: -Math.sign(d) * Math.min(a, 2) * 6,
            };
            card.classList.toggle('is-front', d === 0);
            card.setAttribute('aria-hidden', hidden ? 'true' : 'false');
            if (animate && !reduced) {
                gsap.to(card, { ...vars, duration: 1, ease: 'expo.inOut', overwrite: true });
            } else {
                gsap.set(card, vars);
            }
        });

        const card = cards[front];
        const write = () => {
            title.textContent = card.dataset.title;
            category.textContent = card.dataset.category;
            location.textContent = card.dataset.location || '';
        };
        if (animate && !reduced) {
            gsap.timeline()
                .to(caption, { y: -16, opacity: 0, duration: 0.3, ease: 'power2.in', onComplete: write })
                .fromTo(caption, { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.6, ease: 'expo.out' });
        } else {
            write();
        }
    };

    const go = (step) => {
        front = (front + step + n) % n;
        place(true);
    };

    place(false);
    window.addEventListener('resize', () => place(false));

    root.querySelector('[data-pstack-next]')?.addEventListener('click', () => go(1));
    root.querySelector('[data-pstack-prev]')?.addEventListener('click', () => go(-1));

    // A side card is brought to the front; the front one opens its project.
    cards.forEach((card, i) => {
        card.querySelector('a').addEventListener('click', (event) => {
            const d = offset(i);
            if (d === 0) return;
            event.preventDefault();
            go(d);
        });
    });

    if (reduced) return;

    /*
     * Scroll mode (the landing page): the section is pinned and the scroll
     * itself walks the stack, card by card, snapping to each. Positions are
     * interpolated between slots, so a card is seen travelling from the side
     * into the front rather than jumping there.
     */
    if (root.hasAttribute('data-pstack-scroll')) {
        const lerp = (x, y, t) => x + (y - x) * t;
        const slotAt = (a) => {
            const lo = SLOTS[Math.min(3, Math.floor(a))];
            const hi = SLOTS[Math.min(3, Math.ceil(a))];
            const t = a - Math.floor(a);
            return {
                x: lerp(lo.x, hi.x, t),
                y: lerp(lo.y, hi.y, t),
                scale: lerp(lo.scale, hi.scale, t),
                brightness: lerp(lo.brightness, hi.brightness, t),
            };
        };
        let shown = -1;
        const scrub = (f) => {
            const w = cards[0].offsetWidth;
            cards.forEach((card, i) => {
                const d = i - f;
                const a = Math.abs(d);
                const s = slotAt(Math.min(3, a));
                gsap.set(card, {
                    x: Math.sign(d) * s.x * w,
                    yPercent: s.y * 100,
                    scale: s.scale,
                    zIndex: Math.round(50 - a * 10),
                    opacity: a > 2.6 ? Math.max(0, 1 - (a - 2.6) * 2.5) : 1,
                    filter: `brightness(${s.brightness})`,
                    rotationY: -Math.sign(d) * Math.min(a, 2) * 6,
                });
                card.classList.toggle('is-front', a < 0.5);
            });
            const i = Math.round(f);
            if (i !== shown) {
                shown = i;
                front = i;
                const card = cards[i];
                title.textContent = card.dataset.title;
                category.textContent = card.dataset.category;
                location.textContent = card.dataset.location || '';
                gsap.fromTo(caption, { y: 16, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, ease: 'expo.out', overwrite: true });
            }
        };

        const section = root.closest('section') ?? root;
        const trigger = ScrollTrigger.create({
            trigger: section,
            start: 'top top',
            end: () => `+=${window.innerHeight * (n - 1) * 0.55}`,
            pin: true,
            scrub: 0.6,
            snap: { snapTo: 1 / (n - 1), duration: { min: 0.25, max: 0.6 }, ease: 'power2.inOut' },
            invalidateOnRefresh: true,
            onUpdate: (self) => scrub(self.progress * (n - 1)),
            onRefresh: (self) => scrub(self.progress * (n - 1)),
        });
        scrub(0);

        // This pin is made after the scenes below it (the sectors pin among
        // them), which measured their starts without its scroll in the page.
        // Put the triggers back in page order and measure them all again.
        ScrollTrigger.sort();
        ScrollTrigger.refresh();

        // Arrows and side cards scroll to their card instead of stepping.
        const goTo = (i) => {
            const k = Math.max(0, Math.min(n - 1, i));
            window.scrollTo({ top: trigger.start + (trigger.end - trigger.start) * (k / (n - 1)), behavior: 'smooth' });
        };
        root.querySelector('[data-pstack-next]')?.addEventListener('click', (e) => { e.stopImmediatePropagation(); goTo(front + 1); }, true);
        root.querySelector('[data-pstack-prev]')?.addEventListener('click', (e) => { e.stopImmediatePropagation(); goTo(front - 1); }, true);
        cards.forEach((card, i) => card.querySelector('a').addEventListener('click', (event) => {
            if (i === front) return;
            event.preventDefault();
            event.stopImmediatePropagation();
            goTo(i);
        }, true));
        return;
    }

    // The loop: every 3.2s while on screen and not under the pointer.
    let timer = 0;
    let onScreen = false;
    let held = false;
    const run = () => {
        clearInterval(timer);
        if (onScreen && !held) timer = setInterval(() => go(1), 3200);
    };
    // Held only while a card itself is under the pointer — the section is
    // a whole screen wide, and pausing on it read as the stack having stopped.
    for (const card of cards) {
        card.addEventListener('pointerenter', () => { held = true; run(); });
        card.addEventListener('pointerleave', () => { held = false; run(); });
    }
    ScrollTrigger.create({
        trigger: root,
        start: 'top bottom',
        end: 'bottom top',
        onToggle: (self) => { onScreen = self.isActive; run(); },
    });

    // The stack arrives: cards fan out from the front one.
    gsap.from(cards, {
        x: 0,
        scale: 0.4,
        opacity: 0,
        duration: 1.4,
        ease: 'expo.out',
        stagger: { each: 0.06, from: 'center' },
        scrollTrigger: { trigger: root, start: 'top 75%' },
        onComplete: () => place(false),
    });
}
