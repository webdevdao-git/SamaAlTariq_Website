import { prefersReducedMotion } from './scroll-engine';

/**
 * Entry curtain. The element is already in the HTML and visible by default, so
 * it paints with the page rather than dropping over content that is already
 * showing. This only flips `data-lifting`, which drives both the clip-path wipe
 * and the CSS scroll lock.
 *
 * Bounded on purpose — a loading screen stands between a visitor and the
 * enquiry form. It lifts on `document.fonts.ready`, capped at 1.6s, so a slow
 * connection never traps anyone behind it.
 */
const WIPE_MS = 900;
const MAX_WAIT_MS = 1600;

export function initPreloader() {
    const curtain = document.querySelector('.intro-curtain');
    if (!curtain) return;

    // CSS already hides it under reduced motion; remove it so the :has() scroll
    // lock cannot match a hidden-but-present element and freeze the page.
    if (prefersReducedMotion()) {
        curtain.remove();
        return;
    }

    let lifted = false;

    const lift = () => {
        if (lifted) return;
        lifted = true;
        curtain.dataset.lifting = 'true';
        setTimeout(() => curtain.remove(), WIPE_MS);
    };

    const cap = setTimeout(lift, MAX_WAIT_MS);
    const fonts = document.fonts?.ready ?? Promise.resolve();

    fonts.then(() => {
        setTimeout(() => {
            clearTimeout(cap);
            lift();
        }, 420);
    });
}
