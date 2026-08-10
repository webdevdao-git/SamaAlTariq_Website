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

/** Selects marked data-auto-submit apply as soon as they change. */
function initAutoSubmit() {
    for (const select of document.querySelectorAll('[data-auto-submit]')) {
        select.addEventListener('change', () => select.form?.submit());
    }
}

/**
 * Generates a temporary password into the named fields.
 *
 * Uses crypto.getRandomValues rather than Math.random — this string is a real
 * credential, and Math.random is not a cryptographic source.
 */
function initPasswordGenerator() {
    const ALPHABET = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';

    for (const button of document.querySelectorAll('[data-generate-password]')) {
        button.addEventListener('click', () => {
            const bytes = crypto.getRandomValues(new Uint8Array(16));
            const password = [...bytes].map((b) => ALPHABET[b % ALPHABET.length]).join('');

            for (const id of button.dataset.generatePassword.split(',')) {
                const field = document.getElementById(id.trim());
                if (field) field.value = password;
            }
        });
    }
}

/** Repeatable stage rows on the create-project form. */
function initStageRows() {
    for (const button of document.querySelectorAll('[data-stage-add]')) {
        const rows = button.closest('[data-stage-list]')?.querySelector('[data-stage-rows]');
        if (!rows) continue;

        button.addEventListener('click', () => {
            const field = rows.firstElementChild.cloneNode(true);
            field.value = '';
            rows.append(field);
            field.focus();
        });
    }
}

/**
 * File inputs that submit as soon as a file is chosen, so the drop zone needs
 * no separate Upload button. A <noscript> button covers the case where this
 * never runs.
 */
function initUploadOnChange() {
    for (const input of document.querySelectorAll('[data-submit-on-change]')) {
        input.addEventListener('change', () => input.files?.length && input.form?.submit());
    }
}

function boot() {
    initPasswordToggles();
    initSidebar();
    initAutoSubmit();
    initPasswordGenerator();
    initStageRows();
    initUploadOnChange();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
