import Image from "next/image";
import { LogoMark } from "@/components/logo";
import { ArrowOutward, DiagonalArrow, DotMark } from "@/components/icons";
import { Reveal } from "@/components/reveal";
import { footer, nav, social } from "@/content/site";

/**
 * Figma: frame 1226:1038, 1728×774, background #3FA7B3.
 * Three top columns (mark + nav / "Recently Completed" card with the large
 * rotated arrow / social links), and the wordmark lock-up across the bottom:
 * SAMA AL TARIQ at 208px over BUILDING CONTRACTING LLC. at 49px with 0.72em
 * tracking. Both are sized in vw so the lock-up always spans the page.
 */
export function Footer() {
  return (
    <footer className="overflow-hidden bg-teal pt-[clamp(2.5rem,4.63vw,80px)] text-white">
      <div className="shell">
        <div className="grid items-start gap-[clamp(2.5rem,4vw,68px)] lg:grid-cols-12">
          {/* Mark + navigation */}
          <Reveal className="flex flex-col gap-[clamp(1.25rem,2.14vw,37px)] lg:col-span-6">
            <LogoMark width={63} className="h-auto w-[clamp(44px,3.65vw,63px)]" />
            <nav aria-label="Footer">
              <ul className="flex flex-col">
                {nav.map((item) => (
                  <li key={item.href}>
                    <a
                      href={item.href}
                      className="inline-block text-fluid-sm font-medium transition-opacity hover:opacity-70"
                    >
                      {item.label}
                    </a>
                  </li>
                ))}
              </ul>
            </nav>
          </Reveal>

          {/* Recently completed */}
          <Reveal
            delay={80}
            className="relative w-full max-w-[417px] lg:col-span-3 lg:col-start-8"
          >
            <a href={footer.recent.href} className="group block">
              <span className="mb-[clamp(0.35rem,0.44vw,7.5px)] flex items-center gap-1.5">
                <DotMark className="text-white" />
                <span className="text-[clamp(10px,0.73vw,12.5px)] font-semibold">
                  {footer.recent.label}
                </span>
              </span>
              <span className="relative block aspect-[417/259] w-full overflow-hidden bg-white">
                <Image
                  src={footer.recent.image}
                  alt="Recently completed corporate lobby fit-out"
                  fill
                  sizes="(max-width: 1024px) 90vw, 24vw"
                  className="object-cover transition-transform duration-[900ms] ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-[1.05]"
                />
              </span>
              <DiagonalArrow
                className="absolute bottom-[-6%] right-[6%] w-[clamp(28px,2.95vw,51px)] text-white transition-transform duration-500 group-hover:translate-x-1 group-hover:-translate-y-1"
              />
            </a>
          </Reveal>

          {/* Social */}
          <Reveal delay={160} className="lg:col-span-2 lg:col-start-11 lg:justify-self-end">
            <ul className="flex flex-col gap-[clamp(0.5rem,0.86vw,15px)] lg:w-[clamp(120px,10.4vw,180px)]">
              {social.map((s) => (
                <li key={s.label}>
                  <a
                    href={s.href}
                    target="_blank"
                    rel="noreferrer noopener"
                    className="group flex items-center justify-between gap-2 text-fluid-body transition-opacity hover:opacity-75"
                  >
                    {s.label}
                    <ArrowOutward className="w-[clamp(16px,1.39vw,24px)] shrink-0 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                  </a>
                </li>
              ))}
            </ul>
          </Reveal>
        </div>

        {/* Wordmark lock-up */}
        <div className="mt-[clamp(2.5rem,7vw,120px)] pb-[clamp(1rem,1.5vw,26px)]">
          <p className="font-wordmark text-[12.06vw] font-semibold leading-[0.78] whitespace-nowrap">
            {footer.wordmark.toUpperCase()}
          </p>
          <p className="mt-[clamp(0.15rem,0.35vw,6px)] pl-[0.5vw] font-wordmark text-[2.84vw] leading-none tracking-[0.72em] whitespace-nowrap">
            {footer.wordmarkSub.toUpperCase()}
          </p>
        </div>
      </div>
    </footer>
  );
}
