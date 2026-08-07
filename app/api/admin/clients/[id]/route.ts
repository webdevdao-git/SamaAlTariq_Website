import { NextResponse } from "next/server";
import { errorResponse, notFound, readJson } from "@/lib/api";
import { generatePassword } from "@/lib/auth/password";
import {
  deleteProfile,
  getProfile,
  setProfilePassword,
  updateProfile,
} from "@/lib/repositories/profiles";
import { isMailConfigured, sendClientCredentialsEmail } from "@/lib/mailer";

/**
 * GET    /api/admin/clients/:id
 * PATCH  /api/admin/clients/:id — the `update-client` Edge Function equivalent.
 *                                 Pass resetPassword:true to issue and email a
 *                                 new temporary password.
 * DELETE /api/admin/clients/:id
 */
export const runtime = "nodejs";
export const dynamic = "force-dynamic";

type Params = { params: Promise<{ id: string }> };

type PatchBody = {
  email?: string;
  fullName?: string | null;
  username?: string | null;
  phone?: string | null;
  jobTitle?: string | null;
  canDownload?: boolean;
  role?: "admin" | "client";
  resetPassword?: boolean;
};

export async function GET(_request: Request, { params }: Params) {
  try {
    const { id } = await params;
    const profile = await getProfile(id);
    return profile ? NextResponse.json({ client: profile }) : notFound();
  } catch (error) {
    return errorResponse(error);
  }
}

export async function PATCH(request: Request, { params }: Params) {
  try {
    const { id } = await params;
    const body = await readJson<PatchBody>(request);
    if (!body) return notFound("Nothing to update.");

    const { resetPassword, ...fields } = body;
    const updated = await updateProfile(id, fields);

    let password: string | undefined;
    let emailed = false;

    if (resetPassword) {
      password = generatePassword();
      await setProfilePassword(id, password, { mustChange: true });

      const profile = await getProfile(id);
      if (profile && isMailConfigured()) {
        try {
          await sendClientCredentialsEmail({
            to: profile.email,
            fullName: profile.full_name,
            username: profile.username,
            password,
            portalUrl: `${process.env.NEXT_PUBLIC_SITE_URL ?? ""}/portal`,
          });
          emailed = true;
        } catch (error) {
          console.error("[clients] credential email failed:", error);
        }
      }
    }

    return NextResponse.json({
      updated: updated || Boolean(resetPassword),
      emailed,
      password: emailed ? undefined : password,
    });
  } catch (error) {
    return errorResponse(error);
  }
}

export async function DELETE(_request: Request, { params }: Params) {
  try {
    const { id } = await params;
    const removed = await deleteProfile(id);
    return removed ? NextResponse.json({ ok: true }) : notFound();
  } catch (error) {
    return errorResponse(error);
  }
}
