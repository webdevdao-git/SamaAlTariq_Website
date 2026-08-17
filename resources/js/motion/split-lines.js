import { subscribeToScroll, prefersReducedMotion } from './scroll-engine';

/**
 * Line-by-line text reveal for elements marked `data-split`.
 *
 * Each rendered line is wrapped in a clipping mask and starts pushed down by its
 * own height, then slides up on a stagger — so the words appear to rise out from
 * behind the line above.
 *
 * Two details make this safe rather than clever:
 *
 * 1. Accessibility. The generated line spans are `aria-hidden` and the element
 *    keeps the original string as `aria-label`, so a screen reader announces a
 *    sentence rather than a pile of fragments.
 *
 * 2. Real lines, not assumed ones. Where the browser wraps depends on the width
 *    it is given, so lines are measured after layout by grouping words that
 *    share an offsetTop, then re-measured on resize. Splitting on typed-in
 *    breaks alone would mask the wrong places at every other width. A `<br>` in
 *    the source is still honoured — it is put back into the measurement, so the
 *    copy's own break holds while everything else wraps to the column.
 *
 * The original text is stashed on the element, so a re-split always starts from
 * the source string rather than from already-wrapped markup.
 *
 * `data-split-by` picks the unit: "line" (the default, described above), "word"
 * or "letter". The finer two mask each word or letter in its own inline box and
 * run a much shorter stagger across them, which reads as the type assembling
 * itself rather than as lines arriving. They need no measuring — a word is a
 * word at every width — so they skip the ResizeObserver entirely.
 *
 * Anything that does not set the attribute keeps the line behaviour byte for
 * byte, which is what the landing page relies on.
 */
export function initSplitLines() {
    const nodes = Array.from(document.querySelectorAll('[data-split]'));
    if (nodes.length === 0) return;

    // Without motion the markup is left exactly as the server rendered it.
    if (prefersReducedMotion()) return;

    /**
     * The source string, with any authored `<br>` read back as a newline.
     *
     * Measuring finds where the browser wraps, but not where the copy insists
     * on breaking — and some breaks cannot be reached by width at all, because
     * the line that must come second is wider than the words that would join
     * the first. Keeping the newline lets the probe below put that break back
     * before it measures around it.
     */
    const sourceText = (node) => Array.from(node.childNodes)
        .map((child) => (child.nodeName === 'BR' ? '\n' : child.textContent))
        .join('')
        .replace(/[^\S\n]+/g, ' ')
        .trim();

    for (const node of nodes) {
        node.dataset.splitText = sourceText(node);
        // Flattened for the label: the break is typography, not a pause, and a
        // screen reader should still hear one sentence.
        node.setAttribute('aria-label', node.dataset.splitText.replace(/\n/g, ' '));
    }

    /**
     * A first-line indent has to be taken off every box this file makes.
     *
     * text-indent inherits, and it indents the first line of whatever block or
     * inline-block it lands on — so the measuring words and the masks below,
     * which are exactly that, each take the paragraph's indent for themselves.
     * A 110px indent on a sentence measured word by word becomes 110px on
     * every word, and the line fits three of them.
     *
     * So the boxes are zeroed and the indent is put back in the one place it
     * belongs: the block that holds the first line.
     */
    const noIndent = (el) => { el.style.textIndent = '0'; return el; };

    /**
     * Word and letter modes. Each unit gets its own inline mask, and the
     * spaces between words stay as real text nodes outside the masks — put a
     * space inside a clipping box and it collapses, so the words would run
     * together the moment the type was split.
     */
    const splitUnits = (node, mode) => {
        const text = node.dataset.splitText;
        const delay = Number(node.dataset.splitDelay) || 0;
        const step = mode === 'letter' ? 22 : 45;

        const fragment = document.createDocumentFragment();
        let index = 0;

        const box = (content) => {
            const mask = noIndent(document.createElement('span'));
            mask.className = 'unit-mask';
            mask.setAttribute('aria-hidden', 'true');

            const inner = document.createElement('span');
            inner.textContent = content;
            inner.style.transitionDelay = `${delay + index * step}ms`;
            index += 1;

            mask.append(inner);
            return mask;
        };

        text.split('\n').forEach((line, l) => {
            if (l > 0) fragment.append(document.createElement('br'));

            line.split(/\s+/).filter(Boolean).forEach((word, i) => {
                if (i > 0) fragment.append(document.createTextNode(' '));

                if (mode === 'word') {
                    fragment.append(box(word));
                    return;
                }

                // A word is kept whole so it can never break mid-way across a
                // line ending; only the letters inside it are masked separately.
                const holder = noIndent(document.createElement('span'));
                holder.className = 'unit-word';
                holder.setAttribute('aria-hidden', 'true');
                for (const letter of Array.from(word)) holder.append(box(letter));
                fragment.append(holder);
            });
        });

        node.replaceChildren(fragment);
    };

    const split = (node) => {
        const mode = node.dataset.splitBy || 'line';
        if (mode !== 'line') return splitUnits(node, mode);

        const text = node.dataset.splitText;
        const delay = Number(node.dataset.splitDelay) || 0;

        // Measure with inline-block words, so offsetTop identifies each line.
        // Authored breaks go into the probe as real <br>s, so they land on the
        // measurement as line ends of their own and the words on either side
        // still wrap to whatever width the column is giving them.
        const probe = document.createElement('span');
        probe.style.cssText = 'display:block';
        text.split('\n').forEach((line, l) => {
            if (l > 0) probe.append(document.createElement('br'));

            for (const word of line.split(/\s+/).filter(Boolean)) {
                const span = noIndent(document.createElement('span'));
                span.textContent = word;
                span.style.display = 'inline-block';
                probe.append(span, document.createTextNode(' '));
            }
        });

        node.replaceChildren(probe);

        const lines = [];
        let top = null;

        for (const span of probe.querySelectorAll('span')) {
            const y = span.offsetTop;
            if (top === null || Math.abs(y - top) > 2) {
                top = y;
                lines.push(span.textContent);
            } else {
                lines[lines.length - 1] += ` ${span.textContent}`;
            }
        }

        const fragment = document.createDocumentFragment();

        (lines.length ? lines : [text.replace(/\n/g, ' ')]).forEach((line, i) => {
            // Every mask is a block, so each would take the indent for its own
            // first line. Only the first line has one, so the rest are zeroed
            // and the first is left to inherit what the paragraph is set.
            const mask = document.createElement('span');
            if (i > 0) noIndent(mask);
            mask.className = 'line-mask';
            mask.setAttribute('aria-hidden', 'true');

            const inner = document.createElement('span');
            inner.textContent = line;
            inner.style.transitionDelay = `${delay + i * 90}ms`;

            mask.append(inner);
            fragment.append(mask);
        });

        node.replaceChildren(fragment);
    };

    nodes.forEach(split);

    // Only the line mode depends on where the browser wraps, so only it needs
    // re-measuring; word and letter masks are the same at every width.
    const measured = nodes.filter((node) => (node.dataset.splitBy || 'line') === 'line');

    // Re-split on width change only; height changes are our own doing.
    const widths = new WeakMap(measured.map((node) => [node, node.getBoundingClientRect().width]));
    let timer = 0;

    const observer = new ResizeObserver((entries) => {
        const changed = entries.filter((entry) => {
            const previous = widths.get(entry.target);
            const next = entry.contentRect.width;
            if (previous !== undefined && Math.abs(next - previous) < 1) return false;
            widths.set(entry.target, next);
            return true;
        });

        if (changed.length === 0) return;

        clearTimeout(timer);
        timer = setTimeout(() => {
            changed.forEach((entry) => {
                split(entry.target);
                if (entry.target.dataset.splitVisible === 'true') reveal(entry.target);
            });
        }, 120);
    });

    measured.forEach((node) => observer.observe(node));

    const reveal = (node) => {
        node.dataset.splitVisible = 'true';
        node.querySelectorAll('.line-mask, .unit-mask').forEach((mask) => mask.classList.add('is-visible'));
    };

    const pending = new Set(nodes);

    const unsubscribe = subscribeToScroll(({ vh }) => {
        const limit = vh * 0.92;

        for (const node of pending) {
            if (node.getBoundingClientRect().top >= limit) continue;
            reveal(node);
            pending.delete(node);
        }

        if (pending.size === 0) unsubscribe();
    });
}
