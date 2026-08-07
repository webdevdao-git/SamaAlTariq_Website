"use client";

import {
  useEffect,
  useRef,
  useState,
  useSyncExternalStore,
  type ElementType,
} from "react";
import { reducedMotionStore, subscribeToScroll } from "@/lib/scroll-engine";

/**
 * Line-by-line text reveal.
 *
 * Each rendered line sits in a clipping mask and starts pushed down by its own
 * height, then slides up on a stagger — so the words appear to rise out from
 * behind the line above.
 *
 * Two details make this safe rather than clever:
 *
 * 1. Accessibility. The visible spans are `aria-hidden`, and the container
 *    carries the whole string as `aria-label`. Without that a screen reader
 *    announces the text as a pile of disconnected fragments.
 *
 * 2. Real lines, not authored ones. Where the browser breaks a line depends on
 *    the width it is given, so the split is measured after layout by grouping
 *    words that share an offsetTop, and re-measured on resize. Splitting on
 *    hardcoded line breaks would mask the wrong places at every other width.
 *
 * State is only ever set from inside a rAF callback — never synchronously in an
 * effect — so a measure or a reveal cannot cascade renders during commit.
 */

type SplitLinesProps = {
  text: string;
  as?: ElementType;
  className?: string;
  /** Delay between consecutive lines, ms. */
  stagger?: number;
  /** Delay before the first line, ms. */
  delay?: number;
};

export function SplitLines({
  text,
  as: Tag = "span",
  className = "",
  stagger = 90,
  delay = 0,
}: SplitLinesProps) {
  const ref = useRef<HTMLElement>(null);
  const [lines, setLines] = useState<string[] | null>(null);
  const [revealed, setRevealed] = useState(false);

  const reduced = useSyncExternalStore(
    reducedMotionStore.subscribe,
    reducedMotionStore.getSnapshot,
    reducedMotionStore.getServerSnapshot,
  );

  // Measure where the browser actually wraps, then regroup into lines.
  useEffect(() => {
    const host = ref.current;
    if (!host || reduced) return;

    let raf = 0;
    let timer = 0;

    const measure = () => {
      const probe = document.createElement("span");
      probe.setAttribute("aria-hidden", "true");
      probe.style.cssText =
        "display:block;visibility:hidden;position:absolute;inset:0";

      for (const word of text.split(/\s+/).filter(Boolean)) {
        const span = document.createElement("span");
        span.textContent = word;
        span.style.display = "inline-block";
        probe.append(span, document.createTextNode(" "));
      }

      host.append(probe);

      const grouped: string[] = [];
      let top: number | null = null;

      for (const span of probe.querySelectorAll("span")) {
        const y = (span as HTMLElement).offsetTop;
        if (top === null || Math.abs(y - top) > 2) {
          top = y;
          grouped.push(span.textContent ?? "");
        } else {
          grouped[grouped.length - 1] += ` ${span.textContent ?? ""}`;
        }
      }

      probe.remove();
      setLines(grouped.length > 0 ? grouped : [text]);
    };

    // Deferred a frame: layout must have settled, and this keeps the state
    // update out of the effect's synchronous body.
    raf = requestAnimationFrame(measure);

    // Re-split when the box changes width; height changes are our own doing.
    let width = host.getBoundingClientRect().width;
    const observer = new ResizeObserver(([entry]) => {
      const next = entry.contentRect.width;
      if (Math.abs(next - width) < 1) return;
      width = next;
      window.clearTimeout(timer);
      timer = window.setTimeout(measure, 120);
    });
    observer.observe(host);

    return () => {
      cancelAnimationFrame(raf);
      window.clearTimeout(timer);
      observer.disconnect();
    };
  }, [text, reduced]);

  // Reveal once the block reaches the viewport. Uses the shared scroll loop
  // rather than IntersectionObserver, which can skip a block on a jump-scroll
  // and leave it stuck at translateY(105%).
  useEffect(() => {
    const host = ref.current;
    if (!host || revealed || reduced) return;

    const unsubscribe = subscribeToScroll(({ vh }) => {
      if (host.getBoundingClientRect().top >= vh * 0.92) return;
      setRevealed(true);
      unsubscribe();
    });

    return unsubscribe;
  }, [revealed, reduced]);

  // Before measurement, and whenever motion is reduced, render the plain
  // string — the text is never withheld waiting on JavaScript. The ref stays
  // attached in this branch: the measure effect reads it, so detaching it here
  // would mean the split never happens and the fallback became permanent.
  if (reduced || lines === null) {
    return (
      <Tag ref={ref} className={className}>
        {text}
      </Tag>
    );
  }

  return (
    <Tag ref={ref} className={className} aria-label={text}>
      {lines.map((line, i) => (
        <span key={`${i}-${line}`} aria-hidden="true" className="block overflow-clip">
          <span
            className="block will-change-transform"
            style={{
              transform: revealed ? "translateY(0)" : "translateY(105%)",
              transition: `transform 1000ms cubic-bezier(0.16, 1, 0.3, 1) ${
                delay + i * stagger
              }ms`,
            }}
          >
            {line}
          </span>
        </span>
      ))}
    </Tag>
  );
}
