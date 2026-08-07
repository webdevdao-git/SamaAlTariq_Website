"use client";

/**
 * One rAF loop for every scroll-driven effect on the page.
 *
 * The hero sink, the hero zoom, the project parallax and the reveal observer
 * all need the same two numbers each frame — scrollY and viewport height. Each
 * running its own listener would mean four reads of `scrollY` per frame and
 * four chances to force a layout at a different moment. Here the values are
 * read once, then handed to every subscriber, and the loop only runs while
 * something is subscribed.
 *
 * Effects receive plain numbers and are expected to write only `transform`
 * (and `opacity`), so the browser stays on the compositor.
 */

export type ScrollState = {
  /** Current scroll position in px. */
  y: number;
  /** Viewport height in px. */
  vh: number;
};

type Subscriber = (state: ScrollState) => void;

const subscribers = new Set<Subscriber>();
let frame = 0;
let listening = false;

const state: ScrollState = { y: 0, vh: 0 };

function tick() {
  frame = 0;
  state.y = window.scrollY;
  state.vh = window.innerHeight;
  for (const fn of subscribers) fn(state);
}

function schedule() {
  if (frame) return;
  frame = requestAnimationFrame(tick);
}

function start() {
  if (listening) return;
  listening = true;
  window.addEventListener("scroll", schedule, { passive: true });
  window.addEventListener("resize", schedule, { passive: true });
}

function stop() {
  if (!listening) return;
  listening = false;
  window.removeEventListener("scroll", schedule);
  window.removeEventListener("resize", schedule);
  if (frame) {
    cancelAnimationFrame(frame);
    frame = 0;
  }
}

export function subscribeToScroll(fn: Subscriber): () => void {
  subscribers.add(fn);
  start();
  schedule();

  return () => {
    subscribers.delete(fn);
    if (subscribers.size === 0) stop();
  };
}

/** Lenis drives scroll off rAF, so it pushes frames in rather than listening. */
export function pumpScroll() {
  schedule();
}

export function prefersReducedMotion(): boolean {
  return (
    typeof window !== "undefined" &&
    window.matchMedia("(prefers-reduced-motion: reduce)").matches
  );
}

const REDUCED_QUERY = "(prefers-reduced-motion: reduce)";

/**
 * Subscribe/getSnapshot pair for useSyncExternalStore, so components can read
 * the reduced-motion preference as a render value instead of setting state
 * from an effect. It also stays correct if the visitor flips the OS setting
 * while the page is open.
 */
export const reducedMotionStore = {
  subscribe(onChange: () => void) {
    const mq = window.matchMedia(REDUCED_QUERY);
    mq.addEventListener("change", onChange);
    return () => mq.removeEventListener("change", onChange);
  },
  getSnapshot() {
    return window.matchMedia(REDUCED_QUERY).matches;
  },
  /** The server cannot know the preference; assume motion is allowed. */
  getServerSnapshot() {
    return false;
  },
};

/** 0 → 1 as `rect` travels from just below the viewport to just above it. */
export function viewportProgress(rect: DOMRect, vh: number): number {
  const span = vh + rect.height;
  const travelled = vh - rect.top;
  return Math.min(1, Math.max(0, travelled / span));
}
