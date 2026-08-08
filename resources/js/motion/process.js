import { subscribeToScroll, prefersReducedMotion } from './scroll-engine';

/**
 * Our Process — a sticky text column that cross-fades as the step images scroll
 * past it.
 *
 * Each step's weight is how close its image is to the sticky text, falling off
 * linearly to zero one image-spacing away. Adjacent steps therefore blend
 * rather than snapping, which is what the reference does: mid-transition it
 * measured 0.47 / 0.53 across two steps rather than 0 / 1.
 *
 * The stacking is added here, not in the markup. Left alone, the four steps are
 * an ordinary readable list — so with JavaScript off, or motion reduced, the
 * section degrades to exactly that instead of four paragraphs piled on top of
 * one another.
 */
export function initProcessScroll() {
    const root = document.querySelector('[data-process]');
    if (!root) return;

    const stack = root.querySelector('[data-process-stack]');
    const steps = [...root.querySelectorAll('[data-process-step]')];
    const images = [...root.querySelectorAll('[data-process-image]')];
    const rails = [...root.querySelectorAll('[data-process-rail]')];
    const railLines = [...root.querySelectorAll('[data-process-rail-line]')];

    if (steps.length === 0 || steps.length !== images.length) return;
    if (prefersReducedMotion()) return;

    /*
     * Only stack where the text column is actually sticky — the Blade view
     * applies `lg:sticky`, so below that breakpoint the column scrolls with the
     * page and there is nothing for the images to cross-fade against. Stacking
     * anyway would pile four steps on one another with no way to read them.
     */
    const canStack = window.matchMedia('(min-width: 1024px)');

    let dominant = -1;

    const stackOn = () => {
        stack.classList.add('is-stacked');
        // Set an initial state immediately. The scroll handler bails out while
        // the section is far off screen, so without this the steps would sit
        // stacked at full opacity — all four legible at once, on top of each
        // other — until the first scroll near them.
        steps.forEach((s, i) => {
            s.style.opacity = i === 0 ? '1' : '0';
            if (i === 0) s.removeAttribute('aria-hidden');
            else s.setAttribute('aria-hidden', 'true');
        });
        dominant = 0;
    };

    const stackOff = () => {
        stack.classList.remove('is-stacked');
        steps.forEach((s) => {
            s.style.opacity = '';
            s.removeAttribute('aria-hidden');
        });
        dominant = -1;
    };

    if (canStack.matches) stackOn();
    canStack.addEventListener('change', (e) => (e.matches ? stackOn() : stackOff()));

    subscribeToScroll(({ vh }) => {
        if (!canStack.matches) return;

        const stackRect = stack.getBoundingClientRect();
        // Cheap bail-out: nothing to do while the section is far off screen.
        if (stackRect.bottom < -vh || stackRect.top > vh * 2) return;

        // The line the images are measured against: the middle of the text.
        const reference = stackRect.top + stackRect.height / 2;

        const centres = images.map((img) => {
            const r = img.getBoundingClientRect();
            return r.top + r.height / 2;
        });

        // Spacing between consecutive images — the distance over which one step
        // hands over to the next.
        const spacing = centres.length > 1
            ? Math.abs(centres[1] - centres[0])
            : vh;

        const weights = centres.map((c) => Math.max(0, 1 - Math.abs(c - reference) / spacing));
        const total = weights.reduce((a, w) => a + w, 0);

        // Before the first image reaches the text and after the last has left,
        // every weight is 0 — pin to the nearest end rather than fading to
        // nothing, which would leave an empty column.
        if (total === 0) {
            const nearest = reference < centres[0] ? 0 : centres.length - 1;
            weights[nearest] = 1;
        }

        const sum = weights.reduce((a, w) => a + w, 0);
        let best = 0;

        weights.forEach((w, i) => {
            const opacity = w / sum;
            steps[i].style.opacity = opacity.toFixed(3);
            if (w > weights[best]) best = i;
        });

        if (best === dominant) return;
        dominant = best;

        // Only the step actually being read is exposed; the rest are decorative
        // duplicates stacked underneath it.
        steps.forEach((s, i) => {
            if (i === best) s.removeAttribute('aria-hidden');
            else s.setAttribute('aria-hidden', 'true');
        });

        rails.forEach((r, i) => {
            r.classList.toggle('text-ink', i <= best);
            r.classList.toggle('text-ink/35', i > best);
        });

        railLines.forEach((l, i) => {
            l.classList.toggle('bg-teal', i <= best);
            l.classList.toggle('bg-ink/20', i > best);
        });
    });
}
