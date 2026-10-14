import { prefersReducedMotion } from './scroll-engine';
import { TRANSIT_KEY } from './page-transition';

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
const MAX_WAIT_MS = 1800;

/*
 * Long enough for the mark to finish turning in — 100 of delay and 820 of
 * movement — so it is never caught halfway and sent back out. Still bounded:
 * a loading screen stands between a visitor and the enquiry form.
 */
const HOLD_MS = 640;

/*
 * Arriving through page-transition.js the visitor has already watched the
 * curtain rise and the mark turn in on the page they left, so holding it again
 * for the full beat reads as a second loading screen. The short hold is only
 * long enough for the new page's first paint to settle behind it.
 */
const TRANSIT_HOLD_MS = 180;

function arrivedInTransit() {
    try {
        const flag = sessionStorage.getItem(TRANSIT_KEY) === '1';
        sessionStorage.removeItem(TRANSIT_KEY);
        return flag;
    } catch {
        return false;
    }
}

export function initPreloader() {
    const curtain = document.querySelector('.intro-curtain');
    if (!curtain) return;

    // CSS already hides it under reduced motion; remove it so the :has() scroll
    // lock cannot match a hidden-but-present element and freeze the page.
    if (prefersReducedMotion()) {
        curtain.remove();
        return;
    }

    const transit = arrivedInTransit();
    if (transit) curtain.classList.add('is-transit');

    let lifted = false;

    const lift = () => {
        if (lifted) return;
        lifted = true;
        curtain.dataset.lifting = 'true';
        // scenes.js opens the hero on this, so the photograph's entrance
        // starts exactly as the curtain starts to rise off it.
        window.dispatchEvent(new CustomEvent('curtain:lift'));
        setTimeout(() => curtain.remove(), WIPE_MS);
    };

    const cap = setTimeout(lift, MAX_WAIT_MS);
    const fonts = document.fonts?.ready ?? Promise.resolve();

    fonts.then(() => {
        setTimeout(() => {
            clearTimeout(cap);
            lift();
        }, transit ? TRANSIT_HOLD_MS : HOLD_MS);
    });
}
