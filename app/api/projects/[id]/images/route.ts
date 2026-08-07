import { NextResponse } from "next/server";
import { badRequest, errorResponse, notFound } from "@/lib/api";
import { requireAdmin } from "@/lib/auth/session";
import {
  addProjectImage,
  listProjectImages,
  removeProjectImage,
} from "@/lib/repositories/project-assets";
import { assertProjectAccess } from "@/lib/repositories/projects";
import { deleteStoredFile, saveUpload } from "@/lib/storage";

/**
 * GET    /api/projects/:id/images         — scoped to the caller's access
 * POST   /api/projects/:id/images         — admin; multipart upload (field: file)
 * DELETE /api/projects/:id/images?imageId= — admin; removes the row and the file
 */
export const runtime = "nodejs";
export const dynamic = "force-dynamic";

type Params = { params: Promise<{ id: string }> };

export async function GET(_request: Request, { params }: Params) {
  try {
    const { id } = await params;
    return NextResponse.json({ images: await listProjectImages(id) });
  } catch (error) {
    return errorResponse(error);
  }
}

export async function POST(request: Request, { params }: Params) {
  try {
    const { id } = await params;
    await requireAdmin();
    // Confirms the project exists before anything is written to disk.
    await assertProjectAccess(id);

    const form = await request.formData();
    const file = form.get("file");
    if (!(file instanceof File)) return badRequest("Attach a file to upload.");

    const caption = form.get("caption");
    const { storagePath } = await saveUpload(file, id, "");
    const imageId = await addProjectImage(
      id,
      storagePath,
      typeof caption === "string" && caption.trim() ? caption.trim().slice(0, 300) : null,
    );

    return NextResponse.json({ id: imageId, storagePath }, { status: 201 });
  } catch (error) {
    return errorResponse(error);
  }
}

export async function DELETE(request: Request, { params }: Params) {
  try {
    const { id } = await params;
    await assertProjectAccess(id);

    const imageId = new URL(request.url).searchParams.get("imageId");
    if (!imageId) return badRequest("imageId is required.");

    const storagePath = await removeProjectImage(imageId);
    if (!storagePath) return notFound("Image not found.");

    await deleteStoredFile(storagePath);
    return NextResponse.json({ ok: true });
  } catch (error) {
    return errorResponse(error);
  }
}
