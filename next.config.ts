import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  /**
   * `standalone` emits .next/standalone/server.js — a self-contained Node
   * server with only the packages it actually needs. That is what Hostinger's
   * Node.js app manager runs, and it keeps the uploaded bundle small enough to
   * deploy over their file manager or SSH. See DEPLOY-HOSTINGER.md.
   */
  output: "standalone",

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
