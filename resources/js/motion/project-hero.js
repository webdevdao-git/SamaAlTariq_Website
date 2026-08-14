import { prefersReducedMotion } from './scroll-engine';

/**
 * The project hero cycles its photographs.
 *
 * Traced off the reference frame by frame rather than guessed at, and it does
 * not do what it looks like it does. BOTH PICTURES MOVE THE SAME WAY: the
 * outgoing one grows from 1 to 1.2 as it fades out, and the incoming one is
 * snapped to 1.2 the instant the change begins and settles back to 1 as it
 * fades in. The eye reads one continuous push rather than two pictures passing
 * in opposite directions, which is what an incoming picture growing from 0.8
 * gives you — the first version of this, and wrong.
 *
 * The trace: opacity crosses 0.01, 0.09, 0.33, 0.54, 0.75, 0.94, 1 over 1200ms,
 * which is a symmetric ease-in-out, and the scale lands with it and then holds
 * still. A change every 4490ms.
 *
 * Everything is CSS transitions rather than a rAF loop. The browser composites
 * opacity and transform off the main thread, so a hero that is fading while the
 * page scrolls costs nothing in the scroll handler this site already runs.
 */
const PERIOD = 4490;
const FADE = 1200;
const EASE = 'cubic-bezier(0.45, 0, 0.55, 1)';
const ZOOM = 1.2;

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
        const move = `opacity ${fade}ms ${EASE}, transform ${fade}ms ${EASE}`;

        // Out: fades while it grows away from the reader.
        outgoing.style.transition = move;
        outgoing.style.opacity = '0';
        outgoing.style.transform = still ? 'scale(1)' : `scale(${ZOOM})`;

        // In: placed at the outgoing one's end scale with no transition, then
        // released on the next frame so it settles back as it arrives. Set both
        // in one go and there is nothing to animate from.
        incoming.style.transition = 'none';
        incoming.style.transform = still ? 'scale(1)' : `scale(${ZOOM})`;
        incoming.style.opacity = '0';
        requestAnimationFrame(() => {
            incoming.style.transition = move;
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
        timer = setInterval(advance, PERIOD);
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
