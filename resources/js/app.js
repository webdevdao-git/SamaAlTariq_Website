import { initPreloader } from './motion/preloader';
import { initSmoothScroll } from './motion/smooth-scroll';
import { initReveal } from './motion/reveal';
import { initParallax, initHeroParallax, initMediaDrift } from './motion/parallax';
import { initSplitLines } from './motion/split-lines';
import { initProcessScroll } from './motion/process';
import { initMenu } from './motion/menu';
import { initFitText } from './motion/fit-text';
import { initProjectView } from './motion/project-view';
import { initProjectHero } from './motion/project-hero';
import { initLightbox } from './motion/lightbox';
import { initGrowScene } from './motion/grow-scene';
import { initRevealScene } from './motion/reveal-scene';
import { initServiceSlides } from './motion/service-slides';
import { initScrollGallery, initSlideCycle } from './motion/scroll-gallery';
import { initTextFill } from './motion/text-fill';
import { initFieldSelect } from './field-select';

/**
 * '#top' is the hero's id, and the top of the page is where a load already
 * lands — so the fragment never has to stay in the address bar.
 *
 * Nav::href() writes the landing-page link as a bare path now, but that only
 * covers the links this site draws. An old link, a bookmark, a shared URL or
 * the footer's "Back to top" under prefers-reduced-motion — where
 * smooth-scroll.js is not running to intercept the anchor — all still arrive
 * with it. The jump has already happened by the time this runs, so clearing
 * it moves nothing; replaceState is what keeps it off the history stack, so
 * Back still goes where the visitor came from.
 */
function tidyTopHash() {
    if (window.location.hash !== '#top') return;

    window.history.replaceState(null, '', window.location.pathname + window.location.search);
}

/**
 * Everything here is an enhancement over markup that already works. The page
 * renders, scrolls and submits without any of it — see the <noscript> block in
 * layouts/app.blade.php, which reverses the JavaScript-dependent styles.
 */
function boot() {
    tidyTopHash();
    window.addEventListener('hashchange', tidyTopHash);

    initPreloader();
    initMenu();
    initProcessScroll();
    initFitText();
    initFieldSelect();
    initProjectView();
    initProjectHero();
    initLightbox();
    initGrowScene();
    initRevealScene();
    initServiceSlides();
    initScrollGallery();
    initSlideCycle();
    initTextFill();

    // Split before reveal: splitting rewrites the element's children, and doing
    // it after a reveal would restart the transition from the hidden state.
    initSplitLines();
    initReveal();
    initParallax();
    initMediaDrift();
    initHeroParallax();
    initSmoothScroll();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
