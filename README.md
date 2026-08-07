# Sama Al Tariq — Building Contracting L.L.C.

Next.js 16 (App Router) + Node.js, built from the Figma file
[Sama Al Tariq — Landing Page redesign](https://www.figma.com/design/LVe2rjX2pvynHYt3MrC7pV/Sama-Al-Tariq?node-id=50-2119)
(frame `1195:2`), with a MySQL backend for Hostinger.

- **Frontend** — the landing page, nine sections, fully responsive.
- **Backend** — Node.js route handlers over Hostinger MySQL: public enquiries,
  authentication, client accounts, projects, project media, and private file
  downloads. This is a port of the previous Supabase backend; see
  [Backend](#backend) for what changed and why.

---

## Quick start

```bash
npm install
cp .env.example .env      # fill in MYSQL_*, AUTH_SECRET, SMTP_*
npm run db:init           # creates the tables
npm run admin:create -- admin@samaaltariq.org "Site Admin"
npm run dev               # http://localhost:3000
```

| Script | What it does |
| --- | --- |
| `npm run dev` | Development server |
| `npm run build` | Production build; `postbuild` copies `public/` and `.next/static` into the standalone output |
| `npm start` | Runs `.next/standalone/server.js` — the process Hostinger keeps alive |
| `npm run check` | typecheck → lint → build |
| `npm run db:init` | Applies `db/schema.sql` (idempotent) |
| `npm run admin:create` | Seeds the first administrator |

Deployment: **[DEPLOY-HOSTINGER.md](DEPLOY-HOSTINGER.md)**.

---

## Frontend

### Structure

```
app/
  layout.tsx            fonts, metadata, the .js class for scroll reveals
  page.tsx              composes the nine sections
  globals.css           design tokens + component classes
  api/                  the Node backend (see below)
components/
  sections/             hero, about, clients, projects, services,
                        process, precision, inquiry-section, footer
  site-header.tsx       MENU / lockup / ENQUIRE + full-screen nav overlay
  inquiry-form.tsx      the contact form, posts to /api/enquiries
  icons.tsx             SVGs transcribed from the Figma exports
  reveal.tsx            shared scroll-reveal observer
content/site.ts         every string and image path on the page
lib/                    db, auth, repositories, storage, mail, validation
db/schema.sql           MySQL schema
scripts/                db:init, admin:create, standalone packaging
```

All copy lives in `content/site.ts` — edit that, not the components.

### Design fidelity

The Figma frame is 1728px wide with 80px gutters. Type is set with
`clamp(min, <figma px ÷ 1728 × 100>vw, max)` so the whole page scales
proportionally rather than jumping at breakpoints. Tokens are in `globals.css`:

| Token | Value | Used for |
| --- | --- | --- |
| `--color-teal` | `#3FA7B3` | accents, buttons, footer |
| `--color-ink` | `#171717` | body text |
| `--color-night` | `#161616` | the PRECISION section |
| `--color-mist` | `#F9F9F9` | client logo strip |
| `--color-hairline` | `#EFE6E6` | form underlines |

### Fonts

| Figma | Shipped | Note |
| --- | --- | --- |
| Manrope | Manrope | exact |
| Cormorant Garamond | Cormorant Garamond | exact |
| Aeonik | Manrope | licensed; same geometric-grotesque voice |
| FONTSPRING DEMO – Juana Alt Medium | **Playfair Display** | **substitute** |

Juana is a Latinotype commercial face and the Figma file uses the demo. Playfair
Display is the closest freely-licensed match. To swap in the licensed font,
replace the one `next/font` loader in `app/layout.tsx` — everything else
references the `--font-display` variable only.

### Interaction decisions

Two sections are drawn in Figma as stacked static states. Both are built as the
component those states imply:

- **Our Expertise** — two 1728×980 panels differing only in which tab pill is
  active and which headline shows. Built as one tabbed switcher with all six
  services (all six tab labels are in the file).
- **Our Process** — one step (`01`) is drawn. Built as a stepper with a
  numbered rail.

### Motion

Interaction patterns modelled on [studiodado.com](https://www.studiodado.com/),
measured from the live site and reimplemented against this design system —
the behaviour is borrowed, none of the markup or styling is.

| Where | Behaviour | Component |
| --- | --- | --- |
| Entry | Full-screen teal curtain wiping upward via `clip-path: inset(0 0 100%)` | `motion/preloader.tsx` |
| Hero | Section sinks at `scrollY × 0.25`; photo pushes in `1 → 1.1` over one viewport | `motion/hero-parallax.tsx` |
| Headings | Lines rise out of clipping masks on a stagger | `motion/split-lines.tsx` |
| Projects | Per-card vertical drift keyed to viewport progress | `motion/parallax.tsx` |
| Page | Lenis smooth scroll | `motion/smooth-scroll.tsx` |

Everything scroll-driven shares one rAF loop (`lib/scroll-engine.ts`), which
reads `scrollY`/`innerHeight` once per frame and fans them out. Effects write
only `transform`, so animation stays on the compositor.

Guard rails, because motion this heavy fails badly when it fails:

- **`prefers-reduced-motion`** disables all of it — no curtain, no parallax, no
  split lines, and Lenis never initialises.
- **No JavaScript** — a `<noscript>` rule hides the curtain, unlocks scroll, and
  shows every `.reveal` block. The page stays fully readable.
- **The curtain is server-rendered**, so it paints with the HTML instead of
  dropping over an already-visible page. It lifts on `document.fonts.ready`,
  capped at 1.6s, so a slow connection never traps anyone behind it.
- **Scroll locking is CSS**, keyed off `data-lifting`, so it applies from first
  paint rather than after hydration.
- **`SplitLines` keeps text accessible** — the container carries the full string
  as `aria-label` and the visual line spans are `aria-hidden`, so a screen
  reader reads a sentence rather than fragments. Lines are measured from real
  layout and re-measured on resize.

Verified against the reference numbers: sink and zoom match at every scroll
offset tested (0/200/450/800px).

### Content still to confirm

Marked `PLACEHOLDER` in `content/site.ts`:

- **Service headlines 03–06** — Figma names the six tabs but only writes
  headlines for Fit-Out Contracting and Design & Build.
- **Process steps 02–04** — only "Tender And Cost Estimation" exists.
- **Social URLs** — the footer links to Instagram/Facebook/LinkedIn home pages.
- **Service and process photography** — `public/images/services/*` and
  `public/images/process/*` currently reuse project photography. The originals
  are flattened into the Figma frames with their captions baked in, so they
  could not be extracted cleanly. Drop replacements in at the same paths;
  nothing else needs to change.

Every other image is the original asset exported from Figma, re-encoded to WebP
(≈32 MB of PNG → 1.3 MB).

---

## Backend

Node.js route handlers on Hostinger MySQL, ported from the previous
Supabase/Postgres backend. Same tables, same access rules, same two emails.

### What changed in the port

| Supabase | Here | Why |
| --- | --- | --- |
| `auth.users` + `public.profiles` | one `profiles` table | this app owns credentials now, so the hash lives beside the profile |
| Supabase Auth | scrypt (`node:crypto`) + signed JWT cookie | no native module to compile on a shared host |
| Row Level Security | scoping in `lib/repositories/*` | MySQL has no RLS — the WHERE clause *is* the policy |
| Storage bucket + object policies | private files under `STORAGE_DIR` | served only via `/api/files`, which checks project access |
| Edge Functions | route handlers | `send-enquiry` → `POST /api/enquiries`, `create-client` → `POST /api/admin/clients`, `update-client` → `PATCH /api/admin/clients/:id` |
| `denomailer` | `nodemailer` | same SMTP mailbox |

**The authorization rules did not change.** Because MySQL cannot enforce them,
they are enforced in one layer instead: every read goes through a repository
that appends the caller's scope, and every write calls `requireAdmin()`. No
route touches these tables directly — that is what keeps a forgotten filter from
becoming a data leak.

The session cookie carries only the user id; role and permissions are re-read
from the database per request, so revoking an account takes effect immediately.

### API

| Method | Route | Access |
| --- | --- | --- |
| `POST` | `/api/enquiries` | public (rate-limited, honeypot) |
| `GET` | `/api/enquiries` | admin |
| `POST` | `/api/auth/login` | public (rate-limited) |
| `POST` | `/api/auth/logout` | any |
| `GET` | `/api/auth/me` | any |
| `POST` | `/api/auth/password` | signed in (re-authenticates first) |
| `GET` `POST` | `/api/admin/clients` | admin |
| `GET` `PATCH` `DELETE` | `/api/admin/clients/:id` | admin |
| `GET` | `/api/projects` | admin: all · client: own, live only |
| `POST` | `/api/projects` | admin |
| `GET` `PATCH` `DELETE` | `/api/projects/:id` | read scoped · write admin |
| `GET` `POST` `DELETE` | `/api/projects/:id/images` | read scoped · write admin |
| `GET` `POST` `DELETE` | `/api/projects/:id/documents` | read scoped · write admin |
| `GET` `POST` `DELETE` | `/api/projects/:id/updates` | read scoped · write admin |
| `GET` `POST` `PATCH` `DELETE` | `/api/projects/:id/stages` | read scoped · write admin |
| `GET` | `/api/files/<project_id>/...` | scoped; `?download=1` honours `can_download` |

`DELETE /api/projects/:id` soft-deletes (`deleted_at`); add `?purge=true` to
hard-delete the row and its files.

### Enquiries

Saved to MySQL first, then emailed. If SMTP fails the visitor still gets a
success response (HTTP 202) because the lead is already stored — a broken
mailbox should never ask someone to fill the form in again. If SMTP is not
configured at all, the row is still saved and a warning is logged.

---

## Security notes

- Every SQL value is a bound parameter. `LIMIT`/`OFFSET`, which cannot be bound,
  are coerced to integers and clamped.
- Passwords: scrypt, `N=16384`, per-user salt, constant-time comparison. Login
  runs a dummy verification for unknown accounts so timing does not reveal
  whether an email exists.
- Session cookie is `httpOnly`, `sameSite=lax`, and `secure` in production.
- Uploads are allow-listed by MIME type and size-capped; stored names are
  UUID-prefixed. `resolveStoragePath()` rejects anything resolving outside
  `STORAGE_DIR`, so a crafted path cannot traverse out.
- Private files are served with `Cache-Control: private, no-store`.
- Rate limits: 5 enquiries / 10 min and 10 sign-in attempts / 15 min per IP.
- API errors are normalised in `lib/api.ts`; database and stack details are
  logged server-side, never returned.
