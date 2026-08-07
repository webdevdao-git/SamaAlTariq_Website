#!/usr/bin/env node
/**
 * Seeds the first administrator. Everything else — clients, projects — is
 * created through the API by an admin, so this is the one bootstrap that cannot
 * go through requireAdmin().
 *
 *   npm run admin:create -- admin@samaaltariq.org "Full Name"
 *
 * Prints a generated password if none is supplied via ADMIN_PASSWORD.
 * Refuses to overwrite an existing account.
 */
import process from "node:process";
import { randomUUID, randomBytes, scrypt as scryptCb } from "node:crypto";
import { promisify } from "node:util";
import mysql from "mysql2/promise";
import { loadEnv } from "./load-env.mjs";

loadEnv();

const scrypt = promisify(scryptCb);
const PARAMS = { N: 16384, r: 8, p: 1 };

// Kept byte-for-byte compatible with lib/auth/password.ts — same format string,
// same cost parameters, so hashes written here verify in the app.
async function hashPassword(password) {
  const salt = randomBytes(16);
  const derived = await scrypt(password, salt, 64, PARAMS);
  return [
    "scrypt",
    PARAMS.N,
    PARAMS.r,
    PARAMS.p,
    salt.toString("base64"),
    derived.toString("base64"),
  ].join("$");
}

function generatePassword(length = 16) {
  const alphabet = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789";
  const bytes = randomBytes(length);
  let out = "";
  for (let i = 0; i < length; i += 1) out += alphabet[bytes[i] % alphabet.length];
  return out;
}

const [email, fullName] = process.argv.slice(2);

if (!email) {
  console.error('Usage: npm run admin:create -- admin@example.com "Full Name"');
  process.exit(1);
}

const password = process.env.ADMIN_PASSWORD || generatePassword();
const generated = !process.env.ADMIN_PASSWORD;

const connection = await mysql.createConnection({
  host: process.env.MYSQL_HOST,
  port: Number(process.env.MYSQL_PORT ?? 3306),
  user: process.env.MYSQL_USER,
  password: process.env.MYSQL_PASSWORD,
  database: process.env.MYSQL_DATABASE,
  ssl: process.env.MYSQL_SSL === "true" ? { rejectUnauthorized: true } : undefined,
});

try {
  const [existing] = await connection.execute(
    "SELECT id FROM profiles WHERE email = ?",
    [email.toLowerCase()],
  );

  if (existing.length > 0) {
    console.error(`An account already exists for ${email}.`);
    console.error("Reset its password from the admin UI instead of re-seeding.");
    process.exit(1);
  }

  await connection.execute(
    `INSERT INTO profiles (id, email, password_hash, full_name, role, must_change_password)
     VALUES (?, ?, ?, ?, 'admin', ?)`,
    [
      randomUUID(),
      email.toLowerCase(),
      await hashPassword(password),
      fullName ?? null,
      generated ? 1 : 0,
    ],
  );

  console.log(`Administrator created: ${email}`);
  if (generated) {
    console.log(`Temporary password: ${password}`);
    console.log("Sign in and change it — the account is flagged to require it.");
  }
} finally {
  await connection.end();
}
