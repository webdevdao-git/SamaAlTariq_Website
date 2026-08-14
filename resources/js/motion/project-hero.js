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

    /*
     * Reduced motion stops the hero cycling on its own, but it does not stop
     * the strip working: choosing a photograph is something a visitor did, not
     * something the page decided to do at them. The change is a cut rather than
     * a cross-fade.
     */
    const still = prefersReducedMotion();
    const fade = still ? 0 : FADE;

    let current = 0;
    let timer = null;

    const show = (next) => {
        const outgoing = slides[current];
        const incoming = slides[next];

        // The outgoing picture is already at scale 1; letting it run on to 1.2
        // while it fades is what keeps the movement continuous across the cut.
        outgoing.style.transition = `opacity ${fade}ms ease, transform ${fade + HOLD}ms ease-out`;
        outgoing.style.opacity = '0';
        outgoing.style.transform = still ? 'scale(1)' : 'scale(1.2)';

        // Start the incoming one small with no transition, then let it run in
        // on the next frame — set both in one go and there is nothing to
        // animate from.
        incoming.style.transition = 'none';
        incoming.style.transform = still ? 'scale(1)' : 'scale(0.8)';
        incoming.style.opacity = '0';
        requestAnimationFrame(() => {
            incoming.style.transition = `opacity ${fade}ms ease, transform ${fade + HOLD}ms ease-out`;
            incoming.style.opacity = '1';
            incoming.style.transform = 'scale(1)';
        });

        current = next;
        mark();
    };

    const advance = () => show((current + 1) % slides.length);

    /*
     * The strip beside the title. The reference's is inert — cursor auto, no
     * hover, and a click changes nothing — but a row of photographs next to a
     * slideshow reads as a control whether or not it is wired up, so these
     * select. Choosing one restarts the clock rather than leaving the next
     * change a moment away.
     */
    const thumbs = Array.from(hero.querySelectorAll('[data-hero-thumb]'));

    const mark = () => {
        thumbs.forEach((thumb, i) =>
            i === current
                ? thumb.setAttribute('aria-current', 'true')
                : thumb.removeAttribute('aria-current'));
    };

    // Only the clock is withheld under reduced motion; the strip still selects.
    const start = () => {
        if (timer || still) return;
        timer = setInterval(advance, HOLD + FADE);
    };
    const stop = () => {
        clearInterval(timer);
        timer = null;
    };

    thumbs.forEach((thumb, i) =>
        thumb.addEventListener('click', () => {
            if (i === current) return;
            stop();
            show(i);
            start();
        }));

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
