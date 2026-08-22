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
const SPAN = 0.55;   // of the section's travel, per line
const STAGGER = 0.12; // each line starts this much after the one above

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
             * The middle of the travel does the work: read end to end and the
             * fill would finish while the band was still climbing into view.
             */
            const p = Math.min(1, Math.max(0, (viewportProgress(rect, vh) - 0.18) / 0.5));

            for (const [i, line] of band.lines.entries()) {
                const start = i * STAGGER;
                const fill = Math.min(1, Math.max(0, (p - start) / SPAN));
                line.style.setProperty('--fill', (fill * 100).toFixed(1));
            }
        }
    });
}
