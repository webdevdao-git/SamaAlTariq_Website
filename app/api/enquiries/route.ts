import { NextResponse } from "next/server";
import { errorResponse, readJson } from "@/lib/api";
import { clientIp, rateLimit } from "@/lib/rate-limit";
import {
  normalizeEnquiry,
  validateEnquiry,
  type EnquiryInput,
} from "@/lib/enquiry";
import { createEnquiry, listEnquiries } from "@/lib/repositories/enquiries";
import { isMailConfigured, sendEnquiryEmail } from "@/lib/mailer";

/**
 * POST /api/enquiries — the public contact form (was the `send-enquiry` Edge
 * Function). Validates server-side, drops bots, rate-limits by IP, writes to
 * MySQL, then emails the business inbox.
 *
 * GET /api/enquiries — admin-only list, scoped inside the repository.
 */
export const runtime = "nodejs";
export const dynamic = "force-dynamic";

const MAX_BODY_BYTES = 24 * 1024;

export async function POST(request: Request) {
  try {
    const ip = clientIp(request.headers);

    const limit = rateLimit(`enquiry:${ip}`, { limit: 5, windowMs: 10 * 60_000 });
    if (!limit.ok) {
      return NextResponse.json(
        { message: "Too many enquiries from this address. Please try again later." },
        { status: 429, headers: { "Retry-After": String(limit.retryAfterSeconds) } },
      );
    }

    if (Number(request.headers.get("content-length") ?? 0) > MAX_BODY_BYTES) {
      return NextResponse.json({ message: "Request too large." }, { status: 413 });
    }

    const raw = await readJson<Partial<EnquiryInput>>(request);
    if (!raw) {
      return NextResponse.json({ message: "Invalid request body." }, { status: 400 });
    }

    const enquiry = normalizeEnquiry(raw);

    // Honeypot: accept and discard, so the bot sees success and does not retry.
    if (enquiry.company) {
      return NextResponse.json({ message: "Thank you — your enquiry has been sent." });
    }

    const errors = validateEnquiry(enquiry);
    if (Object.keys(errors).length > 0) {
      return NextResponse.json(
        { message: "Please check the highlighted fields.", errors },
        { status: 422 },
      );
    }

    await createEnquiry(enquiry, {
      ip,
      userAgent: request.headers.get("user-agent"),
    });

    if (isMailConfigured()) {
      try {
        await sendEnquiryEmail(enquiry);
      } catch (error) {
        // The enquiry is already saved, so this is a degraded success, not a
        // failure — never ask the visitor to submit the form again.
        console.error("[enquiries] SMTP send failed:", error);
        return NextResponse.json(
          {
            message:
              "Thank you — your enquiry has been received. Our team will be in touch shortly.",
          },
          { status: 202 },
        );
      }
    } else {
      console.warn("[enquiries] SMTP is not configured — saved to MySQL only.");
    }

    return NextResponse.json({
      message: "Thank you — your enquiry has been sent. We'll be in touch shortly.",
    });
  } catch (error) {
    return errorResponse(error);
  }
}

export async function GET(request: Request) {
  try {
    const url = new URL(request.url);
    const status = url.searchParams.get("status");

    const enquiries = await listEnquiries({
      status:
        status === "new" || status === "read" || status === "archived"
          ? status
          : undefined,
      limit: Number(url.searchParams.get("limit") ?? 100),
      offset: Number(url.searchParams.get("offset") ?? 0),
    });

    return NextResponse.json({ enquiries });
  } catch (error) {
    return errorResponse(error);
  }
}
