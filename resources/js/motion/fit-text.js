/**
 * Scales a line of text so it exactly spans its container.
 *
 * The footer wordmark is designed to run the full width of the page. Sizing it
 * with a fixed `vw` value only holds for the exact font build it was tuned
 * against: the Figma file uses a licensed face, and the substitute here renders
 * about 13% wider at the same point size, which pushed the final letter off the
 * edge. Measuring instead of assuming makes the lock-up survive a font swap, a
 * fallback face, or a browser that hyphenates differently.
 *
 * Runs after `document.fonts.ready`, because measuring a fallback face and then
 * having the real one load would leave the wrong size baked in.
 */
export function initFitText() {
    const nodes = Array.from(document.querySelectorAll('[data-fit-text]'));
    if (nodes.length === 0) return;

    const fit = (node) => {
        const container = node.parentElement;
        if (!container) return;

        const style = getComputedStyle(node);
        const available = container.clientWidth
            - parseFloat(style.paddingLeft || 0)
            - parseFloat(style.paddingRight || 0);
        if (available <= 0) return;

        // Letter-spacing also applies after the final character, so a tracked
        // line measures one unit wider than it looks. Add that back to the
        // target rather than letting it shrink the type.
        const tracking = parseFloat(style.letterSpacing) || 0;

        // Measure at a known size so repeated fits cannot compound.
        //
        // `width: max-content` during the measurement is the crucial part: on a
        // block element scrollWidth reports the *box* width whenever the text
        // fits inside it, so a line narrower than its container measures as the
        // container and the ratio collapses to 1. Shrink-wrapping first makes
        // the element's own width equal the text width.
        const probe = 100;
        const previousWidth = node.style.width;

        node.style.fontSize = `${probe}px`;
        node.style.width = 'max-content';
        const width = node.getBoundingClientRect().width;
        node.style.width = previousWidth;

        if (!width) return;

        node.style.fontSize = `${(probe * (available + tracking)) / width}px`;
    };

    const run = () => nodes.forEach(fit);

    (document.fonts?.ready ?? Promise.resolve()).then(run);

    let timer = 0;
    window.addEventListener('resize', () => {
        clearTimeout(timer);
        timer = setTimeout(run, 120);
    }, { passive: true });
}
