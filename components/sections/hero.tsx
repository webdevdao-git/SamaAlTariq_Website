import Image from "next/image";
import { SiteHeader } from "@/components/site-header";
import { ArrowRightThin } from "@/components/icons";
import { hero } from "@/content/site";

/**
 * Figma: frame 1195:3, 1728×1117.
 * Photo fills the frame under two stacked gradients (a bottom-up black 47%
 * wash and a flat 25% black), the header sits at the top, a hairline rule at
 * y=531 separates a three-column intro row, and the display type occupies the
 * lower third: BUILDING / WITH PRECISION on one line, FUTURE indented below.
 */
export function Hero() {
  return (
    <section
      id="top"
      className="relative flex min-h-[100svh] flex-col overflow-hidden bg-night"
    >
      <Image
        src={hero.image}
        alt="Double-height majlis with arched windows overlooking a lake"
        fill
        priority
        sizes="100vw"
        className="object-cover"
      />
      <div
        aria-hidden
        className="absolute inset-0"
        style={{
          backgroundImage:
            "linear-gradient(0deg, rgba(0,0,0,0.47) 0%, rgba(102,102,102,0) 116.97%), linear-gradient(90deg, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0.25) 100%)",
        }}
      />

      <SiteHeader />

      <div className="relative z-10 mt-auto flex flex-col gap-[clamp(2.5rem,17vh,17rem)] pb-[clamp(2.5rem,6vh,4.5rem)] pt-[36vh]">
        {/* Intro row — hairline rule + three columns */}
        <div className="shell">
          <div className="border-t border-white/25 pt-[clamp(1.25rem,1.5vw,26px)]">
            <div className="grid gap-6 text-white md:grid-cols-12 md:items-start">
              <p className="text-fluid-body font-semibold md:col-span-3 md:max-w-[170px]">
                {hero.eyebrow}
              </p>
              <p className="text-fluid-lead font-medium md:col-span-6 md:max-w-[670px]">
                {hero.intro}
              </p>
              <a
                href={hero.cta.href}
                className="group inline-flex items-center gap-1 text-fluid-sm font-medium md:col-span-3 md:justify-end"
              >
                {hero.cta.label}
                <ArrowRightThin className="w-[clamp(20px,1.62vw,28px)] transition-transform duration-300 group-hover:translate-x-1" />
              </a>
            </div>
          </div>
        </div>

        {/* Display type */}
        <h1 className="shell display text-fluid-hero uppercase text-white">
          <span className="flex flex-wrap items-baseline justify-between gap-x-6">
            <span className="block">{hero.words.first}</span>
            <span className="block">{hero.words.second}</span>
          </span>
          <span className="mt-[0.06em] block pl-[max(0px,calc(27%-var(--spacing-gutter)))]">
            {hero.words.third}
          </span>
        </h1>
      </div>
    </section>
  );
}
