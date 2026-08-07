import "server-only";
import { NextResponse } from "next/server";
import { AuthError } from "@/lib/auth/session";
import { StorageError } from "@/lib/storage";

/**
 * One place to turn a thrown error into a response, so no route leaks a stack
 * trace or a MySQL message to the client. Anything that is not a known,
 * user-facing error becomes a generic 500 and is logged server-side.
 */
export function errorResponse(error: unknown): NextResponse {
  if (error instanceof AuthError || error instanceof StorageError) {
    return NextResponse.json({ message: error.message }, { status: error.status });
  }

  // Duplicate email / username hit the unique indexes on `profiles`.
  if (
    typeof error === "object" &&
    error !== null &&
    (error as { code?: string }).code === "ER_DUP_ENTRY"
  ) {
    return NextResponse.json(
      { message: "That email or username is already in use." },
      { status: 409 },
    );
  }

  console.error("[api]", error);
  return NextResponse.json(
    { message: "Something went wrong. Please try again." },
    { status: 500 },
  );
}

/** Parses a JSON body, returning null when it is missing or malformed. */
export async function readJson<T>(request: Request): Promise<T | null> {
  try {
    const body = await request.json();
    return typeof body === "object" && body !== null ? (body as T) : null;
  } catch {
    return null;
  }
}

export const badRequest = (message = "Invalid request body.") =>
  NextResponse.json({ message }, { status: 400 });

export const notFound = (message = "Not found.") =>
  NextResponse.json({ message }, { status: 404 });
