/*
 * The services index: the row across the middle of the screen is the active
 * one, and its photograph wipes up over the last in the held frame. Hovering
 * a row makes it active too. Desktop only — below lg each row has its own
 * picture and nothing is held.
 */
export function initServicesIndex() {
    const root = document.querySelector('[data-svc-index]');
    if (!root) return;

    const rows = Array.from(root.querySelectorAll('[data-svc-row]'));
    const imgs = Array.from(root.querySelectorAll('[data-svc-img]'));
    const count = root.querySelector('[data-svc-count]');
    const total = String(rows.length).padStart(2, '0');
    let current = 0;

    const activate = (index) => {
        if (index === current) return;
        rows.forEach((row, i) => row.classList.toggle('is-active', i === index));
        imgs.forEach((img, i) => {
            img.classList.toggle('is-leaving', i === current);
            img.classList.toggle('is-active', i === index);
        });
        // Clear the old picture once the new one has fully covered it.
        const leaving = imgs[current];
        setTimeout(() => leaving?.classList.remove('is-leaving'), 950);
        current = index;
        if (count) count.textContent = `${String(index + 1).padStart(2, '0')} / ${total}`;
    };

    const desktop = window.matchMedia('(min-width: 1024px)');

    const observer = new IntersectionObserver((entries) => {
        if (!desktop.matches) return;
        entries.forEach((entry) => {
            if (entry.isIntersecting) activate(Number(entry.target.dataset.svcRow));
        });
    }, { rootMargin: '-48% 0px -48% 0px' });

    rows.forEach((row) => {
        observer.observe(row);
        row.addEventListener('mouseenter', () => {
            if (desktop.matches) activate(Number(row.dataset.svcRow));
        });
    });
}
