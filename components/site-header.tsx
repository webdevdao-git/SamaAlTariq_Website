"use client";

import { useEffect, useState } from "react";
import { LogoLockup } from "@/components/logo";
import { MenuIcon, ArrowRightThin } from "@/components/icons";
import { nav, social } from "@/content/site";

/**
 * Figma places MENU / lockup / ENQUIRE in a 1568×123 row at y=44 over the hero.
 * MENU has no designed panel, so it opens a full-screen overlay built from the
 * footer navigation — the only nav list in the file.
 */
export function SiteHeader() {
  const [open, setOpen] = useState(false);

  useEffect(() => {
    document.body.style.overflow = open ? "hidden" : "";
    return () => {
      document.body.style.overflow = "";
    };
  }, [open]);

  useEffect(() => {
    if (!open) return;
    const onKey = (e: KeyboardEvent) => e.key === "Escape" && setOpen(false);
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, [open]);

  return (
    <>
      <header className="absolute inset-x-0 top-0 z-40 pt-[clamp(1.25rem,2.55vw,44px)]">
        <div className="shell flex items-center justify-between gap-4">
          <button
            type="button"
            onClick={() => setOpen(true)}
            aria-expanded={open}
            aria-label="Open navigation menu"
            className="group flex shrink-0 items-center gap-1 text-white transition-opacity hover:opacity-70"
          >
            <MenuIcon className="h-[clamp(20px,1.62vw,28px)] w-[clamp(20px,1.62vw,28px)]" />
            <span className="text-fluid-body font-semibold uppercase">Menu</span>
          </button>

          <a href="#top" aria-label="Sama Al Tariq — home" className="shrink-0">
            <LogoLockup />
          </a>

          <a
            href="#contact"
            className="shrink-0 text-fluid-body font-semibold text-white uppercase underline decoration-solid underline-offset-4 transition-opacity hover:opacity-70"
          >
            Enquire
          </a>
        </div>
      </header>

      {/* Full-screen navigation overlay */}
      <div
        className={`fixed inset-0 z-50 bg-night/97 backdrop-blur-sm transition-opacity duration-500 ${
          open ? "opacity-100" : "pointer-events-none opacity-0"
        }`}
        role="dialog"
        aria-modal="true"
        aria-label="Site navigation"
      >
        <div className="shell flex h-full flex-col py-[clamp(1.25rem,2.55vw,44px)]">
          <div className="flex items-center justify-between">
            <span className="text-fluid-body font-semibold uppercase text-white/60">
              Navigation
            </span>
            <button
              type="button"
              onClick={() => setOpen(false)}
              className="text-fluid-body font-semibold uppercase text-white transition-opacity hover:opacity-70"
            >
              Close
            </button>
          </div>

          <nav className="flex flex-1 flex-col justify-center gap-2">
            {nav.map((item, i) => (
              <a
                key={item.href}
                href={item.href}
                onClick={() => setOpen(false)}
                className="display group flex w-fit items-center gap-6 text-[clamp(2rem,5vw,86px)] uppercase text-white transition-colors hover:text-teal"
                style={{
                  transitionDelay: open ? `${120 + i * 45}ms` : "0ms",
                  opacity: open ? 1 : 0,
                  transform: open ? "none" : "translateY(18px)",
                  transitionProperty: "opacity, transform, color",
                  transitionDuration: "600ms",
                  transitionTimingFunction: "cubic-bezier(0.16,1,0.3,1)",
                }}
              >
                {item.label}
                <ArrowRightThin className="w-7 shrink-0 opacity-0 transition-opacity duration-300 group-hover:opacity-100" />
              </a>
            ))}
          </nav>

          <div className="flex flex-wrap gap-x-10 gap-y-3">
            {social.map((s) => (
              <a
                key={s.label}
                href={s.href}
                target="_blank"
                rel="noreferrer noopener"
                className="text-fluid-sm text-white/60 transition-colors hover:text-white"
              >
                {s.label}
              </a>
            ))}
          </div>
        </div>
      </div>
    </>
  );
}
