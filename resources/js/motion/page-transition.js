import { prefersReducedMotion } from './scroll-engine';

/**
 * The teal curtain, carried between pages.
 *
 * Every marketing page already opens behind the entry curtain and lifts it
 * (preloader.js). This is the other half: following a link draws a curtain of
 * the same teal UP from the foot of the screen, and only then navigates — so
 * the old page is covered by exactly the colour the new one opens on, and the
 * two read as one continuous wipe instead of a white flash between documents.
 *
 * Plain links throughout. The navigation itself is an ordinary one, so the
 * history, the back button, the server's response and a failed script all
 * behave as they would without this; the only thing added is 700ms of teal in
 * front of it.
 *
 * Left alone on purpose:
 *   - modified clicks, middle clicks, target/download links — the visitor is
 *     asking for a new tab or a file, not for this page to go anywhere;
 *   - fragments on the same page — Lenis eases those;
 *   - the portal, admin and auth screens, which use their own layouts with no
 *     entry curtain to lift, so a wipe in would have no wipe out;
 *   - anything that is not a page (the profile PDF, the sitemap).
 */
const COVER_MS = 700;
const SKIP_PATHS = /^\/(portal|admin|login|logout|forgot-password|reset-password)(\/|$)/;
const FILE_EXT = /\.[a-z0-9]{2,5}$/i;

/** Read by preloader.js: the visitor arrived through a wipe, not a cold load. */
export const TRANSIT_KEY = 'sat:transit';

function eligible(event, anchor) {
    if (event.defaultPrevented || event.button !== 0) return false;
    if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return false;
    if (anchor.target && anchor.target !== '_self') return false;
    if (anchor.hasAttribute('download') || anchor.dataset.noTransition !== undefined) return false;

    const url = new URL(anchor.href, window.location.href);
    if (url.origin !== window.location.origin) return false;
    if (SKIP_PATHS.test(url.pathname) || FILE_EXT.test(url.pathname)) return false;

    // Same document, different fragment: an in-page jump, not a navigation.
    const here = window.location;
    if (url.pathname === here.pathname && url.search === here.search && url.hash) return false;

    return url;
}

function buildCover() {
    const cover = document.createElement('div');
    cover.className = 'page-cover';
    cover.setAttribute('aria-hidden', 'true');

    // The same mark the entry curtain carries, so the hand-over between the
    // two curtains is invisible: the new page's mark is already sitting there.
    const source = document.querySelector('.intro-curtain__mark img, header img[src*="logo-mark"]');
    const mark = document.createElement('div');
    mark.className = 'page-cover__mark';
    const img = document.createElement('img');
    img.src = source?.getAttribute('src') ?? '/images/logo-mark.png';
    img.alt = '';
    img.width = 540;
    img.height = 462;
    img.className = 'h-auto w-[clamp(56px,6vw,96px)]';
    mark.append(img);
    cover.append(mark);

    return cover;
}

export function initPageTransition() {
    if (prefersReducedMotion()) return;

    let leaving = false;

    document.addEventListener('click', (event) => {
        const anchor = event.target?.closest?.('a[href]');
        if (!anchor || leaving) return;

        const url = eligible(event, anchor);
        if (!url) return;

        event.preventDefault();
        leaving = true;

        const cover = buildCover();
        document.body.append(cover);

        // Two frames: one to insert the cover at its start state, one to let
        // the transition see the change.
        requestAnimationFrame(() => requestAnimationFrame(() => cover.classList.add('is-covering')));

        try {
            sessionStorage.setItem(TRANSIT_KEY, '1');
        } catch {
            // Storage blocked: the next page just takes its cold-load timing.
        }

        setTimeout(() => window.location.assign(url.href), COVER_MS);
    });

    /*
     * Back/forward cache. A page restored from it comes back exactly as it was
     * left — which is under the cover. Clear it, or Back lands on a teal wall.
     */
    window.addEventListener('pageshow', (event) => {
        if (!event.persisted) return;
        leaving = false;
        document.querySelectorAll('.page-cover').forEach((node) => node.remove());
    });
}
