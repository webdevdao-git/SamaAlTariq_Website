import { NextResponse } from "next/server";
import { badRequest, errorResponse, notFound, readJson } from "@/lib/api";
import {
  addProjectUpdate,
  listProjectUpdates,
  removeProjectUpdate,
} from "@/lib/repositories/project-assets";
import { assertProjectAccess } from "@/lib/repositories/projects";

/**
 * GET    /api/projects/:id/updates
 * POST   /api/projects/:id/updates            — admin; { note }
 * DELETE /api/projects/:id/updates?updateId=  — admin
 */
export const runtime = "nodejs";
export const dynamic = "force-dynamic";

type Params = { params: Promise<{ id: string }> };

export async function GET(_request: Request, { params }: Params) {
  try {
    const { id } = await params;
    return NextResponse.json({ updates: await listProjectUpdates(id) });
  } catch (error) {
    return errorResponse(error);
  }
}

export async function POST(request: Request, { params }: Params) {
  try {
    const { id } = await params;
    await assertProjectAccess(id);

    const body = await readJson<{ note?: string }>(request);
    const note = (body?.note ?? "").trim();
    if (!note) return badRequest("A note is required.");

    const updateId = await addProjectUpdate(id, note.slice(0, 5000));
    return NextResponse.json({ id: updateId }, { status: 201 });
  } catch (error) {
    return errorResponse(error);
  }
}

export async function DELETE(request: Request, { params }: Params) {
  try {
    const { id } = await params;
    await assertProjectAccess(id);

    const updateId = new URL(request.url).searchParams.get("updateId");
    if (!updateId) return badRequest("updateId is required.");

    const removed = await removeProjectUpdate(updateId);
    return removed ? NextResponse.json({ ok: true }) : notFound("Update not found.");
  } catch (error) {
    return errorResponse(error);
  }
}
