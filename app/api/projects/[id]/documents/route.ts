import { NextResponse } from "next/server";
import { badRequest, errorResponse, notFound } from "@/lib/api";
import { requireAdmin } from "@/lib/auth/session";
import {
  addProjectDocument,
  listProjectDocuments,
  removeProjectDocument,
} from "@/lib/repositories/project-assets";
import { assertProjectAccess } from "@/lib/repositories/projects";
import { deleteStoredFile, saveUpload } from "@/lib/storage";

/**
 * Project reports. Files land under "<project_id>/reports/", the same
 * convention the Supabase storage policies keyed off.
 *
 * GET    /api/projects/:id/documents
 * POST   /api/projects/:id/documents          — admin; multipart (field: file)
 * DELETE /api/projects/:id/documents?documentId=
 */
export const runtime = "nodejs";
export const dynamic = "force-dynamic";

type Params = { params: Promise<{ id: string }> };

export async function GET(_request: Request, { params }: Params) {
  try {
    const { id } = await params;
    return NextResponse.json({ documents: await listProjectDocuments(id) });
  } catch (error) {
    return errorResponse(error);
  }
}

export async function POST(request: Request, { params }: Params) {
  try {
    const { id } = await params;
    await requireAdmin();
    await assertProjectAccess(id);

    const form = await request.formData();
    const file = form.get("file");
    if (!(file instanceof File)) return badRequest("Attach a file to upload.");

    const givenName = form.get("name");
    const { storagePath, filename } = await saveUpload(file, id, "reports");
    const name =
      typeof givenName === "string" && givenName.trim()
        ? givenName.trim().slice(0, 255)
        : file.name || filename;

    const documentId = await addProjectDocument(id, storagePath, name);
    return NextResponse.json({ id: documentId, storagePath, name }, { status: 201 });
  } catch (error) {
    return errorResponse(error);
  }
}

export async function DELETE(request: Request, { params }: Params) {
  try {
    const { id } = await params;
    await assertProjectAccess(id);

    const documentId = new URL(request.url).searchParams.get("documentId");
    if (!documentId) return badRequest("documentId is required.");

    const storagePath = await removeProjectDocument(documentId);
    if (!storagePath) return notFound("Document not found.");

    await deleteStoredFile(storagePath);
    return NextResponse.json({ ok: true });
  } catch (error) {
    return errorResponse(error);
  }
}
