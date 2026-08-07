/**
 * The two tabbed sections: Our Expertise and Our Process.
 *
 * Both are drawn in Figma as stacked static states — Expertise shows two panels
 * differing only in the active pill and headline, Process draws one numbered
 * step. Those are states of one component, not separate blocks, so both are
 * built as switchers.
 *
 * Content comes from a JSON script tag rendered by the Blade view, so the copy
 * stays in config/site.php and is never duplicated in JavaScript.
 */

function readJson(id) {
    const el = document.getElementById(id);
    if (!el) return null;
    try {
        return JSON.parse(el.textContent);
    } catch {
        return null;
    }
}

export function initServiceTabs() {
    const root = document.querySelector('[data-services]');
    const titles = readJson('services-data');
    if (!root || !titles) return;

    const tabs = Array.from(root.querySelectorAll('[data-service-tab]'));
    const images = Array.from(root.querySelectorAll('[data-service-image]'));
    const line1 = root.querySelector('[data-service-title-1]');
    const line2 = root.querySelector('[data-service-title-2]');
    const number = root.querySelector('[data-service-number]');
    const panel = root.querySelector('#service-panel');

    const activate = (index) => {
        tabs.forEach((tab, i) => {
            const active = i === index;
            tab.setAttribute('aria-selected', active ? 'true' : 'false');
            tab.classList.toggle('bg-white', active);
            tab.classList.toggle('text-ink', active);
            tab.classList.toggle('text-white/85', !active);
            tab.classList.toggle('hover:bg-white/15', !active);
            tab.classList.toggle('hover:text-white', !active);
        });

        images.forEach((img, i) => {
            const active = i === index;
            img.classList.toggle('opacity-100', active);
            img.classList.toggle('opacity-0', !active);
            // Only the visible panel should be reachable by assistive tech.
            if (active) img.removeAttribute('aria-hidden');
            else img.setAttribute('aria-hidden', 'true');
        });

        line1.textContent = titles[index][0];
        line2.textContent = titles[index][1];
        number.textContent = `(${String(index + 1).padStart(2, '0')})`;
        panel?.setAttribute('aria-labelledby', `service-tab-${index}`);
    };

    tabs.forEach((tab, i) => tab.addEventListener('click', () => activate(i)));
}

export function initProcessTabs() {
    const root = document.querySelector('[data-process]');
    const steps = readJson('process-data');
    if (!root || !steps) return;

    const tabs = Array.from(root.querySelectorAll('[data-process-tab]'));
    const images = Array.from(root.querySelectorAll('[data-process-image]'));
    const number = root.querySelector('[data-process-number]');
    const title = root.querySelector('[data-process-title]');
    const body = root.querySelector('[data-process-body]');

    const activate = (index) => {
        tabs.forEach((tab, i) => {
            const active = i === index;
            const fill = tab.querySelector('[data-process-fill]');
            if (active) tab.setAttribute('aria-current', 'true');
            else tab.removeAttribute('aria-current');
            fill?.classList.toggle('w-full', active);
            fill?.classList.toggle('w-0', !active);
            fill?.classList.toggle('group-hover:w-1/3', !active);
        });

        images.forEach((img, i) => {
            const active = i === index;
            img.classList.toggle('opacity-100', active);
            img.classList.toggle('opacity-0', !active);
            if (active) img.removeAttribute('aria-hidden');
            else img.setAttribute('aria-hidden', 'true');
        });

        number.textContent = steps[index].number;
        title.textContent = steps[index].title;
        body.textContent = steps[index].body;
    };

    tabs.forEach((tab, i) => tab.addEventListener('click', () => activate(i)));
}
