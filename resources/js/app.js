import { initPreloader } from './motion/preloader';
import { initSmoothScroll } from './motion/smooth-scroll';
import { initReveal } from './motion/reveal';
import { initParallax, initHeroParallax } from './motion/parallax';
import { initSplitLines } from './motion/split-lines';
import { initProcessTabs } from './motion/tabs';
import { initMenu } from './motion/menu';
import { initFitText } from './motion/fit-text';

/**
 * Everything here is an enhancement over markup that already works. The page
 * renders, scrolls and submits without any of it — see the <noscript> block in
 * layouts/app.blade.php, which reverses the JavaScript-dependent styles.
 */
function boot() {
    initPreloader();
    initMenu();
    initProcessTabs();
    initFitText();

    // Split before reveal: splitting rewrites the element's children, and doing
    // it after a reveal would restart the transition from the hidden state.
    initSplitLines();
    initReveal();
    initParallax();
    initHeroParallax();
    initSmoothScroll();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
