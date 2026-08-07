import "server-only";
import { randomBytes, scrypt as scryptCb, timingSafeEqual } from "node:crypto";
import { promisify } from "node:util";

const scrypt = promisify(scryptCb) as (
  password: string,
  salt: Buffer,
  keylen: number,
  options: { N: number; r: number; p: number },
) => Promise<Buffer>;

/**
 * Password hashing with scrypt from node:crypto.
 *
 * scrypt rather than bcrypt/argon2 because those ship native bindings that have
 * to compile on the host — Hostinger's Node app manager runs `npm install` on a
 * shared box where native builds are the usual source of deploy failures. scrypt
 * is memory-hard, in the standard library, and needs no toolchain.
 *
 * Stored format: scrypt$N$r$p$<salt-b64>$<hash-b64> — self-describing, so the
 * cost parameters can be raised later without invalidating existing hashes.
 */

const PARAMS = { N: 16384, r: 8, p: 1 };
const KEYLEN = 64;

export async function hashPassword(password: string): Promise<string> {
  const salt = randomBytes(16);
  const derived = await scrypt(password, salt, KEYLEN, PARAMS);
  return [
    "scrypt",
    PARAMS.N,
    PARAMS.r,
    PARAMS.p,
    salt.toString("base64"),
    derived.toString("base64"),
  ].join("$");
}

export async function verifyPassword(
  password: string,
  stored: string,
): Promise<boolean> {
  const parts = stored.split("$");
  if (parts.length !== 6 || parts[0] !== "scrypt") return false;

  const [, n, r, p, saltB64, hashB64] = parts;
  const salt = Buffer.from(saltB64, "base64");
  const expected = Buffer.from(hashB64, "base64");

  let derived: Buffer;
  try {
    derived = await scrypt(password, salt, expected.length, {
      N: Number(n),
      r: Number(r),
      p: Number(p),
    });
  } catch {
    return false;
  }

  // Length check first: timingSafeEqual throws on a mismatch rather than
  // returning false, and a length difference is not secret anyway.
  if (derived.length !== expected.length) return false;
  return timingSafeEqual(derived, expected);
}

/** Temporary password for a newly created client, emailed once. */
export function generatePassword(length = 14): string {
  // No look-alike characters (0/O, 1/l/I) — these get read off a screen.
  const alphabet = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789";
  const bytes = randomBytes(length);
  let out = "";
  for (let i = 0; i < length; i += 1) out += alphabet[bytes[i] % alphabet.length];
  return out;
}
