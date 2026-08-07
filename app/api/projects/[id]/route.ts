import { NextResponse } from "next/server";
import { errorResponse, notFound, readJson } from "@/lib/api";
import {
  archiveProject,
  destroyProject,
  getProject,
  restoreProject,
  updateProject,
  type CreateProjectInput,
} from "@/lib/repositories/projects";
import { deleteProjectFiles } from "@/lib/storage";

/**
 * GET    /api/projects/:id
 * PATCH  /api/projects/:id            — admin only; { restore: true } undeletes
 * DELETE /api/projects/:id            — soft delete (sets deleted_at)
 * DELETE /api/projects/:id?purge=true — hard delete, also removes the files
 */
export const runtime = "nodejs";
export const dynamic = "force-dynamic";

type Params = { params: Promise<{ id: string }> };

export async function GET(_request: Request, { params }: Params) {
  try {
    const { id } = await params;
    const project = await getProject(id);
    return project ? NextResponse.json({ project }) : notFound("Project not found.");
  } catch (error) {
    return errorResponse(error);
  }
}

export async function PATCH(request: Request, { params }: Params) {
  try {
    const { id } = await params;
    const body = await readJson<CreateProjectInput & { restore?: boolean }>(request);
    if (!body) return notFound("Nothing to update.");

    if (body.restore) {
      const restored = await restoreProject(id);
      return NextResponse.json({ updated: restored });
    }

    const fields: Partial<CreateProjectInput> = { ...body };
    delete (fields as { restore?: boolean }).restore;

    return NextResponse.json({ updated: await updateProject(id, fields) });
  } catch (error) {
    return errorResponse(error);
  }
}

export async function DELETE(request: Request, { params }: Params) {
  try {
    const { id } = await params;
    const purge = new URL(request.url).searchParams.get("purge") === "true";

    if (!purge) {
      const archived = await archiveProject(id);
      return archived ? NextResponse.json({ ok: true }) : notFound("Project not found.");
    }

    // Rows first: if the DB delete fails we still have the files, which is the
    // recoverable direction. Child rows cascade.
    const destroyed = await destroyProject(id);
    if (!destroyed) return notFound("Project not found.");
    await deleteProjectFiles(id);

    return NextResponse.json({ ok: true, purged: true });
  } catch (error) {
    return errorResponse(error);
  }
}
