import { NextResponse } from "next/server";
import { badRequest, errorResponse, readJson } from "@/lib/api";
import {
  createProject,
  listProjects,
  type CreateProjectInput,
} from "@/lib/repositories/projects";

/**
 * GET  /api/projects — admin sees all; a client sees only its own live projects
 *                      (the scoping lives in the repository).
 * POST /api/projects — admin only.
 */
export const runtime = "nodejs";
export const dynamic = "force-dynamic";

export async function GET(request: Request) {
  try {
    const params = new URL(request.url).searchParams;
    const projects = await listProjects({
      clientId: params.get("clientId") ?? undefined,
      includeDeleted: params.get("includeDeleted") === "true",
    });
    return NextResponse.json({ projects });
  } catch (error) {
    return errorResponse(error);
  }
}

export async function POST(request: Request) {
  try {
    const body = await readJson<CreateProjectInput>(request);
    if (!body?.title?.trim()) return badRequest("A project title is required.");

    const id = await createProject({ ...body, title: body.title.trim() });
    return NextResponse.json({ id }, { status: 201 });
  } catch (error) {
    return errorResponse(error);
  }
}
