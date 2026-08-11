/**
 * Custom dropdown for the enquiry form's property type.
 *
 * The native select is left in the page and keeps the value; this only draws a
 * menu over it, because the OS control cannot be styled to match the form and
 * the form has to keep working when this file does not run. Every choice made
 * here writes straight back to the select, so submission, server-side
 * validation and old() repopulation never learn that a widget exists.
 *
 * Follows the ARIA select-only combobox pattern: a button that owns the
 * expanded state, a listbox of options, and aria-activedescendant to move a
 * highlight through them while focus stays on the button. That last part is
 * what keeps the keyboard behaviour close to a real select — focus never
 * enters the menu, so Escape and Tab land where a user expects.
 */
export function initFieldSelect() {
    document.querySelectorAll('[data-select]').forEach(enhance);
}

function enhance(root) {
    const native = root.querySelector('[data-select-native]');
    const button = root.querySelector('[data-select-button]');
    const listbox = root.querySelector('[data-select-listbox]');
    const valueEl = root.querySelector('[data-select-value]');
    if (!native || !button || !listbox || !valueEl) return;

    const options = Array.from(listbox.querySelectorAll('[role="option"]'));
    if (!options.length) return;

    // The button's starting text, kept so it can be restored if the field is
    // ever cleared back to the empty option.
    const placeholder = valueEl.textContent.trim();

    let open = false;
    let activeIndex = -1;
    let typeahead = '';
    let typeaheadTimer = 0;

    // The select is now a duplicate of the button for anything that reads the
    // page, and it must not be tabbable — but it stays focusable, so the
    // browser can still point its own "please fill this in" at the field.
    native.setAttribute('aria-hidden', 'true');
    native.setAttribute('tabindex', '-1');
    root.dataset.enhanced = 'true';

    const indexOfValue = (value) => options.findIndex((o) => o.dataset.value === value);

    const paint = () => {
        const selected = indexOfValue(native.value);

        options.forEach((option, i) => {
            option.setAttribute('aria-selected', i === selected ? 'true' : 'false');
            option.dataset.active = i === activeIndex ? 'true' : 'false';
        });

        valueEl.textContent = selected === -1 ? placeholder : options[selected].dataset.value;
    };

    const setActive = (index) => {
        activeIndex = Math.max(0, Math.min(index, options.length - 1));
        button.setAttribute('aria-activedescendant', options[activeIndex].id);
        paint();
        // block: 'nearest' so opening the menu does not jerk a menu that
        // already fits, and the page never scrolls underneath it.
        options[activeIndex].scrollIntoView({ block: 'nearest' });
    };

    const setOpen = (next) => {
        if (open === next) return;
        open = next;

        listbox.hidden = !next;
        button.setAttribute('aria-expanded', next ? 'true' : 'false');
        root.dataset.open = next ? 'true' : 'false';

        if (next) {
            // Open on the current value, or the first option when unset, which
            // is what a native select does.
            setActive(Math.max(0, indexOfValue(native.value)));
        } else {
            button.removeAttribute('aria-activedescendant');
            activeIndex = -1;
            paint();
        }
    };

    const choose = (index) => {
        native.value = options[index].dataset.value;
        // Bubbles, so anything watching the form for changes sees this exactly
        // as it would see a native selection.
        native.dispatchEvent(new Event('change', { bubbles: true }));
        setOpen(false);
        paint();
        button.focus();
    };

    button.addEventListener('click', () => setOpen(!open));

    button.addEventListener('keydown', (event) => {
        switch (event.key) {
            case 'ArrowDown':
                event.preventDefault();
                open ? setActive(activeIndex + 1) : setOpen(true);
                break;
            case 'ArrowUp':
                event.preventDefault();
                open ? setActive(activeIndex - 1) : setOpen(true);
                break;
            case 'Home':
                if (!open) break;
                event.preventDefault();
                setActive(0);
                break;
            case 'End':
                if (!open) break;
                event.preventDefault();
                setActive(options.length - 1);
                break;
            case 'Enter':
            case ' ':
                event.preventDefault();
                open && activeIndex > -1 ? choose(activeIndex) : setOpen(true);
                break;
            case 'Escape':
                if (!open) break;
                event.preventDefault();
                setOpen(false);
                break;
            case 'Tab':
                // Let focus leave, but do not leave a menu hanging over the
                // field behind it.
                setOpen(false);
                break;
            default:
                jumpToLetter(event);
        }
    });

    /**
     * Type a few letters to jump, as a native select does. The buffer clears
     * after a pause so "co" finds Commercial Building but a later "o" starts
     * again at Office rather than searching for "coo".
     */
    function jumpToLetter(event) {
        if (event.key.length !== 1 || event.metaKey || event.ctrlKey || event.altKey) return;

        typeahead += event.key.toLowerCase();
        window.clearTimeout(typeaheadTimer);
        typeaheadTimer = window.setTimeout(() => { typeahead = ''; }, 600);

        const match = options.findIndex(
            (o) => o.dataset.value.toLowerCase().startsWith(typeahead)
        );
        if (match === -1) return;

        event.preventDefault();
        if (!open) setOpen(true);
        setActive(match);
    }

    options.forEach((option, i) => {
        // pointerdown, not click: the document handler below closes on
        // pointerdown, and a click would arrive after the menu had gone.
        option.addEventListener('pointerdown', (event) => {
            event.preventDefault();
            choose(i);
        });
        option.addEventListener('pointermove', () => {
            if (activeIndex !== i) setActive(i);
        });
    });

    document.addEventListener('pointerdown', (event) => {
        if (open && !root.contains(event.target)) setOpen(false);
    });

    // Something else may set the value — a reset, or the browser restoring the
    // form on a back navigation — so the button follows the select rather than
    // assuming it is the only thing writing to it.
    native.addEventListener('change', paint);

    // The browser prompts on the invisible select; send focus somewhere the
    // user can act on.
    native.addEventListener('invalid', () => button.focus());

    paint();
}
