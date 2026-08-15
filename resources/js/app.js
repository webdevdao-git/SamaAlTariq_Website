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
import { initFieldSelect } from './field-select';

/**
 * Everything here is an enhancement over markup that already works. The page
 * renders, scrolls and submits without any of it — see the <noscript> block in
 * layouts/app.blade.php, which reverses the JavaScript-dependent styles.
 */
function boot() {
    initPreloader();
    initMenu();
    initProcessScroll();
    initFitText();
    initFieldSelect();
    initProjectView();
    initProjectHero();
    initLightbox();
    initGrowScene();

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
