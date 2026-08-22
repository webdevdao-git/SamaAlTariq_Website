import { subscribeToScroll, viewportProgress, prefersReducedMotion } from './scroll-engine';

/**
 * Type that fills as its section crosses the screen.
 *
 * Taken from halston-architecture-template.webflow.io/services#stats, which
 * the client asked this slab to match: the words sit in a pale grey and are
 * filled to full ink from the left as the page scrolls, so the sentence reads
 * as it arrives rather than all at once.
 *
 * They do it with two stacked layers — a light "text behind" and a dark title
 * clipped over it. This does it with one: a gradient painted behind the text
 * and clipped to the glyphs, with the hard stop between ink and grey moved by
 * scroll. Same picture, half the markup, and the text stays selectable text.
 *
 *   <section data-text-fill>
 *     <span data-fill-line>   each line fills on its own, slightly behind the
 *                             one above it
 *
 * The lines are staggered because filling them together reads as a single
 * block changing colour rather than as a sentence being written.
 *
 * Progressive enhancement: the CSS default for --fill is 100, so without
 * JavaScript — or under prefers-reduced-motion — every line is simply full
 * ink, which is the frame.
 */
const STAGGER = 0.1; // each line starts this much after the one above

export function initTextFill() {
    const sections = Array.from(document.querySelectorAll('[data-text-fill]'));
    if (sections.length === 0 || prefersReducedMotion()) return;

    const bands = sections
        .map((section) => ({ section, lines: Array.from(section.querySelectorAll('[data-fill-line]')) }))
        .filter((band) => band.lines.length > 0);

    if (bands.length === 0) return;

    subscribeToScroll(({ vh }) => {
        for (const band of bands) {
            const rect = band.section.getBoundingClientRect();
            if (rect.bottom < 0 || rect.top > vh) continue;

            /*
             * The band's whole crossing, end to end. Compressed into the
             * middle of it — as this was — the writing and erasing both
             * finished before the slab reached the centre of the screen, and
             * it sat pale for the rest of the way up.
             */
            const p = viewportProgress(rect, vh);
            const span = 1 - (band.lines.length - 1) * STAGGER;

            for (const [i, line] of band.lines.entries()) {
                /*
                 * Written in, then erased. The reference does not settle on
                 * full ink: the band of ink arrives from the left and leaves
                 * from the left as the section carries on, so a line is pale,
                 * then filling, then full, then emptying, then pale again.
                 *
                 * One number drives both edges. The writing edge covers the
                 * line over the first half of its span and the erasing edge
                 * follows over the second, so a line is at full ink exactly
                 * when it is halfway across the screen — which is where it is
                 * read — and pale at both ends of the journey.
                 */
                const t = Math.min(1, Math.max(0, (p - i * STAGGER) / span));
                const to = Math.min(1, t * 2);
                const from = Math.max(0, t * 2 - 1);

                line.style.setProperty('--fill-to', (to * 100).toFixed(1));
                line.style.setProperty('--fill-from', (from * 100).toFixed(1));
            }
        }
    });
}
