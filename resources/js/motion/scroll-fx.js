import { subscribeToScroll, prefersReducedMotion } from './scroll-engine';

/**
 * The site-wide scroll layer — the movement a visitor sees on every page
 * without having to hover anything.
 *
 *   UNMASK    photographs open from the foot of their frame upward while
 *             settling from a push-in, instead of simply being there.
 *   STAND UP  the footer lock-up starts laid back in space and stands upright
 *             as the footer comes up.
 *
 * Every one of them writes properties no other module on the site owns —
 * clip-path, and the individual `scale` / `translate` / `rotate`-free
 * transforms named below — so they compose with the parallax, drift and
 * reveal transforms already running rather than fighting them.
 *
 * Images that already belong to a scripted scene are left alone: the heroes,
 * the tower, the client strip, and the pinned galleries each draw their own
 * pictures and an extra mask would only get in their way.
 */
const OWNED = [
    '#top', '[data-hero]', '[data-tower]', '.marquee', '[data-grow-scene]', '[data-reveal-scene]',
    '[data-scroll-gallery]', '[data-slide-cycle]', '[data-process]', '[data-lightbox]',
    '.service-slides', '.intro-curtain', 'footer', 'header', '.project-list', '#contact',
    // Every picture on the About page has a scene of its own (about-scenes.js);
    // a second mask on top of those clipped the collage to a sliver.
    'main[data-page="about"]',
].join(',');

const clamp01 = (v) => Math.min(1, Math.max(0, v));
const easeOut = (t) => 1 - Math.pow(1 - t, 3);

function initUnmask() {
    const images = Array.from(document.querySelectorAll('main img')).filter((img) =>
        !img.closest(OWNED)
        && !img.classList.contains('reveal-media')
        && !/\.(png|svg)(\?|$)/i.test(img.getAttribute('src') ?? '')
        && img.getBoundingClientRect().width > 120,
    );
    if (images.length === 0) return;

    for (const img of images) img.classList.add('fx-unmask');

    const pending = new Set(images);
    const unsubscribe = subscribeToScroll(({ vh }) => {
        for (const img of pending) {
            if (img.getBoundingClientRect().top >= vh * 0.92) continue;
            img.classList.add('is-in');
            pending.delete(img);
        }
        if (pending.size === 0) unsubscribe();
    });
}

function initStandUp() {
    const lines = Array.from(document.querySelectorAll('footer [data-fit-text]'));
    if (lines.length === 0) return;

    for (const line of lines) line.classList.add('fx-stand');

    /*
     * Keyed to how much of the page is left, not to where the lines sit on
     * screen: when the footer waits underneath the page (scenes.js), it never
     * moves up the screen at all, and a position-based progress never
     * finished — the sub-line was left half laid back. The last screen of
     * scroll is the footer arriving either way.
     */
    subscribeToScroll(({ y, vh }) => {
        const left = document.documentElement.scrollHeight - vh - y;
        const span = Math.max(1, Math.min(vh, lines[0].closest('footer').offsetHeight));

        lines.forEach((line, i) => {
            const up = easeOut(clamp01((1 - left / span) * 1.25 - i * 0.12));
            line.style.transform =
                `perspective(900px) rotateX(${((1 - up) * 80).toFixed(2)}deg) translateY(${((1 - up) * 40).toFixed(2)}%)`;
            line.style.opacity = (0.1 + up * 0.9).toFixed(3);
        });
    });
}

export function initScrollFx() {
    if (prefersReducedMotion()) return;

    initUnmask();
    initStandUp();
}
