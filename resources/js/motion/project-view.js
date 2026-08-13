/**
 * The projects page's Gallery / List switch.
 *
 * The radios are the state; this only mirrors the checked one onto the wrapper
 * as `data-project-view`, which the stylesheet reads. Nothing is created or
 * destroyed, so switching costs a repaint and no fetches — both views are in
 * the markup already, sharing the same image files.
 *
 * Absent this script the wrapper keeps the `gallery` it was rendered with and
 * the page is complete; the switch simply does nothing. That is why the default
 * is written into the HTML rather than applied here on boot.
 */
export function initProjectView() {
    const wrapper = document.querySelector('[data-project-view]');
    const radios = Array.from(document.querySelectorAll('input[name="project-view"]'));
    if (!wrapper || radios.length === 0) return;

    for (const radio of radios) {
        radio.addEventListener('change', () => {
            if (!radio.checked) return;
            wrapper.dataset.projectView = radio.value;
        });
    }
}
