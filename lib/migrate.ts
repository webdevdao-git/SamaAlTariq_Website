import "server-only";
import { randomUUID } from "node:crypto";
import type { Pool } from "mysql2/promise";
import { SCHEMA_STATEMENTS } from "@/lib/schema";
import { hashPassword } from "@/lib/auth/password";

/**
 * Self-migration — the app creates its own schema on first database use.
 *
 * Hostinger's Node.js Web Apps run `npm run build` and start the process; there
 * is no deploy hook to run a migration in, and on a managed plan you may have no
 * shell at all. Requiring `npm run db:init` over SSH would make the deploy
 * depend on a step the platform cannot perform. Running it from inside the app
 * removes that dependency entirely.
 *
 * Safe to run on every cold start and from several instances at once: every
 * statement is CREATE TABLE IF NOT EXISTS, and the admin seed is a no-op once an
 * admin exists.
 *
 * Set AUTO_MIGRATE=false to turn this off and manage the schema yourself.
 */

let migration: Promise<void> | null = null;

export function ensureSchema(pool: Pool): Promise<void> {
  if (process.env.AUTO_MIGRATE === "false") return Promise.resolve();

  // Cached so concurrent requests share one migration rather than racing.
  if (!migration) {
    migration = run(pool).catch((error) => {
      // Do NOT keep a rejected promise: a database that was briefly unreachable
      // would then stay "failed" for the life of the process, and every later
      // request would fail even after MySQL recovered.
      migration = null;
      throw error;
    });
  }

  return migration;
}

async function run(pool: Pool): Promise<void> {
  for (const statement of SCHEMA_STATEMENTS) {
    await pool.query(statement);
  }

  await seedAdmin(pool);
  console.info("[migrate] schema ready");
}

/**
 * Creates the first administrator from environment variables, once.
 *
 * Only runs when there is no admin at all, so it cannot overwrite a real
 * account or resurrect a deleted one. ADMIN_PASSWORD should be removed from the
 * environment after the first successful boot.
 */
async function seedAdmin(pool: Pool): Promise<void> {
  const email = process.env.ADMIN_EMAIL?.trim().toLowerCase();
  const password = process.env.ADMIN_PASSWORD;

  if (!email || !password) return;

  if (password.length < 10) {
    console.warn(
      "[migrate] ADMIN_PASSWORD is shorter than 10 characters — skipping admin seed.",
    );
    return;
  }

  const [rows] = await pool.query(
    "SELECT id FROM profiles WHERE role = 'admin' LIMIT 1",
  );

  if ((rows as unknown[]).length > 0) return;

  await pool.execute(
    `INSERT INTO profiles (id, email, password_hash, full_name, role, must_change_password)
     VALUES (?, ?, ?, ?, 'admin', 1)`,
    [
      randomUUID(),
      email,
      await hashPassword(password),
      process.env.ADMIN_NAME ?? null,
    ],
  );

  console.info(
    `[migrate] seeded administrator ${email} — remove ADMIN_PASSWORD from the environment now.`,
  );
}
