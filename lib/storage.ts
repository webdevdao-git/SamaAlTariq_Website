import "server-only";
import { mkdir, rm, stat, writeFile } from "node:fs/promises";
import path from "node:path";
import { randomUUID } from "node:crypto";

/**
 * Private file storage — the replacement for the Supabase `project-images`
 * bucket and its RLS policies.
 *
 * Files live under STORAGE_DIR, which must sit OUTSIDE ./public so Next.js never
 * serves them statically. The only way out is /api/files/[...path], which checks
 * the caller's project access first. The Supabase path convention is preserved:
 *
 *   <project_id>/<filename>            → project images
 *   <project_id>/reports/<filename>    → project documents
 *
 * On Hostinger, point STORAGE_DIR at a directory beside the app (for example
 * /home/uXXXXXX/storage) so redeploying the app does not wipe uploaded files.
 */

export const STORAGE_DIR =
  process.env.STORAGE_DIR ?? path.join(process.cwd(), "storage");

const MAX_BYTES = Number(process.env.MAX_UPLOAD_BYTES ?? 15 * 1024 * 1024);

const ALLOWED_MIME = new Map<string, string>([
  ["image/jpeg", ".jpg"],
  ["image/png", ".png"],
  ["image/webp", ".webp"],
  ["image/avif", ".avif"],
  ["image/gif", ".gif"],
  ["application/pdf", ".pdf"],
  [
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    ".docx",
  ],
  ["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", ".xlsx"],
  ["text/plain", ".txt"],
]);

export class StorageError extends Error {
  status: number;

  constructor(status: number, message: string) {
    super(message);
    this.name = "StorageError";
    this.status = status;
  }
}

/**
 * Resolves a stored path to a real filesystem path, refusing anything that
 * escapes STORAGE_DIR. This is the traversal guard: a stored path such as
 * "../../etc/passwd" resolves outside the root and is rejected before any I/O.
 */
export function resolveStoragePath(storagePath: string): string {
  // turbopackIgnore: STORAGE_DIR points outside the project on purpose, so
  // there is nothing here for the bundler to trace — without this it walks the
  // whole tree and inflates the standalone output.
  const root = path.resolve(/* turbopackIgnore: true */ STORAGE_DIR);
  const target = path.resolve(root, storagePath);
  const withSep = root.endsWith(path.sep) ? root : root + path.sep;

  if (target !== root && !target.startsWith(withSep)) {
    throw new StorageError(400, "Invalid file path.");
  }
  return target;
}

/** The project id a stored path belongs to — mirrors storage.foldername(name)[1]. */
export function projectIdFromStoragePath(storagePath: string): string | null {
  const [first] = storagePath.split("/");
  return first || null;
}

function safeFilename(original: string, mime: string): string {
  const fallbackExt = ALLOWED_MIME.get(mime) ?? "";
  const ext = (path.extname(original) || fallbackExt).toLowerCase().slice(0, 10);
  const base = path
    .basename(original, path.extname(original))
    .replace(/[^a-zA-Z0-9._-]+/g, "-")
    .replace(/^-+|-+$/g, "")
    .slice(0, 60);

  // A uuid prefix keeps two uploads of "photo.jpg" from colliding.
  return `${randomUUID()}${base ? `-${base}` : ""}${ext}`;
}

export type SaveResult = { storagePath: string; bytes: number; filename: string };

/**
 * Writes an uploaded file and returns its storage path.
 * `folder` is "" for project images or "reports" for documents.
 */
export async function saveUpload(
  file: File,
  projectId: string,
  folder: "" | "reports" = "",
): Promise<SaveResult> {
  if (!/^[0-9a-f-]{36}$/i.test(projectId)) {
    throw new StorageError(400, "Invalid project id.");
  }
  if (file.size > MAX_BYTES) {
    throw new StorageError(
      413,
      `File is larger than the ${Math.round(MAX_BYTES / 1024 / 1024)} MB limit.`,
    );
  }
  if (!ALLOWED_MIME.has(file.type)) {
    throw new StorageError(415, `Unsupported file type: ${file.type || "unknown"}.`);
  }

  const filename = safeFilename(file.name || "upload", file.type);
  const storagePath = [projectId, folder, filename].filter(Boolean).join("/");
  const absolute = resolveStoragePath(storagePath);

  await mkdir(path.dirname(absolute), { recursive: true });
  const bytes = Buffer.from(await file.arrayBuffer());
  await writeFile(absolute, bytes);

  return { storagePath, bytes: bytes.length, filename };
}

export async function deleteStoredFile(storagePath: string): Promise<void> {
  await rm(resolveStoragePath(storagePath), { force: true });
}

/** Removes every file for a project — used when a project is hard-deleted. */
export async function deleteProjectFiles(projectId: string): Promise<void> {
  if (!/^[0-9a-f-]{36}$/i.test(projectId)) return;
  await rm(resolveStoragePath(projectId), { recursive: true, force: true });
}

export async function statStoredFile(storagePath: string) {
  try {
    return await stat(/* turbopackIgnore: true */ resolveStoragePath(storagePath));
  } catch {
    return null;
  }
}

const CONTENT_TYPES: Record<string, string> = {
  ".jpg": "image/jpeg",
  ".jpeg": "image/jpeg",
  ".png": "image/png",
  ".webp": "image/webp",
  ".avif": "image/avif",
  ".gif": "image/gif",
  ".pdf": "application/pdf",
  ".docx":
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
  ".xlsx": "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
  ".txt": "text/plain; charset=utf-8",
};

export function contentTypeFor(storagePath: string): string {
  return (
    CONTENT_TYPES[path.extname(storagePath).toLowerCase()] ??
    "application/octet-stream"
  );
}
