import "server-only";
import { cookies } from "next/headers";
import { SignJWT, jwtVerify } from "jose";
import { queryOne } from "@/lib/db";

/**
 * Session handling — the replacement for Supabase Auth.
 *
 * A signed, httpOnly JWT cookie carries only the user id; the role and every
 * other attribute are re-read from the database on each request. That means
 * revoking an account or demoting an admin takes effect immediately instead of
 * waiting for a stale token to expire.
 */

export const SESSION_COOKIE = "sat_session";
const MAX_AGE_SECONDS = 60 * 60 * 24 * 7; // 7 days

export type Role = "admin" | "client";

export type SessionUser = {
  id: string;
  email: string;
  fullName: string | null;
  username: string | null;
  phone: string | null;
  jobTitle: string | null;
  canDownload: boolean;
  role: Role;
  mustChangePassword: boolean;
};

function secret(): Uint8Array {
  const value = process.env.AUTH_SECRET;
  if (!value || value.length < 32) {
    throw new Error(
      "AUTH_SECRET is missing or shorter than 32 characters. Generate one with: openssl rand -base64 48",
    );
  }
  return new TextEncoder().encode(value);
}

export async function createSession(userId: string): Promise<void> {
  const token = await new SignJWT({ sub: userId })
    .setProtectedHeader({ alg: "HS256" })
    .setIssuedAt()
    .setExpirationTime(`${MAX_AGE_SECONDS}s`)
    .sign(secret());

  (await cookies()).set(SESSION_COOKIE, token, {
    httpOnly: true,
    secure: process.env.NODE_ENV === "production",
    sameSite: "lax",
    path: "/",
    maxAge: MAX_AGE_SECONDS,
  });
}

export async function destroySession(): Promise<void> {
  (await cookies()).delete(SESSION_COOKIE);
}

type ProfileRow = {
  id: string;
  email: string;
  full_name: string | null;
  username: string | null;
  phone: string | null;
  job_title: string | null;
  can_download: number;
  role: Role;
  must_change_password: number;
};

/** The signed-in user, or null. Never throws for an anonymous caller. */
export async function getSessionUser(): Promise<SessionUser | null> {
  const token = (await cookies()).get(SESSION_COOKIE)?.value;
  if (!token) return null;

  let userId: string;
  try {
    const { payload } = await jwtVerify(token, secret());
    if (typeof payload.sub !== "string") return null;
    userId = payload.sub;
  } catch {
    return null; // expired, tampered with, or signed by an old secret
  }

  const row = await queryOne<ProfileRow>(
    `SELECT id, email, full_name, username, phone, job_title,
            can_download, role, must_change_password
       FROM profiles
      WHERE id = ?`,
    [userId],
  );
  if (!row) return null;

  return {
    id: row.id,
    email: row.email,
    fullName: row.full_name,
    username: row.username,
    phone: row.phone,
    jobTitle: row.job_title,
    canDownload: Boolean(row.can_download),
    role: row.role,
    mustChangePassword: Boolean(row.must_change_password),
  };
}

/** Thrown by the require* helpers; mapped to a status code by the API routes. */
export class AuthError extends Error {
  status: number;

  constructor(status: number, message: string) {
    super(message);
    this.name = "AuthError";
    this.status = status;
  }
}

export async function requireUser(): Promise<SessionUser> {
  const user = await getSessionUser();
  if (!user) throw new AuthError(401, "Sign in to continue.");
  return user;
}

export async function requireAdmin(): Promise<SessionUser> {
  const user = await requireUser();
  if (user.role !== "admin") {
    throw new AuthError(403, "Administrator access is required.");
  }
  return user;
}
