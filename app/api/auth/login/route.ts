import { NextResponse } from "next/server";
import { errorResponse, readJson } from "@/lib/api";
import { clientIp, rateLimit } from "@/lib/rate-limit";
import { verifyPassword } from "@/lib/auth/password";
import { createSession, getSessionUser } from "@/lib/auth/session";
import {
  findAuthProfileByIdentifier,
  touchLastLogin,
} from "@/lib/repositories/profiles";

/** POST /api/auth/login — email or username + password. */
export const runtime = "nodejs";
export const dynamic = "force-dynamic";

export async function POST(request: Request) {
  try {
    const ip = clientIp(request.headers);
    const limit = rateLimit(`login:${ip}`, { limit: 10, windowMs: 15 * 60_000 });
    if (!limit.ok) {
      return NextResponse.json(
        { message: "Too many sign-in attempts. Please try again later." },
        { status: 429, headers: { "Retry-After": String(limit.retryAfterSeconds) } },
      );
    }

    const body = await readJson<{ identifier?: string; password?: string }>(request);
    const identifier = (body?.identifier ?? "").trim().slice(0, 254);
    const password = body?.password ?? "";

    if (!identifier || !password) {
      return NextResponse.json(
        { message: "Enter your email or username and password." },
        { status: 400 },
      );
    }

    const profile = await findAuthProfileByIdentifier(identifier);

    // Same message and roughly the same work either way, so the response does
    // not reveal whether an account exists.
    const ok = profile
      ? await verifyPassword(password, profile.password_hash)
      : await verifyPassword(password, "scrypt$16384$8$1$AAAA$AAAA");

    if (!profile || !ok) {
      return NextResponse.json(
        { message: "Incorrect email/username or password." },
        { status: 401 },
      );
    }

    await createSession(profile.id);
    await touchLastLogin(profile.id);

    return NextResponse.json({ user: await getSessionUser() });
  } catch (error) {
    return errorResponse(error);
  }
}
