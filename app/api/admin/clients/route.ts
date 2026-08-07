import { NextResponse } from "next/server";
import { badRequest, errorResponse, readJson } from "@/lib/api";
import { generatePassword } from "@/lib/auth/password";
import { createProfile, listProfiles } from "@/lib/repositories/profiles";
import { isMailConfigured, sendClientCredentialsEmail } from "@/lib/mailer";

/**
 * GET  /api/admin/clients — list accounts (admin only).
 * POST /api/admin/clients — the `create-client` Edge Function equivalent:
 *   creates the account, then emails the temporary password.
 *
 * Authorisation lives in the repository (requireAdmin), so it holds no matter
 * which route reaches these functions.
 */
export const runtime = "nodejs";
export const dynamic = "force-dynamic";

type CreateBody = {
  email?: string;
  fullName?: string;
  username?: string;
  phone?: string;
  jobTitle?: string;
  canDownload?: boolean;
  role?: "admin" | "client";
  password?: string;
};

export async function GET(request: Request) {
  try {
    const role = new URL(request.url).searchParams.get("role");
    const clients = await listProfiles(
      role === "admin" || role === "client" ? role : undefined,
    );
    return NextResponse.json({ clients });
  } catch (error) {
    return errorResponse(error);
  }
}

export async function POST(request: Request) {
  try {
    const body = await readJson<CreateBody>(request);
    const email = (body?.email ?? "").trim().toLowerCase();

    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email)) {
      return badRequest("A valid email address is required.");
    }

    // An admin may set a password explicitly; otherwise one is generated and
    // the account is flagged to force a change at first sign-in.
    const generated = !body?.password;
    const password = body?.password ?? generatePassword();

    const id = await createProfile({
      email,
      password,
      fullName: body?.fullName?.trim() || null,
      username: body?.username?.trim() || null,
      phone: body?.phone?.trim() || null,
      jobTitle: body?.jobTitle?.trim() || null,
      canDownload: Boolean(body?.canDownload),
      role: body?.role === "admin" ? "admin" : "client",
      mustChangePassword: generated,
    });

    let emailed = false;
    if (isMailConfigured()) {
      try {
        await sendClientCredentialsEmail({
          to: email,
          fullName: body?.fullName?.trim() || null,
          username: body?.username?.trim() || null,
          password,
          portalUrl: `${process.env.NEXT_PUBLIC_SITE_URL ?? ""}/portal`,
        });
        emailed = true;
      } catch (error) {
        console.error("[clients] credential email failed:", error);
      }
    }

    return NextResponse.json(
      {
        id,
        emailed,
        // Returned only when we could not deliver it, so the admin can pass it
        // on by hand. Never echoed back on a successful send.
        password: emailed ? undefined : generated ? password : undefined,
        message: emailed
          ? "Client created and credentials emailed."
          : "Client created. Email delivery is not configured, so share the password manually.",
      },
      { status: 201 },
    );
  } catch (error) {
    return errorResponse(error);
  }
}
