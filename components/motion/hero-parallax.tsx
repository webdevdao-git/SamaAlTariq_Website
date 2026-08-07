"use client";

import { useEffect, useRef, type ReactNode } from "react";
import { prefersReducedMotion, subscribeToScroll } from "@/lib/scroll-engine";

/**
 * The hero's two scroll behaviours, measured off the reference site:
 *
 *   section  translateY = scrollY × 0.25   — the hero sinks at a quarter of
 *                                            scroll speed, so the section below
 *                                            appears to slide up over it
 *   media    scale      = 1 + progress × 0.1  where progress = scrollY / vh
 *                                            — a slow push-in as it leaves
 *
 * Both stop once the hero is fully past, so nothing is recalculated for the
 * remaining 9,000px of page.
 */
export function HeroParallax({
  children,
  media,
  className = "",
}: {
  children: ReactNode;
  media: ReactNode;
  className?: string;
}) {
  const section = useRef<HTMLDivElement>(null);
  const mediaRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const sectionNode = section.current;
    const mediaNode = mediaRef.current;
    if (!sectionNode || !mediaNode || prefersReducedMotion()) return;

    return subscribeToScroll(({ y, vh }) => {
      const height = sectionNode.offsetHeight;
      if (y > height + vh) return; // hero is long gone

      const clamped = Math.max(0, y);
      sectionNode.style.transform = `translate3d(0, ${(clamped * 0.25).toFixed(2)}px, 0)`;

      const scale = 1 + Math.min(clamped / vh, 1.4) * 0.1;
      mediaNode.style.transform = `scale(${scale.toFixed(4)})`;
    });
  }, []);

  return (
    <div ref={section} className={className} style={{ willChange: "transform" }}>
      <div
        ref={mediaRef}
        className="absolute inset-0 -z-10"
        style={{ willChange: "transform", transformOrigin: "center" }}
      >
        {media}
      </div>
      {children}
    </div>
  );
}
