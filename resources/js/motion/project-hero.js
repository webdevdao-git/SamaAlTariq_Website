import { prefersReducedMotion } from './scroll-engine';

/**
 * The project hero cycles its photographs, taken from the reference's own
 * hero-slider behaviour: the outgoing picture keeps growing to 1.2 as it fades,
 * the incoming one arrives from 0.8, and the two cross in the middle. That
 * overlap is the whole effect — cut it and the pictures blink rather than
 * dissolve.
 *
 * Measured off the reference at 1440: about four seconds on a slide and a
 * second and a bit of cross-fade. Nothing about the hero's layout changes;
 * this only swaps which photograph is under the title.
 *
 * Everything is CSS transitions rather than a rAF loop. The browser composites
 * opacity and transform off the main thread, so a hero that is fading while the
 * page scrolls costs nothing in the scroll handler this site already runs.
 */
const HOLD = 4000;
const FADE = 1200;

export function initProjectHero() {
    const hero = document.querySelector('[data-hero-slides]');
    if (!hero) return;

    const slides = Array.from(hero.querySelectorAll('[data-hero-slide]'));
    if (slides.length < 2) return;

    // One picture, no cycling: the hero is a photograph with a title on it and
    // that is all it has to be.
    if (prefersReducedMotion()) return;

    let current = 0;
    let timer = null;

    const show = (next) => {
        const outgoing = slides[current];
        const incoming = slides[next];

        // The outgoing picture is already at scale 1; letting it run on to 1.2
        // while it fades is what keeps the movement continuous across the cut.
        outgoing.style.transition = `opacity ${FADE}ms ease, transform ${FADE + HOLD}ms ease-out`;
        outgoing.style.opacity = '0';
        outgoing.style.transform = 'scale(1.2)';

        // Start the incoming one small with no transition, then let it run in
        // on the next frame — set both in one go and there is nothing to
        // animate from.
        incoming.style.transition = 'none';
        incoming.style.transform = 'scale(0.8)';
        incoming.style.opacity = '0';
        requestAnimationFrame(() => {
            incoming.style.transition = `opacity ${FADE}ms ease, transform ${FADE + HOLD}ms ease-out`;
            incoming.style.opacity = '1';
            incoming.style.transform = 'scale(1)';
        });

        current = next;
    };

    const advance = () => show((current + 1) % slides.length);

    const start = () => {
        if (timer) return;
        timer = setInterval(advance, HOLD + FADE);
    };
    const stop = () => {
        clearInterval(timer);
        timer = null;
    };

    // A hero that has scrolled away has nothing to show, and a background tab
    // has no one watching. Both stop the clock rather than the animation, so
    // the next slide gets its full hold when the hero comes back.
    if ('IntersectionObserver' in window) {
        new IntersectionObserver(
            ([entry]) => (entry.isIntersecting ? start() : stop()),
            { threshold: 0.01 },
        ).observe(hero);
    } else {
        start();
    }

    document.addEventListener('visibilitychange', () => {
        document.hidden ? stop() : start();
    });
}
