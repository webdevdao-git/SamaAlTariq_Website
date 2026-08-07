/**
 * Full-screen navigation overlay.
 *
 * The MENU button has no designed panel in Figma, so the overlay is built from
 * the footer navigation — the only nav list in the file.
 */
export function initMenu() {
    const overlay = document.querySelector('[data-menu]');
    const openButton = document.querySelector('[data-menu-open]');
    const closeButton = document.querySelector('[data-menu-close]');
    if (!overlay || !openButton) return;

    const links = Array.from(overlay.querySelectorAll('[data-menu-link]'));

    const setOpen = (open) => {
        overlay.classList.toggle('opacity-100', open);
        overlay.classList.toggle('opacity-0', !open);
        overlay.classList.toggle('pointer-events-auto', open);
        overlay.classList.toggle('pointer-events-none', !open);
        openButton.setAttribute('aria-expanded', open ? 'true' : 'false');
        document.body.style.overflow = open ? 'hidden' : '';

        links.forEach((link, i) => {
            link.style.transition = 'opacity 600ms cubic-bezier(0.16,1,0.3,1), transform 600ms cubic-bezier(0.16,1,0.3,1)';
            link.style.transitionDelay = open ? `${120 + i * 45}ms` : '0ms';
            link.style.opacity = open ? '1' : '0';
            link.style.transform = open ? 'none' : 'translateY(18px)';
        });
    };

    setOpen(false);

    openButton.addEventListener('click', () => setOpen(true));
    closeButton?.addEventListener('click', () => setOpen(false));
    links.forEach((link) => link.addEventListener('click', () => setOpen(false)));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') setOpen(false);
    });
}
