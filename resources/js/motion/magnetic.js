import { prefersReducedMotion } from './scroll-engine';

/**
 * Buttons that lean toward the pointer.
 *
 * Within a button's own box the pointer pulls it a fraction of the way toward
 * itself; leaving releases it back on a spring. The pull is a share of the
 * offset from the button's centre, capped, so a wide pill and a small icon
 * both move a few pixels rather than in proportion to their size.
 *
 * Written to the individual `translate` property, not `transform`. Several of
 * these elements already own their transform — the pill lifts on hover, the
 * hero CTA's arrow slides — and `translate` composes with it instead of
 * replacing it, so neither effect has to know about the other.
 */
const SELECTOR = '.pill, [data-magnetic], [data-menu-open], header a, footer a[aria-label]';
const PULL = 0.32;
const MAX = 14;

export function initMagnetic() {
    if (prefersReducedMotion()) return;
    if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

    const nodes = Array.from(document.querySelectorAll(SELECTOR));

    for (const node of nodes) {
        const state = { x: 0, y: 0, tx: 0, ty: 0, frame: 0 };

        const render = () => {
            state.x += (state.tx - state.x) * 0.2;
            state.y += (state.ty - state.y) * 0.2;
            node.style.translate = `${state.x.toFixed(2)}px ${state.y.toFixed(2)}px`;

            const settled = Math.abs(state.tx - state.x) < 0.05 && Math.abs(state.ty - state.y) < 0.05;
            if (settled) {
                state.frame = 0;
                if (state.tx === 0 && state.ty === 0) node.style.translate = '';
                return;
            }
            state.frame = requestAnimationFrame(render);
        };

        const wake = () => {
            if (!state.frame) state.frame = requestAnimationFrame(render);
        };

        node.addEventListener('pointermove', (event) => {
            if (event.pointerType !== 'mouse') return;

            // The box without the current pull in it, or the button would
            // chase its own displaced centre.
            const rect = node.getBoundingClientRect();
            const cx = rect.left - state.x + rect.width / 2;
            const cy = rect.top - state.y + rect.height / 2;

            const clamp = (value) => Math.max(-MAX, Math.min(MAX, value));
            state.tx = clamp((event.clientX - cx) * PULL);
            state.ty = clamp((event.clientY - cy) * PULL);
            wake();
        });

        node.addEventListener('pointerleave', () => {
            state.tx = 0;
            state.ty = 0;
            wake();
        });
    }
}
