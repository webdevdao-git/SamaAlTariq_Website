"use client";

import { useEffect } from "react";
import Lenis from "lenis";
import { pumpScroll, prefersReducedMotion } from "@/lib/scroll-engine";

/**
 * Lenis smooth scroll.
 *
 * The parallax effects only read as weight — the hero lagging behind, the
 * project cards drifting — when scroll position eases instead of jumping in
 * wheel-sized steps. Native scroll makes the same transforms look like jitter.
 *
 * Two things matter for not making this hostile:
 *  - it is skipped entirely under prefers-reduced-motion, falling back to
 *    native scroll;
 *  - anchor links still work, routed through Lenis so they ease rather than
 *    teleport (`scroll-behavior: smooth` is disabled in CSS while Lenis owns
 *    scrolling, since the two fight each other).
 */
export function SmoothScroll() {
  useEffect(() => {
    if (prefersReducedMotion()) return;

    const lenis = new Lenis({
      duration: 1.05,
      // Slight ease-out; long enough to feel weighted, short enough that the
      // page still responds immediately to a flick.
      easing: (t: number) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
      smoothWheel: true,
      // Touch devices already have momentum scrolling; overriding it there
      // feels laggy and breaks browser scroll handoff.
      syncTouch: false,
    });

    document.documentElement.classList.add("lenis-active");

    let frame = 0;
    const raf = (time: number) => {
      lenis.raf(time);
      pumpScroll();
      frame = requestAnimationFrame(raf);
    };
    frame = requestAnimationFrame(raf);

    // Route in-page anchors through Lenis so they ease to the target.
    const onClick = (event: MouseEvent) => {
      const anchor = (event.target as HTMLElement | null)?.closest?.(
        'a[href^="#"]',
      ) as HTMLAnchorElement | null;
      if (!anchor) return;

      const id = anchor.getAttribute("href");
      if (!id || id === "#") return;

      const target = document.querySelector(id);
      if (!target) return;

      event.preventDefault();
      lenis.scrollTo(target as HTMLElement, { offset: 0 });
    };

    document.addEventListener("click", onClick);

    return () => {
      document.removeEventListener("click", onClick);
      cancelAnimationFrame(frame);
      lenis.destroy();
      document.documentElement.classList.remove("lenis-active");
    };
  }, []);

  return null;
}
