import { subscribeToScroll, prefersReducedMotion } from './scroll-engine';

/**
 * Our Process — a sticky text column that cross-fades as the step images scroll
 * past it.
 *
 * One step is shown at a time — whichever image is nearest the sticky text —
 * and the change is handed to CSS to animate.
 *
 * It used to derive each step's opacity from how close its image was to the
 * text, cubed and normalised across all four. That cannot work: normalising two
 * equal weights always returns 0.5 / 0.5, so every handover passed through a
 * point where two serif headings sat at half opacity on top of each other, and
 * a slow scroll could park there indefinitely. Cubing narrowed that window but
 * could not move the midpoint.
 *
 * Driving opacity from time instead of scroll position fixes it at the source:
 * the fade lasts as long as the CSS transition and no scroll position can hold
 * two steps half-visible.
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
    // The Figma layout carries no progress rail, so these are normally empty.
    // Kept as lookups rather than deleted: the forEach calls below no-op on an
    // empty list, so a rail can be reinstated in the Blade view with no JS change.
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
            s.style.transform = i === 0 ? 'translate3d(0,0,0)' : 'translate3d(0,26px,0)';
            if (i === 0) s.removeAttribute('aria-hidden');
            else s.setAttribute('aria-hidden', 'true');
        });
        dominant = 0;
    };

    const stackOff = () => {
        stack.classList.remove('is-stacked');
        steps.forEach((s) => {
            s.style.opacity = '';
            s.style.transform = '';
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

        // Whichever image is nearest the text is the one being read. Outside
        // the section this still resolves to the first or last step, so the
        // column is never left empty.
        let best = 0;
        centres.forEach((c, i) => {
            if (Math.abs(c - reference) < Math.abs(centres[best] - reference)) best = i;
        });

        /*
         * Hysteresis. Exactly at a handover the two images are equidistant, so
         * a scroll that hovers there would otherwise flip between them on every
         * frame and re-trigger the fade each time. Hold the current step until
         * the challenger is clearly nearer.
         */
        if (dominant >= 0 && best !== dominant) {
            const gain = Math.abs(centres[dominant] - reference) - Math.abs(centres[best] - reference);
            if (gain < spacing * 0.08) best = dominant;
        }

        if (best === dominant) return;
        dominant = best;

        steps.forEach((s, i) => {
            s.style.opacity = i === best ? '1' : '0';
            // Steps still to come wait below, ones already read lift away, so
            // the fade carries a direction rather than dissolving in place.
            const side = i === best ? 0 : (centres[i] < reference ? -1 : 1);
            s.style.transform = `translate3d(0, ${side * 26}px, 0)`;

            // Only the step actually being read is exposed; the rest are
            // decorative duplicates stacked underneath it.
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
