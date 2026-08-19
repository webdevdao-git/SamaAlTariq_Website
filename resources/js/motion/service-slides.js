/**
 * The services panels, switched in place by their own tab row.
 *
 * Without JavaScript this is :target and nothing else: the tabs are anchor
 * links, the panel whose id is in the URL is the one shown, and the box falls
 * back to the first panel when nothing of its own is targeted. That still
 * works — it is the reason the tabs are links rather than buttons.
 *
 * What this adds is the swap without the navigation. A fragment jump moves the
 * page to the panel's top and writes a history entry for every tab pressed;
 * neither is wanted when the three panels occupy one box and the visitor is
 * already looking at it. So the click is taken over, the active panel is marked
 * with a class, and `is-enhanced` on the box tells the stylesheet to follow the
 * class rather than the URL.
 */
export function initServiceSlides() {
    const boxes = document.querySelectorAll('.service-slides');

    for (const box of boxes) {
        const slides = [...box.querySelectorAll('.service-slide')];
        if (slides.length < 2) continue;

        box.classList.add('is-enhanced');

        // Whatever the URL already points at, so a link straight to
        // /#service-2 still lands on that panel.
        const targeted = slides.find((slide) => `#${slide.id}` === window.location.hash);
        const show = (slide) => {
            for (const other of slides) {
                const active = other === slide;
                other.classList.toggle('is-active', active);

                for (const tab of other.querySelectorAll('nav a')) {
                    // Each panel carries its own copy of the row, so the mark
                    // of the current panel is set on all of them.
                    const current = tab.getAttribute('href') === `#${slide.id}`;
                    tab.toggleAttribute('aria-current', current);
                    tab.classList.toggle('is-current', current);
                }
            }
        };

        show(targeted ?? slides[0]);

        box.addEventListener('click', (event) => {
            const tab = event.target.closest('a[href^="#service-"]');
            if (!tab) return;

            const slide = slides.find((s) => `#${s.id}` === tab.getAttribute('href'));
            if (!slide) return;

            event.preventDefault();
            show(slide);
        });
    }
}
