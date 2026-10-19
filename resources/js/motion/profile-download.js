/*
 * The floating "Download Profile" button sits in the bottom-left corner,
 * which on the first screen of every page is where the hero's own caption
 * and counter are. It stays tucked away until the visitor has scrolled past
 * most of that first screen, then fades in.
 */
export function initProfileDownload() {
    const button = document.querySelector('[data-profile-dl]');
    if (!button) return;

    // The first screen: the hero, which on the landing page is pinned for a
    // while, so it is measured rather than assumed to be one screen tall.
    const first = document.querySelector('#top') ?? document.querySelector('main > section');

    let tucked = null;
    const update = () => {
        const next = first
            ? first.getBoundingClientRect().bottom > window.innerHeight * 0.35
            : window.scrollY < window.innerHeight * 0.6;
        if (next === tucked) return;
        tucked = next;
        button.classList.toggle('is-tucked', next);
        button.toggleAttribute('inert', next);
    };

    update();
    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
}
