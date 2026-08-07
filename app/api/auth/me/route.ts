import { NextResponse } from "next/server";
import { errorResponse } from "@/lib/api";
import { getSessionUser } from "@/lib/auth/session";

/**
 * GET /api/auth/me — the current user, or null.
 * Replaces supabase.auth.getSession()/getUser() on the client.
 */
export const runtime = "nodejs";
export const dynamic = "force-dynamic";

export async function GET() {
  try {
    return NextResponse.json({ user: await getSessionUser() });
  } catch (error) {
    return errorResponse(error);
  }
}
