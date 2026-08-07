import Image from "next/image";
import { Reveal } from "@/components/reveal";
import { clients } from "@/content/site";

/**
 * Figma: frame 1195:119, 1728×294, background #F9F9F9.
 * Teal two-line label, then six client marks on a single baseline. The Figma
 * render shows them desaturated, so the marks are greyscaled in CSS and lift
 * to full colour on hover.
 */
export function Clients() {
  return (
    <section className="bg-mist py-[clamp(2.5rem,4vw,68px)]">
      <div className="shell">
        <Reveal className="flex flex-col items-start gap-[clamp(1.5rem,3vw,52px)] lg:flex-row lg:items-center">
          <p className="shrink-0 text-fluid-label font-medium leading-snug text-teal">
            {clients.label.map((line) => (
              <span key={line} className="block">
                {line}
              </span>
            ))}
          </p>

          <ul className="grid w-full grid-cols-3 items-center gap-x-[clamp(0.75rem,2vw,34px)] gap-y-8 sm:grid-cols-6">
            {clients.logos.map((logo) => (
              <li key={logo.name} className="flex items-center justify-center">
                <Image
                  src={logo.src}
                  alt={logo.name}
                  width={logo.width}
                  height={logo.height}
                  sizes="(max-width: 640px) 28vw, 12vw"
                  className="h-auto max-h-[clamp(30px,2.9vw,50px)] w-auto max-w-[clamp(88px,7.9vw,137px)] object-contain grayscale transition duration-300 hover:grayscale-0"
                />
              </li>
            ))}
          </ul>
        </Reveal>
      </div>
    </section>
  );
}
