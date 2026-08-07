import "server-only";
import { randomUUID } from "node:crypto";
import { execute, query, queryOne } from "@/lib/db";
import {
  AuthError,
  requireAdmin,
  requireUser,
  type SessionUser,
} from "@/lib/auth/session";

/**
 * Projects.
 *
 * Postgres policies this replaces:
 *   projects_select    → admin sees everything; a client sees its own rows that
 *                        are not soft-deleted
 *   projects_admin_all → every write is admin-only
 *
 * MySQL has no row-level security, so the WHERE clause is the policy. Every read
 * goes through `scopeClause`, which appends the client filter for non-admins —
 * that way a missing filter is impossible to forget at a call site.
 */

export type ProjectStatus = "Planning" | "In Progress" | "On Hold" | "Completed";

export type ProjectRecord = {
  id: string;
  client_id: string | null;
  title: string;
  description: string | null;
  location: string | null;
  status: ProjectStatus;
  progress: number;
  start_date: string | null;
  due_date: string | null;
  project_type: string | null;
  created_at: Date;
  updated_at: Date;
  deleted_at: Date | null;
};

const COLUMNS = `id, client_id, title, description, location, status, progress,
                 start_date, due_date, project_type, created_at, updated_at, deleted_at`;

/** The RLS `projects_select` predicate, expressed as SQL + bound params. */
function scopeClause(user: SessionUser): { sql: string; params: unknown[] } {
  if (user.role === "admin") return { sql: "1 = 1", params: [] };
  return { sql: "client_id = ? AND deleted_at IS NULL", params: [user.id] };
}

export async function listProjects({
  clientId,
  includeDeleted = false,
}: { clientId?: string; includeDeleted?: boolean } = {}): Promise<ProjectRecord[]> {
  const user = await requireUser();
  const scope = scopeClause(user);

  const where = [scope.sql];
  const params = [...scope.params];

  if (clientId) {
    where.push("client_id = ?");
    params.push(clientId);
  }
  // Only an admin can ask to see soft-deleted rows; for a client the scope
  // clause already excludes them.
  if (!includeDeleted || user.role !== "admin") {
    where.push("deleted_at IS NULL");
  }

  return query<ProjectRecord>(
    `SELECT ${COLUMNS} FROM projects
      WHERE ${where.join(" AND ")}
      ORDER BY created_at DESC`,
    params,
  );
}

export async function getProject(id: string): Promise<ProjectRecord | null> {
  const user = await requireUser();
  const scope = scopeClause(user);

  return queryOne<ProjectRecord>(
    `SELECT ${COLUMNS} FROM projects WHERE id = ? AND ${scope.sql}`,
    [id, ...scope.params],
  );
}

/**
 * Shared guard for everything hanging off a project (images, documents,
 * updates, stages). Mirrors the `exists (select 1 from projects …)` sub-query
 * that each child policy used.
 */
export async function assertProjectAccess(projectId: string): Promise<SessionUser> {
  const user = await requireUser();
  const scope = scopeClause(user);

  const row = await queryOne<{ id: string }>(
    `SELECT id FROM projects WHERE id = ? AND ${scope.sql}`,
    [projectId, ...scope.params],
  );

  if (!row) throw new AuthError(404, "Project not found.");
  return user;
}

export type CreateProjectInput = {
  clientId?: string | null;
  title: string;
  description?: string | null;
  location?: string | null;
  status?: ProjectStatus;
  progress?: number;
  startDate?: string | null;
  dueDate?: string | null;
  projectType?: string | null;
};

export async function createProject(input: CreateProjectInput): Promise<string> {
  await requireAdmin();
  const id = randomUUID();

  await execute(
    `INSERT INTO projects
       (id, client_id, title, description, location, status, progress,
        start_date, due_date, project_type)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
    [
      id,
      input.clientId ?? null,
      input.title,
      input.description ?? null,
      input.location ?? null,
      input.status ?? "In Progress",
      clampProgress(input.progress ?? 0),
      input.startDate ?? null,
      input.dueDate ?? null,
      input.projectType ?? null,
    ],
  );

  return id;
}

export async function updateProject(
  id: string,
  input: Partial<CreateProjectInput>,
): Promise<boolean> {
  await requireAdmin();

  const columns: Record<keyof CreateProjectInput, string> = {
    clientId: "client_id",
    title: "title",
    description: "description",
    location: "location",
    status: "status",
    progress: "progress",
    startDate: "start_date",
    dueDate: "due_date",
    projectType: "project_type",
  };

  const sets: string[] = [];
  const values: unknown[] = [];

  for (const [key, column] of Object.entries(columns) as [
    keyof CreateProjectInput,
    string,
  ][]) {
    const value = input[key];
    if (value === undefined) continue;
    sets.push(`${column} = ?`);
    values.push(key === "progress" ? clampProgress(value as number) : value);
  }

  if (sets.length === 0) return false;

  values.push(id);
  const result = await execute(
    `UPDATE projects SET ${sets.join(", ")} WHERE id = ?`,
    values,
  );
  return result.affectedRows > 0;
}

/** Soft delete, matching the original `deleted_at` convention. */
export async function archiveProject(id: string): Promise<boolean> {
  await requireAdmin();
  const result = await execute(
    `UPDATE projects SET deleted_at = CURRENT_TIMESTAMP WHERE id = ? AND deleted_at IS NULL`,
    [id],
  );
  return result.affectedRows > 0;
}

export async function restoreProject(id: string): Promise<boolean> {
  await requireAdmin();
  const result = await execute(
    `UPDATE projects SET deleted_at = NULL WHERE id = ?`,
    [id],
  );
  return result.affectedRows > 0;
}

/** Hard delete. Child rows cascade; files are removed by the caller. */
export async function destroyProject(id: string): Promise<boolean> {
  await requireAdmin();
  const result = await execute(`DELETE FROM projects WHERE id = ?`, [id]);
  return result.affectedRows > 0;
}

function clampProgress(value: number): number {
  if (!Number.isFinite(value)) return 0;
  return Math.min(100, Math.max(0, Math.trunc(value)));
}
