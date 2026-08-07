"use client";

import { useEffect, useState } from "react";
import { LogoMark } from "@/components/logo";

/**
 * Entry curtain — a full-screen panel that wipes upward off the page, animated
 * with `clip-path: inset(0 0 100%)` so the content beneath is revealed rather
 * than faded over.
 *
 * The curtain is server-rendered and visible by default. Mounting it from an
 * effect instead would mean the SSR page paints first and the curtain drops on
 * top a moment later — the opposite of what a preloader is for. Because the
 * markup is identical on both sides there is nothing for hydration to disagree
 * about; JavaScript only ever flips `data-lifting`.
 *
 * A loading screen is a real cost — it stands between a visitor and the enquiry
 * form — so it is bounded:
 *   - it lifts as soon as fonts are ready, capped at ~1.6s, so a slow
 *     connection never leaves someone staring at a blank panel;
 *   - `prefers-reduced-motion` hides it in CSS, before any JS runs;
 *   - a <noscript> rule hides it too, so a failed bundle cannot leave the page
 *     permanently covered.
 *
 * Scroll is locked only while the curtain is up.
 */

const MAX_WAIT_MS = 1600;
const WIPE_MS = 900;

export function Preloader() {
  const [lifting, setLifting] = useState(false);
  const [gone, setGone] = useState(false);

  useEffect(() => {
    let lifted = false;
    let removeTimer = 0;

    // Scroll locking is handled in CSS off `data-lifting` (see globals.css),
    // so flipping this one attribute both wipes the curtain and releases scroll.
    const lift = () => {
      if (lifted) return;
      lifted = true;
      setLifting(true);
      removeTimer = window.setTimeout(() => setGone(true), WIPE_MS);
    };

    // Whichever comes first: fonts settled, or the cap. Both are async, so no
    // state is set synchronously inside this effect.
    const cap = window.setTimeout(lift, MAX_WAIT_MS);
    const fonts = document.fonts?.ready ?? Promise.resolve();
    let settle = 0;
    fonts.then(() => {
      settle = window.setTimeout(lift, 420);
    });

    return () => {
      window.clearTimeout(cap);
      window.clearTimeout(settle);
      window.clearTimeout(removeTimer);
    };
  }, []);

  if (gone) return null;

  return (
    <div aria-hidden data-lifting={lifting} className="intro-curtain">
      {/*
        The mark drifts up slightly ahead of the curtain, so the panel reads as
        a sheet being lifted rather than one flat layer sliding away.
      */}
      <div className="intro-curtain__mark">
        <LogoMark width={96} className="h-auto w-[clamp(56px,6vw,96px)]" />
      </div>
    </div>
  );
}
