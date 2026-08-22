import { subscribeToScroll, prefersReducedMotion } from './scroll-engine';

/**
 * The band where a picture grows between two words as the page scrolls.
 *
 * Taken from havenconstructions.com.au, which the client asked this to follow.
 * Their markup says exactly what it does, so this is built from the mechanism
 * rather than from a guess at it: the picture container is written out at
 * `transform:scale(0.2)` with the two words offset from their places —
 * `translateY(50px)` on the first and `translateY(-50px)` on the second — and
 * scroll drives all three back to rest. A stack of images sits inside the
 * container, one shown at a time.
 *
 *   <section data-scroll-gallery>          the travel: taller than the screen
 *     <div data-gallery-pin>               sticky, one screen tall
 *       <p data-gallery-word="0">          settles down into place
 *       <div data-gallery-frame>           scales 0.2 → 1
 *         <figure data-gallery-slide>      one of several, cross-faded
 *       <p data-gallery-word="1">          settles up into place
 *
 * The section's own height is the timeline. `data-scroll-gallery="180"` means
 * 180% of a viewport of travel; the sticky child holds still through it while
 * the numbers below are driven from how far into that travel the page is.
 *
 * Progressive enhancement, like everything else in this directory. Without
 * JavaScript — or under prefers-reduced-motion — the CSS leaves the frame at
 * its full size, the words at their places and the first slide visible, which
 * is the arrangement the frame draws. Nothing here is required to read it.
 */
const SCALE_FROM = 0.2;   // their own starting scale
const WORD_OFFSET = 50;   // px, their own — one word down, the other up

/** Ease-out, so the growth slows as the picture arrives rather than stopping. */
function ease(t) {
    return 1 - Math.pow(1 - t, 3);
}

export function initScrollGallery() {
    const sections = Array.from(document.querySelectorAll('[data-scroll-gallery]'));
    if (sections.length === 0 || prefersReducedMotion()) return;

    const bands = sections.map((section) => ({
        section,
        pin: section.querySelector('[data-gallery-pin]'),
        frame: section.querySelector('[data-gallery-frame]'),
        words: Array.from(section.querySelectorAll('[data-gallery-word]')),
        slides: Array.from(section.querySelectorAll('[data-gallery-slide]')),
    })).filter((band) => band.pin && band.frame);

    if (bands.length === 0) return;

    for (const band of bands) band.section.dataset.galleryReady = 'true';

    subscribeToScroll(({ vh }) => {
        for (const band of bands) {
            const rect = band.section.getBoundingClientRect();

            // Off screen: no reads, no writes, no work.
            if (rect.bottom < 0 || rect.top > vh) continue;

            /*
             * How far through the pinned travel we are. The pin is one screen
             * tall and sticks to the top, so the travel is the section's height
             * less that screen — measure against anything else and the picture
             * finishes growing before the band has finished passing.
             */
            const travel = Math.max(1, rect.height - vh);
            const p = Math.min(1, Math.max(0, -rect.top / travel));
            const eased = ease(p);

            band.frame.style.transform = `scale(${(SCALE_FROM + (1 - SCALE_FROM) * eased).toFixed(4)})`;

            /*
             * The words settle over the first half and then hold. Tying them to
             * the whole travel left them drifting while the picture was already
             * at full size, which reads as two animations rather than one.
             */
            const settled = ease(Math.min(1, p * 2));
            for (const word of band.words) {
                const direction = word.dataset.galleryWord === '0' ? 1 : -1;
                const offset = WORD_OFFSET * direction * (1 - settled);
                word.style.transform = `translate3d(0, ${offset.toFixed(2)}px, 0)`;
            }

            /*
             * The slides divide the travel between them and cross-fade. One
             * slide is the ordinary case and costs nothing: the index is always
             * 0 and it stays at full opacity throughout.
             */
            if (band.slides.length > 1) {
                const step = 1 / band.slides.length;
                const active = Math.min(band.slides.length - 1, Math.floor(p / step));
                for (const [i, slide] of band.slides.entries()) {
                    slide.style.opacity = i === active ? '1' : '0';
                }
            }
        }
    });
}
