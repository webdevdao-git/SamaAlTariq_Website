import { NextResponse } from "next/server";
import { badRequest, errorResponse, notFound, readJson } from "@/lib/api";
import {
  addProjectStage,
  listProjectStages,
  removeProjectStage,
  updateProjectStage,
  type StageStatus,
} from "@/lib/repositories/project-assets";
import { assertProjectAccess } from "@/lib/repositories/projects";

/**
 * GET    /api/projects/:id/stages
 * POST   /api/projects/:id/stages           — admin; { name, status?, targetDate?, sortOrder? }
 * PATCH  /api/projects/:id/stages           — admin; { stageId, ...fields }
 * DELETE /api/projects/:id/stages?stageId=  — admin
 */
export const runtime = "nodejs";
export const dynamic = "force-dynamic";

type Params = { params: Promise<{ id: string }> };

type StageBody = {
  stageId?: string;
  name?: string;
  status?: StageStatus;
  targetDate?: string | null;
  sortOrder?: number;
};

export async function GET(_request: Request, { params }: Params) {
  try {
    const { id } = await params;
    return NextResponse.json({ stages: await listProjectStages(id) });
  } catch (error) {
    return errorResponse(error);
  }
}

export async function POST(request: Request, { params }: Params) {
  try {
    const { id } = await params;
    await assertProjectAccess(id);

    const body = await readJson<StageBody>(request);
    const name = (body?.name ?? "").trim();
    if (!name) return badRequest("A stage name is required.");

    const stageId = await addProjectStage(id, {
      name: name.slice(0, 200),
      status: body?.status,
      targetDate: body?.targetDate ?? null,
      sortOrder: body?.sortOrder,
    });

    return NextResponse.json({ id: stageId }, { status: 201 });
  } catch (error) {
    return errorResponse(error);
  }
}

export async function PATCH(request: Request, { params }: Params) {
  try {
    const { id } = await params;
    await assertProjectAccess(id);

    const body = await readJson<StageBody>(request);
    if (!body?.stageId) return badRequest("stageId is required.");

    const { stageId, ...fields } = body;
    return NextResponse.json({ updated: await updateProjectStage(stageId, fields) });
  } catch (error) {
    return errorResponse(error);
  }
}

export async function DELETE(request: Request, { params }: Params) {
  try {
    const { id } = await params;
    await assertProjectAccess(id);

    const stageId = new URL(request.url).searchParams.get("stageId");
    if (!stageId) return badRequest("stageId is required.");

    const removed = await removeProjectStage(stageId);
    return removed ? NextResponse.json({ ok: true }) : notFound("Stage not found.");
  } catch (error) {
    return errorResponse(error);
  }
}
