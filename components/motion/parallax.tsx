"use client";

import { useEffect, useRef, type ReactNode } from "react";
import {
  prefersReducedMotion,
  subscribeToScroll,
  viewportProgress,
} from "@/lib/scroll-engine";

/**
 * Vertical drift tied to how far an element has travelled through the viewport.
 *
 * `distance` is the total px swept from entry to exit, split either side of
 * centre — so the element sits at its designed position when it is centred,
 * and only ever offset by half the distance. That keeps a card from appearing
 * to sit in the wrong place in a screenshot or on a short screen.
 *
 * Writes only `transform`, from the shared rAF loop.
 */
export function Parallax({
  children,
  distance = 80,
  className = "",
}: {
  children: ReactNode;
  distance?: number;
  className?: string;
}) {
  const ref = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const node = ref.current;
    if (!node || prefersReducedMotion()) return;

    return subscribeToScroll(({ vh }) => {
      const rect = node.getBoundingClientRect();
      // Skip anything off screen — no layout thrash for the whole page.
      if (rect.bottom < -vh || rect.top > vh * 2) return;

      const offset = (viewportProgress(rect, vh) - 0.5) * -distance;
      node.style.transform = `translate3d(0, ${offset.toFixed(2)}px, 0)`;
    });
  }, [distance]);

  return (
    <div ref={ref} className={className} style={{ willChange: "transform" }}>
      {children}
    </div>
  );
}
