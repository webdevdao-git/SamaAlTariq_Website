import "server-only";
import { randomUUID } from "node:crypto";
import { execute, query, queryOne } from "@/lib/db";
import { requireAdmin } from "@/lib/auth/session";
import { assertProjectAccess } from "@/lib/repositories/projects";

/**
 * Everything that hangs off a project: images, documents, timeline updates and
 * stages.
 *
 * Postgres policies this replaces — images_select, documents_select,
 * updates_select, stages_select (client may read rows belonging to its own
 * live project) and the matching *_admin_all write policies. Each read here
 * calls assertProjectAccess() first, which is the same predicate; each write
 * calls requireAdmin().
 */

export type ProjectImage = {
  id: string;
  project_id: string;
  storage_path: string;
  caption: string | null;
  created_at: Date;
};

export type ProjectDocument = {
  id: string;
  project_id: string;
  storage_path: string;
  name: string;
  created_at: Date;
};

export type ProjectUpdate = {
  id: string;
  project_id: string;
  note: string;
  created_at: Date;
};

export type StageStatus = "Pending" | "In Progress" | "Completed";

export type ProjectStage = {
  id: string;
  project_id: string;
  name: string;
  status: StageStatus;
  target_date: string | null;
  sort_order: number;
  created_at: Date;
};

/* ---------------------------------------------------------------- images -- */

export async function listProjectImages(projectId: string): Promise<ProjectImage[]> {
  await assertProjectAccess(projectId);
  return query<ProjectImage>(
    `SELECT id, project_id, storage_path, caption, created_at
       FROM project_images WHERE project_id = ? ORDER BY created_at DESC`,
    [projectId],
  );
}

export async function addProjectImage(
  projectId: string,
  storagePath: string,
  caption: string | null,
): Promise<string> {
  await requireAdmin();
  const id = randomUUID();
  await execute(
    `INSERT INTO project_images (id, project_id, storage_path, caption)
     VALUES (?, ?, ?, ?)`,
    [id, projectId, storagePath, caption],
  );
  return id;
}

/** Returns the storage path so the caller can delete the file too. */
export async function removeProjectImage(id: string): Promise<string | null> {
  await requireAdmin();
  const row = await queryOne<{ storage_path: string }>(
    `SELECT storage_path FROM project_images WHERE id = ?`,
    [id],
  );
  if (!row) return null;
  await execute(`DELETE FROM project_images WHERE id = ?`, [id]);
  return row.storage_path;
}

/* ------------------------------------------------------------- documents -- */

export async function listProjectDocuments(
  projectId: string,
): Promise<ProjectDocument[]> {
  await assertProjectAccess(projectId);
  return query<ProjectDocument>(
    `SELECT id, project_id, storage_path, name, created_at
       FROM project_documents WHERE project_id = ? ORDER BY created_at DESC`,
    [projectId],
  );
}

export async function addProjectDocument(
  projectId: string,
  storagePath: string,
  name: string,
): Promise<string> {
  await requireAdmin();
  const id = randomUUID();
  await execute(
    `INSERT INTO project_documents (id, project_id, storage_path, name)
     VALUES (?, ?, ?, ?)`,
    [id, projectId, storagePath, name],
  );
  return id;
}

export async function removeProjectDocument(id: string): Promise<string | null> {
  await requireAdmin();
  const row = await queryOne<{ storage_path: string }>(
    `SELECT storage_path FROM project_documents WHERE id = ?`,
    [id],
  );
  if (!row) return null;
  await execute(`DELETE FROM project_documents WHERE id = ?`, [id]);
  return row.storage_path;
}

/* --------------------------------------------------------------- updates -- */

export async function listProjectUpdates(
  projectId: string,
): Promise<ProjectUpdate[]> {
  await assertProjectAccess(projectId);
  return query<ProjectUpdate>(
    `SELECT id, project_id, note, created_at
       FROM project_updates WHERE project_id = ? ORDER BY created_at DESC`,
    [projectId],
  );
}

export async function addProjectUpdate(
  projectId: string,
  note: string,
): Promise<string> {
  await requireAdmin();
  const id = randomUUID();
  await execute(
    `INSERT INTO project_updates (id, project_id, note) VALUES (?, ?, ?)`,
    [id, projectId, note],
  );
  return id;
}

export async function removeProjectUpdate(id: string): Promise<boolean> {
  await requireAdmin();
  const result = await execute(`DELETE FROM project_updates WHERE id = ?`, [id]);
  return result.affectedRows > 0;
}

/* ---------------------------------------------------------------- stages -- */

export async function listProjectStages(projectId: string): Promise<ProjectStage[]> {
  await assertProjectAccess(projectId);
  return query<ProjectStage>(
    `SELECT id, project_id, name, status, target_date, sort_order, created_at
       FROM project_stages WHERE project_id = ? ORDER BY sort_order ASC, created_at ASC`,
    [projectId],
  );
}

export async function addProjectStage(
  projectId: string,
  stage: {
    name: string;
    status?: StageStatus;
    targetDate?: string | null;
    sortOrder?: number;
  },
): Promise<string> {
  await requireAdmin();
  const id = randomUUID();
  await execute(
    `INSERT INTO project_stages (id, project_id, name, status, target_date, sort_order)
     VALUES (?, ?, ?, ?, ?, ?)`,
    [
      id,
      projectId,
      stage.name,
      stage.status ?? "Pending",
      stage.targetDate ?? null,
      Math.trunc(stage.sortOrder ?? 0),
    ],
  );
  return id;
}

export async function updateProjectStage(
  id: string,
  stage: {
    name?: string;
    status?: StageStatus;
    targetDate?: string | null;
    sortOrder?: number;
  },
): Promise<boolean> {
  await requireAdmin();

  const columns = {
    name: "name",
    status: "status",
    targetDate: "target_date",
    sortOrder: "sort_order",
  } as const;

  const sets: string[] = [];
  const values: unknown[] = [];

  for (const [key, column] of Object.entries(columns) as [
    keyof typeof columns,
    string,
  ][]) {
    const value = stage[key];
    if (value === undefined) continue;
    sets.push(`${column} = ?`);
    values.push(key === "sortOrder" ? Math.trunc(value as number) : value);
  }

  if (sets.length === 0) return false;

  values.push(id);
  const result = await execute(
    `UPDATE project_stages SET ${sets.join(", ")} WHERE id = ?`,
    values,
  );
  return result.affectedRows > 0;
}

export async function removeProjectStage(id: string): Promise<boolean> {
  await requireAdmin();
  const result = await execute(`DELETE FROM project_stages WHERE id = ?`, [id]);
  return result.affectedRows > 0;
}
