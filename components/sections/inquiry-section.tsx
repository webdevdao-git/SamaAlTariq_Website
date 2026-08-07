import Image from "next/image";
import { InquiryForm } from "@/components/inquiry-form";
import { Reveal } from "@/components/reveal";
import { inquiry, site } from "@/content/site";

/**
 * Figma: frame 1226:955, 1728×980.
 * A full-bleed interior photo with a white card (1466 wide, 40px radius,
 * 64px padding) floating over it: copy column on the left with the copyright
 * pinned to its bottom, form on the right.
 */
export function InquirySection() {
  return (
    <section
      id="contact"
      className="relative isolate border-t border-hairline px-[clamp(1rem,4.63vw,80px)] py-[clamp(2.5rem,4.63vw,80px)]"
    >
      <Image
        src={inquiry.background}
        alt=""
        fill
        sizes="100vw"
        className="-z-10 object-cover"
      />

      <Reveal className="mx-auto w-full max-w-[1466px] rounded-[clamp(20px,2.31vw,40px)] bg-white p-[clamp(1.5rem,3.7vw,64px)] shadow-[0_30px_80px_-40px_rgba(0,0,0,0.35)]">
        <div className="flex flex-col gap-[clamp(2.5rem,5.79vw,100px)] lg:flex-row">
          <div className="flex w-full flex-col justify-between gap-[clamp(2rem,4vw,64px)] lg:w-[548px] lg:shrink-0">
            <div className="flex flex-col gap-[clamp(1rem,2.31vw,40px)]">
              <p className="text-fluid-label font-medium text-teal">
                {inquiry.label}
              </p>
              <h2 className="display max-w-[444px] text-fluid-h2 leading-[1.3] text-ink">
                {inquiry.heading.map((line) => (
                  <span key={line} className="inline sm:block">
                    {line}{" "}
                  </span>
                ))}
              </h2>
              <p className="text-fluid-body font-medium text-ink-muted">
                {inquiry.body}
              </p>
            </div>

            <p className="text-fluid-body font-medium text-ink">
              {site.copyright}
            </p>
          </div>

          <InquiryForm />
        </div>
      </Reveal>
    </section>
  );
}
