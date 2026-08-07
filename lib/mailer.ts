import "server-only";
import nodemailer from "nodemailer";
import type { EnquiryInput } from "@/lib/enquiry";

/**
 * SMTP delivery — the replacement for the denomailer calls inside the Supabase
 * Edge Functions. Same two messages: an enquiry notification to the business
 * inbox, and a credentials email when an admin creates a client account.
 *
 * On Hostinger, use a mailbox created in hPanel → Emails:
 *   SMTP_HOST=smtp.hostinger.com  SMTP_PORT=465  SMTP_SECURE=true
 *
 * If SMTP is not configured the enquiry route still succeeds — the row is
 * already committed to MySQL, and a misconfigured mailbox must never cost a lead.
 */

const escapeHtml = (value: string) =>
  value
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");

export function isMailConfigured() {
  return Boolean(
    process.env.SMTP_HOST && process.env.SMTP_USER && process.env.SMTP_PASS,
  );
}

let cached: nodemailer.Transporter | null = null;

function transporter() {
  if (cached) return cached;
  cached = nodemailer.createTransport({
    host: process.env.SMTP_HOST,
    port: Number(process.env.SMTP_PORT ?? 465),
    secure: (process.env.SMTP_SECURE ?? "true") === "true",
    auth: { user: process.env.SMTP_USER, pass: process.env.SMTP_PASS },
  });
  return cached;
}

function from() {
  const address = process.env.SMTP_FROM ?? process.env.SMTP_USER ?? "";
  const name = process.env.SMTP_FROM_NAME;
  if (!name || address.includes("<")) return address;
  return `${name} <${address}>`;
}

function table(rows: [string, string][]) {
  return `<table cellpadding="6" style="border-collapse:collapse">
    ${rows
      .map(
        ([k, v]) =>
          `<tr><td style="font-weight:600;vertical-align:top;white-space:nowrap">${escapeHtml(
            k,
          )}</td><td>${escapeHtml(v).replace(/\n/g, "<br>")}</td></tr>`,
      )
      .join("")}
  </table>`;
}

const shell = (heading: string, body: string) => `
  <div style="font-family:Arial,Helvetica,sans-serif;color:#171717;line-height:1.6">
    <h2 style="color:#3fa7b3;margin:0 0 16px">${escapeHtml(heading)}</h2>
    ${body}
  </div>`;

/** Notifies the business inbox of a new website enquiry. */
export async function sendEnquiryEmail(enquiry: EnquiryInput) {
  const rows: [string, string][] = [
    ["Name", enquiry.name],
    ["Email", enquiry.email],
    ["Phone", enquiry.phone || "—"],
    ["Property type", enquiry.projectType || "—"],
    ["Location", enquiry.location || "—"],
    ["Project brief", enquiry.projectBrief || "—"],
  ];

  await transporter().sendMail({
    from: from(),
    to: process.env.ENQUIRY_TO ?? process.env.SMTP_USER,
    replyTo: `${enquiry.name} <${enquiry.email}>`,
    subject: `New website enquiry — ${enquiry.name}`,
    text: rows.map(([k, v]) => `${k}: ${v}`).join("\n"),
    html: shell("New website enquiry", table(rows)),
  });
}

/** Sends a newly created client their sign-in details. */
export async function sendClientCredentialsEmail(input: {
  to: string;
  fullName: string | null;
  username: string | null;
  password: string;
  portalUrl: string;
}) {
  const rows: [string, string][] = [
    ["Portal", input.portalUrl],
    ["Username", input.username ?? input.to],
    ["Temporary password", input.password],
  ];

  await transporter().sendMail({
    from: from(),
    to: input.to,
    subject: "Your Sama Al Tariq client portal access",
    text:
      `Hello ${input.fullName ?? ""}\n\n` +
      `Your client portal account is ready.\n\n` +
      rows.map(([k, v]) => `${k}: ${v}`).join("\n") +
      `\n\nPlease sign in and change your password.`,
    html: shell(
      "Your client portal access",
      `<p>Hello ${escapeHtml(input.fullName ?? "")},</p>
       <p>Your client portal account is ready.</p>
       ${table(rows)}
       <p style="margin-top:16px">Please sign in and change your password.</p>`,
    ),
  });
}
