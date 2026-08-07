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
 * 2. Real lines, not authored ones. Where the browser wraps depends on the width
 *    it is given, so lines are measured after layout by grouping words that
 *    share an offsetTop, then re-measured on resize. Splitting on hardcoded
 *    breaks would mask the wrong places at every other width.
 *
 * The original text is stashed on the element, so a re-split always starts from
 * the source string rather than from already-wrapped markup.
 */
export function initSplitLines() {
    const nodes = Array.from(document.querySelectorAll('[data-split]'));
    if (nodes.length === 0) return;

    // Without motion the markup is left exactly as the server rendered it.
    if (prefersReducedMotion()) return;

    for (const node of nodes) {
        node.dataset.splitText = node.textContent.trim();
        node.setAttribute('aria-label', node.dataset.splitText);
    }

    const split = (node) => {
        const text = node.dataset.splitText;
        const delay = Number(node.dataset.splitDelay) || 0;

        // Measure with inline-block words, so offsetTop identifies each line.
        const probe = document.createElement('span');
        probe.style.cssText = 'display:block';
        for (const word of text.split(/\s+/).filter(Boolean)) {
            const span = document.createElement('span');
            span.textContent = word;
            span.style.display = 'inline-block';
            probe.append(span, document.createTextNode(' '));
        }

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

        (lines.length ? lines : [text]).forEach((line, i) => {
            const mask = document.createElement('span');
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

    // Re-split on width change only; height changes are our own doing.
    const widths = new WeakMap(nodes.map((node) => [node, node.getBoundingClientRect().width]));
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

    nodes.forEach((node) => observer.observe(node));

    const reveal = (node) => {
        node.dataset.splitVisible = 'true';
        node.querySelectorAll('.line-mask').forEach((mask) => mask.classList.add('is-visible'));
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
