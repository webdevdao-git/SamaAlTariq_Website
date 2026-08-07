import "server-only";
import { randomUUID } from "node:crypto";
import { execute, query, queryOne } from "@/lib/db";
import { hashPassword } from "@/lib/auth/password";
import { requireAdmin, requireUser, type Role } from "@/lib/auth/session";

/**
 * Profiles — users and their role.
 *
 * Postgres policies this replaces:
 *   profiles_select      → read own profile, or anything if admin
 *   profiles_admin_write → all writes are admin-only
 *
 * The Supabase `handle_new_user` trigger that mirrored auth.users into profiles
 * has no equivalent here: this app owns the credentials, so createProfile is the
 * single place a user comes into existence.
 */

export type ProfileRecord = {
  id: string;
  email: string;
  full_name: string | null;
  username: string | null;
  phone: string | null;
  job_title: string | null;
  can_download: number;
  role: Role;
  must_change_password: number;
  last_login_at: Date | null;
  created_at: Date;
};

const PUBLIC_COLUMNS = `id, email, full_name, username, phone, job_title,
                        can_download, role, must_change_password,
                        last_login_at, created_at`;

/** Internal — used by the login route only; includes the password hash. */
export async function findAuthProfileByIdentifier(identifier: string) {
  return queryOne<{ id: string; password_hash: string }>(
    `SELECT id, password_hash FROM profiles
      WHERE email = ? OR username = ? LIMIT 1`,
    [identifier, identifier],
  );
}

export async function touchLastLogin(id: string): Promise<void> {
  await execute(`UPDATE profiles SET last_login_at = CURRENT_TIMESTAMP WHERE id = ?`, [
    id,
  ]);
}

/** Own profile, or any profile when the caller is an admin. */
export async function getProfile(id: string): Promise<ProfileRecord | null> {
  const user = await requireUser();
  if (user.role !== "admin" && user.id !== id) return null;

  return queryOne<ProfileRecord>(
    `SELECT ${PUBLIC_COLUMNS} FROM profiles WHERE id = ?`,
    [id],
  );
}

/** Admin only. */
export async function listProfiles(role?: Role): Promise<ProfileRecord[]> {
  await requireAdmin();
  if (role) {
    return query<ProfileRecord>(
      `SELECT ${PUBLIC_COLUMNS} FROM profiles WHERE role = ? ORDER BY created_at DESC`,
      [role],
    );
  }
  return query<ProfileRecord>(
    `SELECT ${PUBLIC_COLUMNS} FROM profiles ORDER BY created_at DESC`,
  );
}

export type CreateProfileInput = {
  email: string;
  password: string;
  fullName?: string | null;
  username?: string | null;
  phone?: string | null;
  jobTitle?: string | null;
  canDownload?: boolean;
  role?: Role;
  mustChangePassword?: boolean;
};

/**
 * Admin only — the `create-client` Edge Function equivalent.
 * Callers that seed the very first admin (scripts/create-admin.mjs) go straight
 * to SQL instead, since there is no admin session to authorise against yet.
 */
export async function createProfile(input: CreateProfileInput): Promise<string> {
  await requireAdmin();
  return insertProfile(input);
}

/** Unauthenticated insert. Only for the seed script and createProfile above. */
export async function insertProfile(input: CreateProfileInput): Promise<string> {
  const id = randomUUID();
  const passwordHash = await hashPassword(input.password);

  await execute(
    `INSERT INTO profiles
       (id, email, password_hash, full_name, username, phone, job_title,
        can_download, role, must_change_password)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
    [
      id,
      input.email,
      passwordHash,
      input.fullName ?? null,
      input.username ?? null,
      input.phone ?? null,
      input.jobTitle ?? null,
      input.canDownload ? 1 : 0,
      input.role ?? "client",
      input.mustChangePassword ? 1 : 0,
    ],
  );

  return id;
}

export type UpdateProfileInput = Partial<
  Pick<
    CreateProfileInput,
    "email" | "fullName" | "username" | "phone" | "jobTitle" | "canDownload" | "role"
  >
>;

/** Admin only — the `update-client` Edge Function equivalent. */
export async function updateProfile(
  id: string,
  input: UpdateProfileInput,
): Promise<boolean> {
  await requireAdmin();

  const columns: Record<string, string> = {
    email: "email",
    fullName: "full_name",
    username: "username",
    phone: "phone",
    jobTitle: "job_title",
    canDownload: "can_download",
    role: "role",
  };

  const sets: string[] = [];
  const values: unknown[] = [];

  for (const [key, column] of Object.entries(columns)) {
    const value = input[key as keyof UpdateProfileInput];
    if (value === undefined) continue;
    sets.push(`${column} = ?`);
    values.push(key === "canDownload" ? (value ? 1 : 0) : value);
  }

  if (sets.length === 0) return false;

  values.push(id);
  const result = await execute(
    `UPDATE profiles SET ${sets.join(", ")} WHERE id = ?`,
    values,
  );
  return result.affectedRows > 0;
}

/** Admin only. */
export async function setProfilePassword(
  id: string,
  password: string,
  { mustChange = false } = {},
): Promise<void> {
  await requireAdmin();
  await writePassword(id, password, mustChange);
}

/** A user changing their own password — no admin rights required. */
export async function changeOwnPassword(password: string): Promise<void> {
  const user = await requireUser();
  await writePassword(user.id, password, false);
}

async function writePassword(id: string, password: string, mustChange: boolean) {
  await execute(
    `UPDATE profiles SET password_hash = ?, must_change_password = ? WHERE id = ?`,
    [await hashPassword(password), mustChange ? 1 : 0, id],
  );
}

/** Admin only. Projects fall back to client_id NULL via ON DELETE SET NULL. */
export async function deleteProfile(id: string): Promise<boolean> {
  const admin = await requireAdmin();
  if (admin.id === id) {
    throw new Error("You cannot delete the account you are signed in with.");
  }
  const result = await execute(`DELETE FROM profiles WHERE id = ?`, [id]);
  return result.affectedRows > 0;
}
