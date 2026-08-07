import { readFileSync } from "node:fs";
import path from "node:path";
import process from "node:process";

/**
 * Minimal .env loader for the standalone scripts.
 *
 * Next.js loads .env itself at runtime, but these scripts run outside it, and
 * pulling in dotenv just for them would add a dependency that ships to
 * production. Real environment variables always win, so Hostinger's hPanel
 * variables are never overridden by a stray .env left on the server.
 */
export function loadEnv(files = [".env.local", ".env"]) {
  for (const file of files) {
    let contents;
    try {
      contents = readFileSync(path.join(process.cwd(), file), "utf8");
    } catch {
      continue;
    }

    for (const rawLine of contents.split("\n")) {
      const line = rawLine.trim();
      if (!line || line.startsWith("#")) continue;

      const eq = line.indexOf("=");
      if (eq === -1) continue;

      const key = line.slice(0, eq).trim();
      if (!key || key in process.env) continue;

      let value = line.slice(eq + 1).trim();
      if (
        (value.startsWith('"') && value.endsWith('"')) ||
        (value.startsWith("'") && value.endsWith("'"))
      ) {
        value = value.slice(1, -1);
      }

      process.env[key] = value;
    }
  }
}
