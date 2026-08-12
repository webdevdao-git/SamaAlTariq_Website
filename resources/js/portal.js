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

/**
 * Repeatable stage rows on the create- and edit-project forms.
 *
 * Rows are added from a <template> rather than cloned from a neighbour, so a
 * new row cannot inherit the one above it — its typed name, its chosen status,
 * or, on the edit form, the hidden id that would make the server update an
 * existing stage instead of inserting a new one.
 *
 * Everything works without this file: the rows already rendered submit and save
 * as they are. Only adding and removing rows needs the script.
 */
function initStageRows() {
    for (const list of document.querySelectorAll('[data-stage-list]')) {
        const rows = list.querySelector('[data-stage-rows]');
        const template = list.querySelector('[data-stage-template]');
        const add = list.querySelector('[data-stage-add]');
        if (!rows || !template || !add) continue;

        /*
         * Field names carry their position — stages[2][name] — so any change to
         * the set has to rewrite them. Removing row 1 of 3 without this leaves
         * PHP a gap it reads as two rows numbered 0 and 2, which is harmless
         * for saving but makes validation messages point at the wrong row.
         */
        const renumber = () => {
            rows.querySelectorAll('[data-stage-row]').forEach((row, i) => {
                for (const field of row.querySelectorAll('[name]')) {
                    field.name = field.name.replace(/stages\[[^\]]*\]/, `stages[${i}]`);
                }

                const name = row.querySelector('[data-stage-name]');
                if (name) name.placeholder = `Stage ${i + 1} name`;
            });
        };

        add.addEventListener('click', () => {
            rows.append(template.content.cloneNode(true));
            renumber();
            rows.lastElementChild?.querySelector('[data-stage-name]')?.focus();
        });

        // Delegated, so it reaches rows added after this ran as well as the
        // ones the server rendered.
        rows.addEventListener('click', (event) => {
            const button = event.target.closest('[data-stage-remove]');
            if (!button) return;

            button.closest('[data-stage-row]')?.remove();
            renumber();
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
