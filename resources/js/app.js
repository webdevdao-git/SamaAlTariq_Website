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
import { initPageTransition } from './motion/page-transition';
import { initMagnetic } from './motion/magnetic';
import { initTilt } from './motion/tilt';
import { initCountUp } from './motion/count-up';
import { initMarqueeBoost } from './motion/marquee-boost';
import { initHeroGl } from './motion/hero-gl';
import { initProjectTower } from './motion/project-tower';
import { initScrollFx } from './motion/scroll-fx';
import { initScenes } from './motion/scenes';
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

    // The pointer- and navigation-driven layer. Last, so the elements it
    // measures have already been split, fitted and placed by everything above.
    initPageTransition();
    initMagnetic();
    initTilt();
    initCountUp();
    initMarqueeBoost();
    initHeroGl();
    initProjectTower();
    initScrollFx();

    // Last: the scenes pin and measure sections, so every other module has to
    // have finished laying the page out first.
    /*
     * The 3D projects stage is the one heavy module — Three.js — so it is
     * fetched only by the page that has the stage, and only when it can run.
     * The section is switched over synchronously so the rest of the scenes
     * lay out against the stage, not the grid it replaces.
     */
    const stage = document.querySelector('#projects [data-stage]');
    if (stage && stageCanRun()) {
        document.querySelector('#projects').classList.add('has-stage');
        stage.hidden = false;
        import('./motion/project-stage').then(({ initProjectStage }) => initProjectStage());
    }

    initScenes();
}

function stageCanRun() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return false;
    try {
        const canvas = document.createElement('canvas');
        return Boolean(canvas.getContext('webgl2') || canvas.getContext('webgl'));
    } catch {
        return false;
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
