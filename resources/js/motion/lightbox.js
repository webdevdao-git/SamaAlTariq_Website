/**
 * The gallery lightbox, taken from the reference's behaviour rather than its
 * appearance: a picture opens full screen over the page, the set is navigable
 * from there, and the page underneath does not move.
 *
 * Built here rather than pulled in: the overlay is a backdrop, one picture and
 * two controls, and every library that does this also brings its own styling
 * to argue with.
 *
 * The markup it needs is `[data-lightbox]` on the gallery and
 * `[data-lightbox-item]` on each link, carrying the large file in `href`. Those
 * links work on their own — with the script absent, or before it loads, a click
 * opens the photograph directly, which is why they are anchors and not buttons.
 */
export function initLightbox() {
    const galleries = Array.from(document.querySelectorAll('[data-lightbox]'));
    if (galleries.length === 0) return;

    let items = [];
    let index = 0;
    let opener = null;

    const overlay = document.createElement('div');
    overlay.className =
        'fixed inset-0 z-[100] hidden items-center justify-center bg-black/90 opacity-0 transition-opacity duration-300';
    overlay.setAttribute('role', 'dialog');
    overlay.setAttribute('aria-modal', 'true');
    overlay.setAttribute('aria-label', 'Project photographs');
    overlay.innerHTML = `
        <img data-lightbox-image alt=""
             class="max-h-[86svh] max-w-[86vw] object-contain opacity-0 transition-opacity duration-300">
        <button type="button" data-lightbox-close aria-label="Close"
                class="absolute right-[clamp(1rem,2.31vw,40px)] top-[clamp(1rem,2.31vw,40px)] text-[clamp(1rem,1.157vw,20px)] font-semibold text-white transition-opacity hover:opacity-70">Close</button>
        <button type="button" data-lightbox-prev aria-label="Previous photograph"
                class="absolute left-[clamp(0.5rem,2.31vw,40px)] top-1/2 -translate-y-1/2 p-4 text-[clamp(1.5rem,2.31vw,40px)] leading-none text-white transition-opacity hover:opacity-70">&#8249;</button>
        <button type="button" data-lightbox-next aria-label="Next photograph"
                class="absolute right-[clamp(0.5rem,2.31vw,40px)] top-1/2 -translate-y-1/2 p-4 text-[clamp(1.5rem,2.31vw,40px)] leading-none text-white transition-opacity hover:opacity-70">&#8250;</button>
        <p data-lightbox-count aria-live="polite"
           class="absolute bottom-[clamp(1rem,2.31vw,40px)] left-1/2 -translate-x-1/2 text-[clamp(0.875rem,1.157vw,20px)] font-semibold text-white"></p>
    `;
    document.body.append(overlay);

    const picture = overlay.querySelector('[data-lightbox-image]');
    const counter = overlay.querySelector('[data-lightbox-count]');

    const render = () => {
        const item = items[index];
        picture.style.opacity = '0';
        const next = new Image();
        next.onload = () => {
            picture.src = next.src;
            picture.alt = item.getAttribute('data-lightbox-alt') ?? '';
            picture.style.opacity = '1';
        };
        next.src = item.href;
        counter.textContent = `${index + 1} / ${items.length}`;
    };

    const open = (gallery, item) => {
        items = Array.from(gallery.querySelectorAll('[data-lightbox-item]'));
        index = items.indexOf(item);
        opener = item;

        // Same lock the navigation overlay uses — body overflow, not a fixed
        // position, so the page keeps its scroll offset while the picture is up.
        document.body.style.overflow = 'hidden';
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        requestAnimationFrame(() => (overlay.style.opacity = '1'));
        render();
        overlay.querySelector('[data-lightbox-close]').focus();
    };

    const close = () => {
        overlay.style.opacity = '0';
        setTimeout(() => {
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
            picture.removeAttribute('src');
        }, 300);
        document.body.style.overflow = '';
        opener?.focus();
    };

    const step = (by) => {
        index = (index + by + items.length) % items.length;
        render();
    };

    for (const gallery of galleries) {
        gallery.addEventListener('click', (event) => {
            const item = event.target.closest('[data-lightbox-item]');
            if (!item || !gallery.contains(item)) return;
            event.preventDefault();
            open(gallery, item);
        });
    }

    overlay.addEventListener('click', (event) => {
        // The backdrop closes; the picture and the controls do not.
        if (event.target === overlay) return close();
        if (event.target.closest('[data-lightbox-close]')) return close();
        if (event.target.closest('[data-lightbox-prev]')) return step(-1);
        if (event.target.closest('[data-lightbox-next]')) return step(1);
    });

    document.addEventListener('keydown', (event) => {
        if (overlay.classList.contains('hidden')) return;
        if (event.key === 'Escape') close();
        if (event.key === 'ArrowLeft') step(-1);
        if (event.key === 'ArrowRight') step(1);
    });
}
