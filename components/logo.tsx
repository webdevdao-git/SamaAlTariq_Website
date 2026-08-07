import Image from "next/image";
import { site } from "@/content/site";

/**
 * The stacked-chevron mark is a raster asset in Figma (image 4, 540×462),
 * exported to /images/logo-mark.png. It is white-on-transparent, so it only
 * ever sits on the hero photo or the teal footer.
 */
export function LogoMark({
  className,
  width = 68,
}: {
  className?: string;
  width?: number;
}) {
  return (
    <Image
      src="/images/logo-mark.png"
      alt=""
      width={width}
      height={Math.round((width * 462) / 540)}
      className={className}
      priority
    />
  );
}

/** Mark over the two-line Cormorant wordmark — the header lockup (180px wide in Figma). */
export function LogoLockup({ className }: { className?: string }) {
  return (
    <span
      className={`flex flex-col items-center leading-none text-white ${className ?? ""}`}
    >
      <LogoMark width={68} className="h-auto w-[clamp(38px,3.94vw,68px)]" />
      <span className="mt-[0.55em] font-wordmark text-[clamp(13px,1.37vw,23.6px)] font-semibold tracking-[0.01em] whitespace-nowrap">
        {site.name.toUpperCase()}
      </span>
      <span className="mt-[0.25em] font-wordmark text-[clamp(7px,0.73vw,12.5px)] font-bold tracking-[0.02em] whitespace-nowrap">
        {site.tagline.toUpperCase()}
      </span>
    </span>
  );
}
