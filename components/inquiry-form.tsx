"use client";

import { useId, useState, type ChangeEvent } from "react";
import { ArrowRightPill, ChevronDown } from "@/components/icons";
import { inquiry } from "@/content/site";
import { validateEnquiry, type EnquiryErrors } from "@/lib/enquiry";

/**
 * The Figma inquiry card, wired to POST /api/enquiries.
 * Field names match the `enquiries` table: name, phone, email, projectType
 * (Property Type), location (Project Location in Dubai), projectBrief
 * (Brief Project Description).
 */

type Status = "idle" | "submitting" | "success" | "error";

const initial = {
  name: "",
  phone: "",
  email: "",
  projectType: "",
  location: "",
  projectBrief: "",
  company: "", // honeypot — real people never see or fill this
};

type Values = typeof initial;

export function InquiryForm() {
  const uid = useId();
  const [values, setValues] = useState<Values>(initial);
  const [errors, setErrors] = useState<EnquiryErrors>({});
  const [status, setStatus] = useState<Status>("idle");
  const [message, setMessage] = useState("");

  const set = (key: keyof Values) => (value: string) => {
    setValues((v) => ({ ...v, [key]: value }));
    setErrors((e) => ({ ...e, [key]: undefined }));
  };

  async function onSubmit(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();

    const found = validateEnquiry(values);
    if (Object.keys(found).length > 0) {
      setErrors(found);
      setStatus("error");
      setMessage("Please check the highlighted fields.");
      return;
    }

    setStatus("submitting");
    setMessage("");

    try {
      const res = await fetch("/api/enquiries", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(values),
      });
      const data = (await res.json().catch(() => ({}))) as {
        message?: string;
        errors?: EnquiryErrors;
      };

      if (!res.ok) {
        setErrors(data.errors ?? {});
        setStatus("error");
        setMessage(data.message ?? "We could not send your enquiry. Please try again.");
        return;
      }

      setValues(initial);
      setStatus("success");
      setMessage(
        data.message ?? "Thank you — your enquiry has been sent. We'll be in touch shortly.",
      );
    } catch {
      setStatus("error");
      setMessage("Network error. Please try again, or email us directly.");
    }
  }

  const fieldError = (key: keyof EnquiryErrors) =>
    errors[key] ? (
      <span
        id={`${uid}-${key}-error`}
        role="alert"
        className="mt-2 block text-sm text-[#c0392b]"
      >
        {errors[key]}
      </span>
    ) : null;

  const fieldProps = (key: keyof Values) => ({
    id: `${uid}-${key}`,
    name: key,
    value: values[key],
    onChange: (
      e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>,
    ) => set(key)(e.target.value),
    "aria-invalid": Boolean(errors[key as keyof EnquiryErrors]),
    "aria-describedby": errors[key as keyof EnquiryErrors]
      ? `${uid}-${key}-error`
      : undefined,
    className: "field",
  });

  const labelClass =
    "mb-[clamp(1.25rem,2.3vw,40px)] block text-fluid-body text-ink";

  return (
    <form
      onSubmit={onSubmit}
      noValidate
      className="flex flex-1 flex-col gap-[clamp(1.75rem,3.24vw,56px)]"
    >
      <div className="grid gap-[clamp(1.75rem,3.24vw,56px)] sm:grid-cols-2 sm:gap-x-[clamp(1rem,1.29vw,22px)]">
        <div>
          <label htmlFor={`${uid}-name`} className={labelClass}>
            Name
          </label>
          <input type="text" autoComplete="name" required {...fieldProps("name")} />
          {fieldError("name")}
        </div>

        <div>
          <label htmlFor={`${uid}-phone`} className={labelClass}>
            Phone Number
          </label>
          <input type="tel" autoComplete="tel" required {...fieldProps("phone")} />
          {fieldError("phone")}
        </div>

        <div>
          <label htmlFor={`${uid}-email`} className={labelClass}>
            Email
          </label>
          <input type="email" autoComplete="email" required {...fieldProps("email")} />
          {fieldError("email")}
        </div>

        <div>
          <label
            htmlFor={`${uid}-projectType`}
            className={`${labelClass} flex items-center justify-between gap-2`}
          >
            Property Type
            <ChevronDown className="shrink-0 text-ink" />
          </label>
          <select {...fieldProps("projectType")} className="field appearance-none">
            <option value="">Select…</option>
            {inquiry.propertyTypes.map((t) => (
              <option key={t} value={t}>
                {t}
              </option>
            ))}
          </select>
          {fieldError("projectType")}
        </div>
      </div>

      <div>
        <label htmlFor={`${uid}-location`} className={labelClass}>
          Project Location in Dubai
        </label>
        <input type="text" {...fieldProps("location")} />
        {fieldError("location")}
      </div>

      <div>
        <label htmlFor={`${uid}-projectBrief`} className={labelClass}>
          Brief Project Description
        </label>
        <textarea rows={2} {...fieldProps("projectBrief")} className="field resize-none" />
        {fieldError("projectBrief")}
      </div>

      {/* Honeypot: off-screen and hidden from assistive tech, irresistible to bots */}
      <div aria-hidden className="absolute left-[-9999px] h-0 w-0 overflow-hidden">
        <label htmlFor={`${uid}-company`}>Company</label>
        <input
          id={`${uid}-company`}
          name="company"
          type="text"
          tabIndex={-1}
          autoComplete="off"
          value={values.company}
          onChange={(e) => set("company")(e.target.value)}
        />
      </div>

      <div className="flex flex-wrap items-center gap-4">
        <button
          type="submit"
          disabled={status === "submitting"}
          className="pill group disabled:opacity-60"
        >
          {status === "submitting" ? "Sending…" : inquiry.submit}
          <ArrowRightPill className="transition-transform duration-300 group-hover:translate-x-0.5" />
        </button>

        {message && (
          <p
            role="status"
            aria-live="polite"
            className={`text-fluid-sm ${
              status === "success" ? "text-teal" : "text-[#c0392b]"
            }`}
          >
            {message}
          </p>
        )}
      </div>
    </form>
  );
}
