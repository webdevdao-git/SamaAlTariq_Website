import Image from "next/image";
import { Reveal } from "@/components/reveal";
import { ArrowRightPill } from "@/components/icons";
import { precision } from "@/content/site";

/**
 * Figma: frame 1226:1150, 1728×922, background #161616.
 * A faint grid of hairlines (two verticals at ~30% and ~72%, one horizontal at
 * y=786), a 456×664 tower photo centred at 64% opacity, the word PRECISION at
 * 128px pinned bottom-left, and a copy block in the right column.
 */
export function Precision() {
  return (
    <section className="relative isolate overflow-hidden bg-night py-[clamp(3rem,5.79vw,100px)]">
      {/* Hairline grid */}
      <div aria-hidden className="pointer-events-none absolute inset-0">
        <span className="absolute inset-y-0 left-[30.3%] w-px bg-white/10" />
        <span className="absolute inset-y-0 left-[71.5%] w-px bg-white/10" />
        <span className="absolute inset-x-0 top-[85.3%] h-px bg-white/10" />
      </div>

      {/* Centred tower */}
      <div
        aria-hidden
        className="pointer-events-none absolute left-1/2 top-[13.3%] h-[72%] w-[clamp(180px,26.4vw,456px)] -translate-x-1/2 opacity-64"
      >
        <Image
          src={precision.image}
          alt=""
          fill
          sizes="(max-width: 768px) 40vw, 26vw"
          className="object-cover"
        />
      </div>

      <div className="shell relative">
        <div className="grid items-end gap-[clamp(2.5rem,4vw,68px)] lg:min-h-[clamp(360px,42vw,722px)] lg:grid-cols-[1fr_498px]">
          <Reveal as="h2" className="display text-fluid-mega uppercase leading-[0.7] text-white">
            {precision.word}
          </Reveal>

          <Reveal delay={120} className="flex flex-col gap-[clamp(1rem,1.39vw,24px)] lg:mb-[clamp(2rem,10vw,172px)]">
            <p className="max-w-[423px] text-fluid-lead font-bold text-white">
              {precision.heading}
            </p>
            <p className="max-w-[423px] text-fluid-lead font-normal text-white/60">
              {precision.body}
            </p>
            <a href={precision.cta.href} className="pill group w-fit">
              {precision.cta.label}
              <ArrowRightPill className="transition-transform duration-300 group-hover:translate-x-0.5" />
            </a>
          </Reveal>
        </div>
      </div>
    </section>
  );
}
