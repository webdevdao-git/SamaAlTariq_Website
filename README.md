# Sama Al Tariq — Building Contracting L.L.C.

Laravel 13 + MySQL, built from the Figma file
[Sama Al Tariq — Landing Page redesign](https://www.figma.com/design/LVe2rjX2pvynHYt3MrC7pV/Sama-Al-Tariq?node-id=50-2119)
(frame `1195:2`), deployable to ordinary Hostinger shared hosting.

- **Landing page** — nine sections, fully responsive, scroll-driven motion.
- **Enquiry form** — validated server-side, stored in MySQL, emailed to sales.
- **Client portal** — projects, images, reports, stage timelines, private file
  downloads, and admin account management.

Deployment: **[DEPLOY-HOSTINGER.md](DEPLOY-HOSTINGER.md)**.

---

## Quick start

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate

# Local development uses SQLite; production uses MySQL.
touch database/database.sqlite
php artisan migrate --seed        # prints the admin password once

composer run dev                  # → http://localhost:8000
```

`composer run dev` starts everything at once: the PHP server, the Vite dev
server, the queue listener and log tailing.

**Open `http://localhost:8000`, not `:5173`.** Vite only serves the compiled
CSS and JS — Laravel serves the site and pulls assets from Vite in dev.

| Command | What it does |
| --- | --- |
| `composer run dev` | Everything: PHP server + Vite + queue + logs |
| `php artisan serve` | PHP server only (assets need `npm run dev` too) |
| `npm run dev` | Vite dev server only — does **not** serve the site |
| `npm run build` | Compile assets — **commit `public/build/`**, see below |
| `php artisan test` | Full test suite |
| `php artisan migrate --seed` | Schema + first administrator |

> **`public/build/` is committed on purpose.** Hostinger shared hosting has no
> Node runtime, so assets must be built locally and shipped in the repository.

---

## Structure

```
app/
  Http/Controllers/       PageController, EnquiryController,
                          Auth/, Portal/, Admin/
  Http/Requests/          StoreEnquiryRequest — validation + honeypot
  Models/                 User, Project, ProjectImage, ProjectDocument,
                          ProjectUpdate, ProjectStage, Enquiry
  Policies/               ProjectPolicy, UserPolicy, EnquiryPolicy
  Services/               ProjectFileStorage — private uploads
  Mail/                   EnquiryReceived, ClientCredentials
config/site.php           every string and image on the landing page
resources/views/
  layouts/                app (public), portal (authenticated)
  sections/               the nine landing-page sections
  components/             icon, project-card
resources/js/motion/      preloader, smooth-scroll, parallax, split-lines,
                          reveal, tabs, menu, fit-text
database/migrations/      schema
tests/Feature/            enquiry + authorization coverage
```

All copy lives in `config/site.php` — edit that, not the Blade views.

---

## Design fidelity

The Figma frame is 1728px wide with 80px gutters, giving a 1568px content
column. Type is set with `clamp(min, <figma px ÷ 1728 × 100>vw, max)` so the page
scales proportionally rather than jumping at breakpoints. Tokens live in
`resources/css/app.css`:

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
Display is the closest freely-licensed match. Swap it in
`resources/css/app.css` (`--font-display`) and `vite.config.js`; nothing else
references it.

Fonts are downloaded at build time and served from our own origin — one less
external request, and no font CDN in the critical path.

### Interaction decisions

Two sections are drawn in Figma as stacked static states, and are built as the
component those states imply:

- **Our Expertise** — two 1728×980 panels differing only in the active tab pill
  and headline. Built as one tabbed switcher with all six services.
- **Our Process** — one step (`01`) is drawn. Built as a stepper.

---

## Motion

Interaction patterns modelled on [studiodado.com](https://www.studiodado.com/),
measured from the live site and reimplemented — the behaviour is borrowed, none
of the markup or styling is.

| Where | Behaviour |
| --- | --- |
| Entry | Full-screen teal curtain wiping upward via `clip-path: inset(0 0 100%)` |
| Hero | Section sinks at `scrollY × 0.25`; photo pushes in `1 → 1.1` over one viewport |
| Headings | Lines rise out of clipping masks on a stagger |
| Projects | Per-card vertical drift keyed to viewport progress |
| Page | Lenis smooth scroll |

Everything scroll-driven shares one rAF loop (`motion/scroll-engine.js`) that
reads `scrollY`/`innerHeight` once per frame and fans them out. Effects write
only `transform`, so animation stays on the compositor.

Guard rails, because motion this heavy fails badly when it fails:

- **`prefers-reduced-motion`** disables all of it, Lenis included.
- **No JavaScript** — a `<noscript>` rule in the layout hides the curtain,
  unlocks scroll, and shows every `.reveal` block. The page stays fully
  readable, and the enquiry form still submits: it is a plain POST.
- **The curtain is server-rendered**, so it paints with the HTML rather than
  dropping over an already-visible page. It lifts on `document.fonts.ready`,
  capped at 1.6s.
- **Scroll locking is CSS**, keyed off `data-lifting`, so it holds from first
  paint rather than after JavaScript boots.
- **Split text stays accessible** — the element keeps the full string as
  `aria-label` and the generated line spans are `aria-hidden`, so a screen
  reader reads a sentence rather than fragments. Lines are measured from real
  layout and re-measured on resize.
- **The footer wordmark is sized by measurement**, not a fixed `vw`. The
  licensed Figma face is unavailable, and the substitute renders ~13% wider at
  the same point size — enough to push the final letter off the page. Measuring
  makes the lock-up survive any font swap.

---

## Backend

Ported from a Supabase/Postgres backend. Same tables, same access rules.

| Supabase | Here | Why |
| --- | --- | --- |
| `auth.users` + `public.profiles` | one `users` table | Laravel owns authentication, so there is no reason to split a person across two tables |
| Supabase Auth | Laravel session auth | native to the framework, nothing extra to run |
| Row Level Security | Policies + query scopes | MySQL has no RLS |
| Storage bucket + object policies | `storage/app/private` + `/portal/files/…` | outside the document root, served only after an authorization check |
| Edge Functions | Controllers | `send-enquiry` → `EnquiryController`, `create-client` / `update-client` → `Admin\ClientController` |
| `denomailer` | Laravel Mail | same SMTP mailbox |

**The authorization rules did not change** — only where they are enforced. MySQL
cannot express them, so they live in two complementary places:

- **`ProjectPolicy`** guards a record you have already loaded (`show`,
  downloads).
- **`Project::visibleTo()`** scopes the query, because a policy cannot filter an
  index listing.

Both are needed. A policy alone would let a list leak other clients' rows; a
scope alone would leave a route-model-bound page unguarded.

### Routes

| Method | Route | Access |
| --- | --- | --- |
| `GET` | `/` | public |
| `POST` | `/enquiries` | public — throttled 5/10min, honeypot |
| `GET` `POST` | `/login` | guests — throttled 10/15min |
| `POST` | `/logout` | authenticated |
| `GET` | `/portal` | admin: all projects · client: own, live only |
| `GET` | `/portal/projects/{project}` | scoped by `ProjectPolicy` |
| `PUT` | `/portal/password` | own password; re-authenticates first |
| `GET` | `/portal/files/{path}` | scoped; `?download=1` honours `can_download` |
| `GET` `POST` `PUT` `DELETE` | `/admin/clients` | admin |

---

## Security notes

- Eloquent parameterises every query; no raw SQL is built from input.
- Blade escapes by default, including in the mail templates.
- CSRF protection on every form.
- Passwords hashed with bcrypt via the `hashed` cast.
- Login uses one error message for both unknown accounts and wrong passwords,
  so responses cannot be used to enumerate users.
- Uploads are allow-listed by extension and stored with UUID-prefixed names;
  the original filename is slugged, never used as a path.
- Private files are served with `Cache-Control: private, no-store`.
- An admin cannot delete their own account (`UserPolicy::delete`), which would
  otherwise lock out the last administrator.

`php artisan test` covers the enquiry flow and the whole authorization matrix —
guest, owning client, non-owning client, and admin — because those are the rules
the database is no longer enforcing.

---

## Content still to confirm

Marked `PLACEHOLDER` in `config/site.php`:

- **Service headlines 03–06** — Figma names the six tabs but only writes
  headlines for Fit-Out Contracting and Design & Build.
- **Process steps 02–04** — only "Tender And Cost Estimation" exists.
- **Social URLs** — the footer links to network home pages.
- **Service and process photography** — `public/images/services/*` and
  `public/images/process/*` reuse project photography. The originals are
  flattened into the Figma frames with captions baked in, so they could not be
  extracted cleanly. Drop replacements in at the same paths.

Every other image is the original Figma asset, re-encoded to WebP
(≈32 MB of PNG → 2.2 MB).

---

## History

The `nextjs` branch holds a complete Next.js 16 implementation of the same site
and backend, kept for reference.
