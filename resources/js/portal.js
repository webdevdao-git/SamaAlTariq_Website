/**
 * Portal and admin behaviour.
 *
 * Deliberately separate from app.js: the signed-in area needs none of the
 * landing page's scroll machinery — no Lenis, no parallax, no split text — and
 * loading them here would ship several kilobytes of motion code to a page whose
 * job is to accept a password.
 */

/** Show/hide toggles on password fields. */
function initPasswordToggles() {
    for (const button of document.querySelectorAll('[data-password-toggle]')) {
        const input = document.getElementById(button.dataset.passwordToggle);
        if (!input) continue;

        button.addEventListener('click', () => {
            const revealed = input.type === 'text';
            input.type = revealed ? 'password' : 'text';

            button.setAttribute('aria-pressed', String(!revealed));
            // The label has to flip too — a static "Show password" would lie
            // to a screen reader as soon as the field is revealed.
            button.setAttribute('aria-label', revealed ? 'Show password' : 'Hide password');

            button.querySelector('[data-icon-show]')?.classList.toggle('hidden', !revealed);
            button.querySelector('[data-icon-hide]')?.classList.toggle('hidden', revealed);
        });
    }
}

/**
 * Sidebar slide-over below `lg`.
 *
 * The sidebar is 300px wide and always visible from `lg` up; below that it sits
 * off-canvas until opened. Scroll is locked while it is open so the page behind
 * does not move under the panel.
 */
function initSidebar() {
    const sidebar = document.querySelector('[data-sidebar]');
    const backdrop = document.querySelector('[data-sidebar-backdrop]');
    const openButton = document.querySelector('[data-sidebar-open]');
    if (!sidebar || !openButton) return;

    const setOpen = (open) => {
        sidebar.classList.toggle('-translate-x-full', !open);
        backdrop?.classList.toggle('opacity-0', !open);
        backdrop?.classList.toggle('pointer-events-none', !open);
        openButton.setAttribute('aria-expanded', String(open));
        document.body.style.overflow = open ? 'hidden' : '';
    };

    openButton.addEventListener('click', () => setOpen(true));
    document.querySelector('[data-sidebar-close]')?.addEventListener('click', () => setOpen(false));
    backdrop?.addEventListener('click', () => setOpen(false));
    document.addEventListener('keydown', (e) => e.key === 'Escape' && setOpen(false));
}

function boot() {
    initPasswordToggles();
    initSidebar();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
