import { NextResponse } from "next/server";
import { readFile } from "node:fs/promises";
import { errorResponse, notFound } from "@/lib/api";
import { requireUser } from "@/lib/auth/session";
import { assertProjectAccess } from "@/lib/repositories/projects";
import {
  contentTypeFor,
  projectIdFromStoragePath,
  resolveStoragePath,
  statStoredFile,
} from "@/lib/storage";

/**
 * GET /api/files/<project_id>/<filename>
 * GET /api/files/<project_id>/reports/<filename>
 *
 * The only route out of private storage. This is the replacement for the
 * Supabase storage policies: the first path segment is the project id, and
 * assertProjectAccess() applies exactly the predicate
 * `storage.foldername(name)[1] = p.id AND p.client_id = auth.uid()` did.
 *
 * `?download=1` forces an attachment, and is refused for clients whose
 * can_download flag is off — the view-only rule from the original schema.
 */
export const runtime = "nodejs";
export const dynamic = "force-dynamic";

type Params = { params: Promise<{ path: string[] }> };

export async function GET(request: Request, { params }: Params) {
  try {
    const { path: segments } = await params;
    const storagePath = segments.join("/");

    const projectId = projectIdFromStoragePath(storagePath);
    if (!projectId) return notFound("File not found.");

    const user = await requireUser();
    await assertProjectAccess(projectId); // throws 404 when out of scope

    const info = await statStoredFile(storagePath);
    if (!info || !info.isFile()) return notFound("File not found.");

    const wantsDownload = new URL(request.url).searchParams.get("download") === "1";
    if (wantsDownload && user.role !== "admin" && !user.canDownload) {
      return NextResponse.json(
        { message: "Downloads are not enabled for your account." },
        { status: 403 },
      );
    }

    const filename = segments[segments.length - 1];
    const body = await readFile(resolveStoragePath(storagePath));

    return new NextResponse(new Uint8Array(body), {
      headers: {
        "Content-Type": contentTypeFor(storagePath),
        "Content-Length": String(info.size),
        "Content-Disposition": `${
          wantsDownload ? "attachment" : "inline"
        }; filename="${filename.replace(/"/g, "")}"`,
        // Private: these are client-confidential files, so no shared cache may
        // hold a copy, and the browser must revalidate the session each time.
        "Cache-Control": "private, no-store",
      },
    });
  } catch (error) {
    return errorResponse(error);
  }
}
