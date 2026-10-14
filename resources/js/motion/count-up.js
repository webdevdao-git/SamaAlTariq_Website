import { subscribeToScroll, prefersReducedMotion } from './scroll-engine';

/**
 * Figures that count up to themselves as they come into view.
 *
 * `data-count` goes on the element holding the figure, and the figure stays
 * in the markup exactly as the copy writes it — "250k+", "03+", "95%". The
 * number inside it is found, run up from zero, and the original string is
 * written back at the end, so the landed state is the server's own text to
 * the character, and without JavaScript nothing changes at all.
 *
 * A leading zero is kept for the whole run ("00+" → "03+"), so a figure the
 * copy pads never jumps width when it lands.
 */
const DURATION = 1800;
const easeOutExpo = (t) => (t === 1 ? 1 : 1 - Math.pow(2, -10 * t));

function parse(text) {
    const match = text.match(/^(\D*)(\d+(?:\.\d+)?)(.*)$/s);
    if (!match) return null;

    const [, prefix, digits, suffix] = match;
    const decimals = digits.includes('.') ? digits.split('.')[1].length : 0;

    // Only a figure the copy pads keeps its width; "250" counts up as 7, 48,
    // 250 — not 007, 048.
    const padded = digits.length > 1 && digits.startsWith('0');

    return { prefix, suffix, value: Number(digits), decimals, width: padded ? digits.length : 0 };
}

function format(n, spec) {
    let body = n.toFixed(spec.decimals);
    if (!spec.decimals && body.length < spec.width) body = body.padStart(spec.width, '0');
    return `${spec.prefix}${body}${spec.suffix}`;
}

function run(node, spec, original) {
    const start = performance.now();

    const step = (now) => {
        const t = Math.min(1, (now - start) / DURATION);
        node.textContent = format(spec.value * easeOutExpo(t), spec);

        if (t < 1) requestAnimationFrame(step);
        else node.textContent = original;
    };

    requestAnimationFrame(step);
}

export function initCountUp() {
    const nodes = Array.from(document.querySelectorAll('[data-count]'));
    if (nodes.length === 0 || prefersReducedMotion()) return;

    const pending = new Map();

    for (const node of nodes) {
        const original = node.textContent.trim();
        const spec = parse(original);
        if (!spec) continue;

        // Set to the start before the first paint it is visible in. Read out
        // as the real figure throughout — the moving digits are decoration.
        node.setAttribute('aria-label', original);
        node.style.fontVariantNumeric = 'tabular-nums';
        node.textContent = format(0, spec);
        pending.set(node, { spec, original });
    }

    const unsubscribe = subscribeToScroll(({ vh }) => {
        for (const [node, { spec, original }] of pending) {
            if (node.getBoundingClientRect().top >= vh * 0.9) continue;
            pending.delete(node);
            run(node, spec, original);
        }

        if (pending.size === 0) unsubscribe();
    });
}
