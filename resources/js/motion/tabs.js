/**
 * Our Process — a switcher over the numbered steps.
 *
 * Figma draws only step 01; those are states of one component, so it is built
 * as a stepper. Content comes from a JSON script tag rendered by the Blade
 * view, so the copy stays in config/site.php and is never duplicated here.
 *
 * (Our Expertise used to live here too. It is now six scrolling panels, each
 * rendering its own active pill, so it needs no JavaScript at all.)
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
