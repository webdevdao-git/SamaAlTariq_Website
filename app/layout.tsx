import type { Metadata, Viewport } from "next";
import { Manrope, Playfair_Display, Cormorant_Garamond } from "next/font/google";
import "./globals.css";
import { SmoothScroll } from "@/components/motion/smooth-scroll";
import { Preloader } from "@/components/motion/preloader";

const manrope = Manrope({
  subsets: ["latin"],
  variable: "--font-manrope",
  display: "swap",
});

/**
 * The Figma file uses "Juana Alt Medium" (Latinotype, commercial licence) for
 * every display heading. Playfair Display is the closest freely-licensed match
 * for the same high-contrast serif voice. To swap in the licensed face, replace
 * this loader with a next/font/local pointing at the Juana web fonts — the rest
 * of the app only ever references the --font-display variable.
 */
const display = Playfair_Display({
  subsets: ["latin"],
  weight: ["400", "500", "600"],
  variable: "--font-display",
  display: "swap",
});

const cormorant = Cormorant_Garamond({
  subsets: ["latin"],
  weight: ["400", "600"],
  variable: "--font-cormorant",
  display: "swap",
});

const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "https://samaaltariq.ae";

export const metadata: Metadata = {
  metadataBase: new URL(siteUrl),
  title: {
    default: "Sama Al Tariq Building Contracting L.L.C. — Building With Precision",
    template: "%s | Sama Al Tariq",
  },
  description:
    "Sama Al Tariq delivers exceptional construction, engineering, and contracting solutions across Dubai — fit-out, design & build, villa renovation, joinery, and millwork.",
  keywords: [
    "construction Dubai",
    "fit-out contracting Dubai",
    "design and build UAE",
    "villa renovation Dubai",
    "joinery and millwork",
    "Sama Al Tariq",
  ],
  openGraph: {
    type: "website",
    url: siteUrl,
    siteName: "Sama Al Tariq Building Contracting L.L.C.",
    title: "Building With Precision — Sama Al Tariq",
    description:
      "Exceptional construction, engineering, and contracting solutions that shape modern communities.",
    images: [{ url: "/images/hero.webp", width: 1480, height: 986 }],
  },
  twitter: {
    card: "summary_large_image",
    title: "Building With Precision — Sama Al Tariq",
    description:
      "Exceptional construction, engineering, and contracting solutions that shape modern communities.",
    images: ["/images/hero.webp"],
  },
  robots: { index: true, follow: true },
};

export const viewport: Viewport = {
  themeColor: "#3fa7b3",
  width: "device-width",
  initialScale: 1,
};

export default function RootLayout({
  children,
}: Readonly<{ children: React.ReactNode }>) {
  return (
    <html
      lang="en"
      className={`${manrope.variable} ${display.variable} ${cormorant.variable}`}
    >
      <head>
        {/*
          Scroll-reveal fallback. `.reveal` starts at opacity 0 and JavaScript
          adds `.is-visible`; without JavaScript nothing ever would, so the page
          would read as blank. This reverses the hidden state for those visitors.

          Deliberately a <noscript> style rather than a script that stamps a
          class onto <html>: mutating the root element before hydration changes
          markup React already rendered on the server, which is a hydration
          mismatch. Nothing here touches the DOM, so server and client agree.
        */}
        <noscript
          dangerouslySetInnerHTML={{
            __html: `<style>
              .reveal{opacity:1 !important;transform:none !important}
              .intro-curtain{display:none !important}
              html{overflow:visible !important}
            </style>`,
          }}
        />
      </head>
      <body className="antialiased">
        <SmoothScroll />
        <Preloader />
        {children}
      </body>
    </html>
  );
}
