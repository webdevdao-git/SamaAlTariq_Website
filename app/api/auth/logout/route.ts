import { NextResponse } from "next/server";
import { errorResponse } from "@/lib/api";
import { destroySession } from "@/lib/auth/session";

/** POST /api/auth/logout — clears the session cookie. */
export const runtime = "nodejs";
export const dynamic = "force-dynamic";

export async function POST() {
  try {
    await destroySession();
    return NextResponse.json({ ok: true });
  } catch (error) {
    return errorResponse(error);
  }
}
