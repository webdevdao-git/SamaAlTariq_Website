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

function boot() {
    initPasswordToggles();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
