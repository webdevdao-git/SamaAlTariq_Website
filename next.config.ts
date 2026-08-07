import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  /*
   * No `output: "standalone"`.
   *
   * Standalone suits a Node process you supervise yourself, but it needs a
   * postbuild step to copy public/ and .next/static into the output, and it
   * breaks `next start`. Hostinger's Web Apps runtime auto-detects Next.js and
   * runs the stock build/start pair, so the plain output is both simpler and
   * the one the platform expects.
   */

  images: {
    // All imagery is bundled in /public, so no remote patterns are needed.
    formats: ["image/webp"],
    // Hostinger shared plans have modest CPU quotas; cache optimised variants
    // for a week instead of re-encoding on every cold start.
    minimumCacheTTL: 60 * 60 * 24 * 7,
  },

  poweredByHeader: false,
  compress: true,

  async headers() {
    return [
      {
        source: "/:path*",
        headers: [
          { key: "X-Content-Type-Options", value: "nosniff" },
          { key: "Referrer-Policy", value: "strict-origin-when-cross-origin" },
          { key: "X-Frame-Options", value: "SAMEORIGIN" },
          {
            key: "Permissions-Policy",
            value: "camera=(), microphone=(), geolocation=()",
          },
        ],
      },
      {
        source: "/images/:path*",
        headers: [
          {
            key: "Cache-Control",
            value: "public, max-age=31536000, immutable",
          },
        ],
      },
    ];
  },
};

export default nextConfig;
