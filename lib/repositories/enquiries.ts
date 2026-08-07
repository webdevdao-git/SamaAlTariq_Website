import "server-only";
import { randomUUID } from "node:crypto";
import { execute, query, queryOne } from "@/lib/db";
import type { EnquiryInput } from "@/lib/enquiry";
import { requireAdmin } from "@/lib/auth/session";

/**
 * Enquiries.
 *
 * Postgres policy this replaces: no public SELECT; `enquiries_admin_all` gave
 * admins full access, and inserts came from the service-role Edge Function.
 * Here `create` is deliberately unauthenticated (it is the public contact form)
 * while every read and mutation goes through requireAdmin().
 */

export type EnquiryRecord = {
  id: string;
  name: string;
  email: string;
  phone: string | null;
  location: string | null;
  project_type: string | null;
  project_brief: string | null;
  status: "new" | "read" | "archived";
  created_at: Date;
};

/** Public — called by the landing-page form. */
export async function createEnquiry(
  enquiry: EnquiryInput,
  meta: { ip: string; userAgent: string | null },
): Promise<string> {
  const id = randomUUID();
  await execute(
    `INSERT INTO enquiries
       (id, name, email, phone, location, project_type, project_brief, ip, user_agent)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
    [
      id,
      enquiry.name,
      enquiry.email,
      enquiry.phone || null,
      enquiry.location || null,
      enquiry.projectType || null,
      enquiry.projectBrief || null,
      meta.ip,
      meta.userAgent?.slice(0, 255) ?? null,
    ],
  );
  return id;
}

/** Admin only. */
export async function listEnquiries({
  status,
  limit = 100,
  offset = 0,
}: {
  status?: EnquiryRecord["status"];
  limit?: number;
  offset?: number;
} = {}): Promise<EnquiryRecord[]> {
  await requireAdmin();

  // LIMIT/OFFSET cannot be bound as placeholders in a prepared statement, so
  // they are coerced to integers and clamped rather than interpolated raw.
  const take = Math.min(Math.max(Math.trunc(limit) || 0, 1), 200);
  const skip = Math.max(Math.trunc(offset) || 0, 0);

  if (status) {
    return query<EnquiryRecord>(
      `SELECT id, name, email, phone, location, project_type, project_brief, status, created_at
         FROM enquiries WHERE status = ?
        ORDER BY created_at DESC LIMIT ${take} OFFSET ${skip}`,
      [status],
    );
  }

  return query<EnquiryRecord>(
    `SELECT id, name, email, phone, location, project_type, project_brief, status, created_at
       FROM enquiries
      ORDER BY created_at DESC LIMIT ${take} OFFSET ${skip}`,
  );
}

/** Admin only. */
export async function getEnquiry(id: string): Promise<EnquiryRecord | null> {
  await requireAdmin();
  return queryOne<EnquiryRecord>(
    `SELECT id, name, email, phone, location, project_type, project_brief, status, created_at
       FROM enquiries WHERE id = ?`,
    [id],
  );
}

/** Admin only. */
export async function setEnquiryStatus(
  id: string,
  status: EnquiryRecord["status"],
): Promise<boolean> {
  await requireAdmin();
  const result = await execute(`UPDATE enquiries SET status = ? WHERE id = ?`, [
    status,
    id,
  ]);
  return result.affectedRows > 0;
}
