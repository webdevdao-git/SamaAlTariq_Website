"use client";

import { useState } from "react";
import Image from "next/image";
import { Reveal } from "@/components/reveal";
import { process } from "@/content/site";

/**
 * Figma: frame 1226:731, 1728×1019.
 * Two 752px columns: "OUR PROCESS" display heading top-left with the numbered
 * step pinned to the bottom of that column, and a full-height image on the
 * right. Only step 01 is drawn in the file; the remaining steps live in
 * content/site.ts and are switched with the numbered rail below the copy.
 */
export function Process() {
  const [active, setActive] = useState(0);
  const step = process.steps[active];

  return (
    <section id="process" className="bg-white py-[clamp(3.5rem,5.79vw,100px)]">
      <div className="shell">
        <div className="grid gap-[clamp(2rem,3.7vw,64px)] lg:grid-cols-2">
          <Reveal className="flex flex-col justify-between gap-[clamp(2.5rem,6vw,104px)]">
            <h2 className="display text-fluid-section uppercase text-ink">
              {process.heading.map((line) => (
                <span key={line} className="block">
                  {line}
                </span>
              ))}
            </h2>

            <div>
              {/* Step rail */}
              <div className="mb-[clamp(1.5rem,2.3vw,40px)] flex gap-2">
                {process.steps.map((s, i) => (
                  <button
                    key={s.number}
                    type="button"
                    onClick={() => setActive(i)}
                    aria-label={`Step ${s.number}: ${s.title}`}
                    aria-current={i === active}
                    className="group relative h-[3px] flex-1 overflow-hidden rounded-full bg-black/10"
                  >
                    <span
                      className={`absolute inset-y-0 left-0 rounded-full bg-teal transition-[width] duration-500 ${
                        i === active ? "w-full" : "w-0 group-hover:w-1/3"
                      }`}
                    />
                  </button>
                ))}
              </div>

              <div key={step.number} className="reveal is-visible">
                <p className="display text-[clamp(1.35rem,1.85vw,32px)] text-teal">
                  {step.number}
                </p>
                <h3 className="display mt-[clamp(0.75rem,1.16vw,20px)] text-[clamp(1.15rem,1.85vw,32px)] text-ink">
                  {step.title}
                </h3>
                <p className="mt-[clamp(0.5rem,0.7vw,12px)] max-w-[560px] text-fluid-body font-medium text-ink-muted">
                  {step.body}
                </p>
              </div>
            </div>
          </Reveal>

          <Reveal delay={120} className="relative">
            <div className="relative aspect-[752/819] w-full overflow-hidden bg-mist">
              {process.steps.map((s, i) => (
                <Image
                  key={s.image}
                  src={s.image}
                  alt=""
                  fill
                  sizes="(max-width: 1024px) 100vw, 45vw"
                  aria-hidden={i !== active}
                  className={`object-cover transition-opacity duration-[900ms] ease-out ${
                    i === active ? "opacity-100" : "opacity-0"
                  }`}
                />
              ))}
            </div>
          </Reveal>
        </div>
      </div>
    </section>
  );
}
