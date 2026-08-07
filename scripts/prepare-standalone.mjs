#!/usr/bin/env node
/**
 * `output: "standalone"` emits .next/standalone/server.js with a minimal
 * node_modules, but Next deliberately does NOT copy ./public or the static
 * chunks into it — on Vercel those are served by the CDN. On Hostinger the Node
 * process serves everything, so without this step the site boots with no CSS,
 * no JS and no images.
 *
 * Runs automatically after `npm run build` (postbuild), so `npm start` always
 * has a complete tree.
 */
import { cp, access, rm } from "node:fs/promises";
import path from "node:path";
import process from "node:process";

const root = process.cwd();
const standalone = path.join(root, ".next", "standalone");

try {
  await access(standalone);
} catch {
  console.error(
    'No .next/standalone directory. Is output: "standalone" set in next.config.ts?',
  );
  process.exit(1);
}

// Clear first: cp merges rather than replaces, so without this every rebuild
// leaves the previous build's hashed chunks behind and the deployed bundle
// grows without limit.
await rm(path.join(standalone, "public"), { recursive: true, force: true });
await rm(path.join(standalone, ".next", "static"), { recursive: true, force: true });

await cp(path.join(root, "public"), path.join(standalone, "public"), {
  recursive: true,
});

await cp(
  path.join(root, ".next", "static"),
  path.join(standalone, ".next", "static"),
  { recursive: true },
);

console.log("Copied public/ and .next/static into .next/standalone.");
