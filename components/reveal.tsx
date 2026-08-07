"use client";

import {
  useEffect,
  useRef,
  useState,
  type ElementType,
  type ReactNode,
} from "react";

/**
 * Scroll-reveal for the long-form sections.
 *
 * All registered nodes share one rAF-throttled pass instead of one
 * IntersectionObserver each. IntersectionObserver samples threshold crossings,
 * so a jump-scroll — clicking an anchor in the menu, restoring a scroll
 * position, a fast trackpad fling — can skip a block entirely and leave it
 * stuck at opacity 0. A plain "is its top above the viewport bottom" test
 * cannot miss, because it reads the resting position rather than the crossing.
 *
 * The hidden state itself is scoped to `.js` in globals.css, so this only ever
 * animates content that JavaScript is present to reveal.
 */

type Entry = { node: HTMLElement; show: () => void };

const registry = new Set<Entry>();
let frame = 0;
let listening = false;

function flush() {
  frame = 0;
  const limit = window.innerHeight * 0.94;
  for (const entry of registry) {
    if (entry.node.getBoundingClientRect().top < limit) {
      entry.show();
      registry.delete(entry);
    }
  }
  if (registry.size === 0) stopListening();
}

function schedule() {
  if (frame) return;
  frame = requestAnimationFrame(flush);
}

function startListening() {
  if (listening) return;
  listening = true;
  window.addEventListener("scroll", schedule, { passive: true });
  window.addEventListener("resize", schedule, { passive: true });
}

function stopListening() {
  if (!listening) return;
  listening = false;
  window.removeEventListener("scroll", schedule);
  window.removeEventListener("resize", schedule);
}

function register(entry: Entry) {
  registry.add(entry);
  startListening();
  schedule();
  return () => {
    registry.delete(entry);
    if (registry.size === 0) stopListening();
  };
}

export function Reveal({
  children,
  as: Tag = "div",
  className = "",
  delay = 0,
}: {
  children: ReactNode;
  as?: ElementType;
  className?: string;
  delay?: number;
}) {
  const ref = useRef<HTMLElement>(null);
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    const node = ref.current;
    if (!node) return;
    return register({ node, show: () => setVisible(true) });
  }, []);

  return (
    <Tag
      ref={ref}
      className={`reveal ${visible ? "is-visible" : ""} ${className}`}
      style={delay ? { transitionDelay: `${delay}ms` } : undefined}
    >
      {children}
    </Tag>
  );
}
