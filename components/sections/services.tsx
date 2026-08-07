"use client";

import { useState } from "react";
import Image from "next/image";
import { Reveal } from "@/components/reveal";
import { ArrowRightPill } from "@/components/icons";
import { services } from "@/content/site";

/**
 * Figma: frame 1224:548, 1728×2510.
 * The file stacks two flattened 1728×980 panels that differ only in which tab
 * pill is active and which headline shows — i.e. two states of one tabbed
 * switcher, not two separate blocks. It is built here as the switcher: six
 * tabs (all six labels are in the design) over a full-bleed photo panel.
 */
export function Services() {
  const [active, setActive] = useState(0);
  const panel = services.items[active];

  return (
    <section id="services" className="bg-white pt-[clamp(3rem,4.63vw,80px)]">
      <div className="shell">
        <Reveal className="flex flex-col gap-[clamp(1rem,3vw,52px)] pb-[clamp(2.5rem,5.79vw,100px)] lg:flex-row lg:items-start lg:justify-between">
          <p className="shrink-0 text-fluid-label font-medium text-teal">
            {services.label}
          </p>
          <h2 className="display max-w-[922px] text-fluid-h2 leading-[1.3] text-ink lg:w-[59%]">
            {services.heading}
          </h2>
        </Reveal>
      </div>

      <Reveal className="relative isolate w-full overflow-hidden">
        <div className="relative min-h-[clamp(420px,56.7vw,980px)] w-full">
          {services.items.map((item, i) => (
            <Image
              key={item.image}
              src={item.image}
              alt=""
              fill
              sizes="100vw"
              aria-hidden={i !== active}
              className={`object-cover transition-opacity duration-[900ms] ease-out ${
                i === active ? "opacity-100" : "opacity-0"
              }`}
            />
          ))}
          <div
            aria-hidden
            className="absolute inset-0 bg-[linear-gradient(180deg,rgba(0,0,0,0.55)_0%,rgba(0,0,0,0.15)_45%,rgba(0,0,0,0.35)_100%)]"
          />

          <div className="relative flex min-h-[clamp(420px,56.7vw,980px)] flex-col gap-[clamp(1.5rem,4.7vw,80px)] px-[var(--spacing-gutter)] py-[clamp(2rem,5.5vw,96px)]">
            {/* Tab pills */}
            <div
              role="tablist"
              aria-label="Our areas of expertise"
              className="-mx-[var(--spacing-gutter)] flex snap-x gap-1 overflow-x-auto px-[var(--spacing-gutter)] pb-1 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
            >
              {services.items.map((item, i) => (
                <button
                  key={item.tab}
                  type="button"
                  role="tab"
                  id={`service-tab-${i}`}
                  aria-selected={i === active}
                  aria-controls="service-panel"
                  onClick={() => setActive(i)}
                  className={`shrink-0 snap-start rounded-full px-[clamp(0.9rem,1.3vw,22px)] py-[clamp(0.45rem,0.7vw,12px)] text-[clamp(0.75rem,0.93vw,16px)] font-medium whitespace-nowrap transition-colors duration-300 ${
                    i === active
                      ? "bg-white text-ink"
                      : "text-white/85 hover:bg-white/15 hover:text-white"
                  }`}
                >
                  {item.tab}
                </button>
              ))}
            </div>

            {/* Active headline */}
            <h3
              id="service-panel"
              role="tabpanel"
              aria-labelledby={`service-tab-${active}`}
              className="display text-[clamp(1.5rem,2.55vw,44px)] uppercase text-white"
            >
              <span className="block">
                {panel.title[0]}
                <sup className="ml-2 align-super text-[0.42em] tracking-wide">
                  ({String(active + 1).padStart(2, "0")})
                </sup>
              </span>
              <span className="block">{panel.title[1]}</span>
            </h3>
          </div>
        </div>
      </Reveal>

      <div className="flex justify-center py-[clamp(2.5rem,4.63vw,80px)]">
        <a href={services.cta.href} className="pill group">
          {services.cta.label}
          <ArrowRightPill className="transition-transform duration-300 group-hover:translate-x-0.5" />
        </a>
      </div>
    </section>
  );
}
