/**
 * Shape + validation for the public enquiry form. Field names match the
 * `enquiries` table (and the previous Supabase `send-enquiry` payload) so the
 * contract is unchanged: name, email, phone, location, projectType, projectBrief.
 *
 * This module is imported by both the client component and the API route. The
 * browser check is a convenience; the server re-runs it, and that is the copy
 * that counts.
 */

export type EnquiryInput = {
  name: string;
  email: string;
  phone: string;
  location: string;
  projectType: string;
  projectBrief: string;
  /** Honeypot. Anything other than an empty string means a bot filled it in. */
  company?: string;
};

export type EnquiryErrors = Partial<Record<keyof EnquiryInput, string>>;

const EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
/** Digits, spaces, +, -, () — permissive enough for UAE and international. */
const PHONE = /^[+(\d][\d\s\-()]{6,19}$/;

export const ENQUIRY_LIMITS = {
  name: 120,
  email: 254,
  phone: 32,
  location: 200,
  projectType: 120,
  projectBrief: 4000,
} as const;

export function validateEnquiry(raw: Partial<EnquiryInput>): EnquiryErrors {
  const errors: EnquiryErrors = {};

  const name = (raw.name ?? "").trim();
  if (name.length < 2) errors.name = "Please enter your name.";
  else if (name.length > ENQUIRY_LIMITS.name) errors.name = "Name is too long.";

  const email = (raw.email ?? "").trim();
  if (!email) errors.email = "Please enter your email.";
  else if (!EMAIL.test(email) || email.length > ENQUIRY_LIMITS.email)
    errors.email = "Please enter a valid email address.";

  const phone = (raw.phone ?? "").trim();
  if (!phone) errors.phone = "Please enter a phone number.";
  else if (!PHONE.test(phone)) errors.phone = "Please enter a valid phone number.";

  const projectType = (raw.projectType ?? "").trim();
  if (!projectType) errors.projectType = "Please choose a property type.";
  else if (projectType.length > ENQUIRY_LIMITS.projectType)
    errors.projectType = "Invalid property type.";

  if ((raw.location ?? "").trim().length > ENQUIRY_LIMITS.location)
    errors.location = "Location is too long.";

  if ((raw.projectBrief ?? "").trim().length > ENQUIRY_LIMITS.projectBrief)
    errors.projectBrief = "Description is too long.";

  return errors;
}

/** Trim and clamp every field before it reaches the database or a mail body. */
export function normalizeEnquiry(raw: Partial<EnquiryInput>): EnquiryInput {
  const take = (value: unknown, max: number) =>
    typeof value === "string" ? value.trim().slice(0, max) : "";

  return {
    name: take(raw.name, ENQUIRY_LIMITS.name),
    email: take(raw.email, ENQUIRY_LIMITS.email),
    phone: take(raw.phone, ENQUIRY_LIMITS.phone),
    location: take(raw.location, ENQUIRY_LIMITS.location),
    projectType: take(raw.projectType, ENQUIRY_LIMITS.projectType),
    projectBrief: take(raw.projectBrief, ENQUIRY_LIMITS.projectBrief),
    company: take(raw.company, 200),
  };
}
