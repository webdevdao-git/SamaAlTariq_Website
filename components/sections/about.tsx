import Image from "next/image";
import { Reveal } from "@/components/reveal";
import { about } from "@/content/site";

/**
 * Figma: frame 1226:693, 1728×1102, 79px inline / 100px block padding.
 * Label + 48px heading, a 389×272 image set at ~54% of the content width,
 * a full-width hairline, then a two-column block: subheading + stat row on the
 * left, two body paragraphs on the right.
 */
export function About() {
  return (
    <section id="about" className="bg-white py-[clamp(3.5rem,5.79vw,100px)]">
      <div className="shell">
        <Reveal className="flex flex-col gap-[clamp(1rem,3.7vw,64px)] md:flex-row md:items-start">
          <p className="shrink-0 text-fluid-label font-medium text-teal">
            {about.label}
          </p>
          {/*
            The designed line breaks only make sense at the width they were set
            for. Below md the spans go inline so the heading wraps naturally
            instead of breaking twice — once where Figma said, once where the
            viewport forces it.
          */}
          <h2 className="display max-w-[1042px] text-fluid-h2 leading-[1.3] text-ink">
            {about.heading.map((line) => (
              <span key={line} className="inline md:block">
                {line}{" "}
              </span>
            ))}
          </h2>
        </Reveal>

        <Reveal className="mt-[clamp(2.5rem,5.79vw,100px)] flex justify-center md:justify-start md:pl-[54%]">
          <Image
            src={about.image}
            alt="Curved timber-ribbed interior with a sculpted lounge chair"
            width={389}
            height={272}
            sizes="(max-width: 768px) 90vw, 389px"
            className="h-auto w-full max-w-[389px] object-cover"
          />
        </Reveal>

        <div className="mt-[clamp(2rem,2.31vw,40px)] border-t border-black/10 pt-[clamp(2rem,2.31vw,40px)]">
          <div className="grid gap-[clamp(2rem,3vw,52px)] md:grid-cols-2">
            <Reveal className="flex flex-col justify-between gap-[clamp(2rem,4.63vw,80px)]">
              <h3 className="text-fluid-body font-semibold text-ink">
                {about.subheading.map((line) => (
                  <span key={line} className="block">
                    {line}
                  </span>
                ))}
              </h3>

              <dl className="flex flex-wrap items-center gap-[clamp(1.25rem,2.31vw,40px)]">
                {about.stats.map((stat, i) => (
                  <div key={stat.label} className="flex items-center gap-[clamp(1.25rem,2.31vw,40px)]">
                    {i > 0 && (
                      <span aria-hidden className="h-[52px] w-px bg-black/15" />
                    )}
                    <div>
                      <dt className="sr-only">{stat.label}</dt>
                      <dd>
                        <span className="block text-fluid-stat font-medium tracking-[-0.06em] text-teal">
                          {stat.value}
                        </span>
                        <span className="mt-1 block text-fluid-body font-medium text-ink">
                          {stat.label}
                        </span>
                      </dd>
                    </div>
                  </div>
                ))}
              </dl>
            </Reveal>

            <Reveal delay={120} className="flex flex-col gap-[1.4em]">
              {about.body.map((p) => (
                <p
                  key={p.slice(0, 24)}
                  className="max-w-[561px] text-fluid-lead font-medium text-ink"
                >
                  {p}
                </p>
              ))}
            </Reveal>
          </div>
        </div>
      </div>
    </section>
  );
}
