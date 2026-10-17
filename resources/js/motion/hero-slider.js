import { gsap } from 'gsap';
import { prefersReducedMotion } from './scroll-engine';

/**
 * The landing hero's slides. Each picture comes up over the last in a slow
 * cross-dissolve, easing in from a slight push, and brings its own message:
 * the old lines drop out of their masks, the new ones rise into them. The
 * project the picture is from, a row of dots and a counter sit under it.
 *
 * Waits for the hero's opening zoom (scenes.js fires hero:opened) so the
 * first picture and message are seen whole before the first change, and runs
 * only while the hero is on screen.
 */
const HOLD = 5600;

export function initHeroSlider() {
    const media = document.querySelector('#top [data-hero-media]');
    const first = media?.querySelector('img:not([data-hero-slide])');
    const slides = Array.from(media?.querySelectorAll('[data-hero-slide]') ?? []);
    const msgs = Array.from(document.querySelectorAll('[data-hero-msg]'));
    if (!media || !first || slides.length === 0) return;

    const pictures = [first, ...slides];
    const n = pictures.length;
    const caption = document.querySelector('[data-hero-caption]');
    const index = document.querySelector('[data-hero-index]');
    const dots = Array.from(document.querySelectorAll('.hero-dots span'));
    const names = JSON.parse(document.querySelector('[data-hero-titles]')?.textContent || '[]');
    const reduced = prefersReducedMotion();

    gsap.set(slides, { opacity: 0 });
    let current = 0;
    let timer = 0;

    const swapMessage = (from, to) => {
        const out = msgs[from]?.querySelectorAll('.hero-msg__line > span');
        const inn = msgs[to]?.querySelectorAll('.hero-msg__line > span');
        if (!inn) return;
        if (reduced) {
            msgs.forEach((m, k) => m.classList.toggle('is-current', k === to));
            return;
        }
        gsap.to(out, { yPercent: -110, duration: 0.6, ease: 'power3.in', stagger: 0.06,
            onComplete: () => msgs[from].classList.remove('is-current') });
        msgs[to].classList.add('is-current');
        gsap.fromTo(inn, { yPercent: 110 }, { yPercent: 0, duration: 1.1, ease: 'expo.out', stagger: 0.09, delay: 0.45 });
    };

    const show = (next) => {
        const from = current;
        current = next;
        const a = pictures[from];
        const b = pictures[next];

        gsap.set(b, { zIndex: 2 });
        gsap.set(a, { zIndex: 1 });
        if (reduced) gsap.set(b, { opacity: 1 });
        else gsap.fromTo(b, { opacity: 0, scale: 1.06 }, { opacity: 1, scale: 1, duration: 1.8, ease: 'power2.out' });
        // Once covered, the old picture can go; the first never goes, it is
        // the floor the others sit on.
        if (a !== first) gsap.set(a, { opacity: 0, delay: 1.8 });

        swapMessage(from, next);
        dots.forEach((d, k) => d.classList.toggle('is-current', k === next));
        if (index) index.textContent = String(next + 1).padStart(2, '0');
        if (caption && names[next]) {
            gsap.to(caption, { opacity: 0, duration: 0.3, onComplete: () => {
                caption.textContent = names[next];
                gsap.to(caption, { opacity: 1, duration: 0.6 });
            } });
        }
    };

    const start = () => {
        clearInterval(timer);
        timer = setInterval(() => show((current + 1) % n), HOLD);
    };

    const io = new IntersectionObserver(([entry]) => {
        if (entry.isIntersecting) start();
        else clearInterval(timer);
    });
    let begun = false;
    const begin = () => {
        if (begun) return;
        begun = true;
        io.observe(media);
    };
    if (window.__heroOpened) setTimeout(begin, 1500);
    else window.addEventListener('hero:opened', () => setTimeout(begin, 1500), { once: true });
    setTimeout(begin, 6000);

}
