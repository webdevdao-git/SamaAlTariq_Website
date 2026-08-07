import { NextResponse } from "next/server";
import { errorResponse, readJson } from "@/lib/api";
import { verifyPassword } from "@/lib/auth/password";
import { requireUser } from "@/lib/auth/session";
import {
  changeOwnPassword,
  findAuthProfileByIdentifier,
} from "@/lib/repositories/profiles";

/**
 * POST /api/auth/password — a signed-in user changes their own password.
 * Replaces supabase.auth.updateUser({ password }).
 */
export const runtime = "nodejs";
export const dynamic = "force-dynamic";

const MIN_LENGTH = 10;

export async function POST(request: Request) {
  try {
    const user = await requireUser();
    const body = await readJson<{ currentPassword?: string; newPassword?: string }>(
      request,
    );

    const currentPassword = body?.currentPassword ?? "";
    const newPassword = body?.newPassword ?? "";

    if (newPassword.length < MIN_LENGTH) {
      return NextResponse.json(
        { message: `Choose a password of at least ${MIN_LENGTH} characters.` },
        { status: 422 },
      );
    }

    // Re-authenticate before the change, so a borrowed session cannot lock the
    // real owner out of their account.
    const profile = await findAuthProfileByIdentifier(user.email);
    if (!profile || !(await verifyPassword(currentPassword, profile.password_hash))) {
      return NextResponse.json(
        { message: "Your current password is incorrect." },
        { status: 401 },
      );
    }

    await changeOwnPassword(newPassword);
    return NextResponse.json({ ok: true });
  } catch (error) {
    return errorResponse(error);
  }
}
