# Deploying to Hostinger

Target: **Hostinger Node.js Web App → Hostinger MySQL**. The whole site — pages,
API, and static assets — runs as one Node process, which is what Hostinger's
Web Apps runtime supervises.

## 0. Check the plan first

**Node.js Web Apps are not available on Premium / Unlimited shared plans.** They
require one of:

| Plan | Node.js Web Apps |
| --- | --- |
| Premium / **Unlimited** (legacy shared) | ❌ not supported |
| **Business** | ✅ 5 web apps |
| **Cloud Startup** and above | ✅ 10 web apps |
| VPS | ✅ but you configure and supervise Node yourself |

Managed MySQL is included on Business and Cloud, so one plan covers both halves
of this app.

To confirm your own plan: hPanel → **Websites → Web Apps**. If the plan does not
include it you get an upgrade prompt instead of a "create app" flow.

### Known limitation: the filesystem is ephemeral

Hostinger's Web Apps runtime resets the filesystem on every redeploy. That is
fine for the parts of this app that matter first — pages, the enquiry form, and
everything in MySQL all survive, because the database is a separate managed
service.

It is **not** fine for `STORAGE_DIR`, which holds client project images and
reports. Uploaded files are wiped on the next deploy. Before the client portal
is used in anger, `lib/storage.ts` needs to be pointed at object storage
(any S3-compatible bucket) instead of local disk. Nothing else changes — every
call site goes through that one module, and the stored path format stays the
same.

The landing page and enquiries do not touch storage, so they can go live now.

---

## 1. Create the database

hPanel → **Databases → Management → Create new database**.

Note the four values Hostinger generates — the database and user names are
prefixed with your account id (`u123456789_…`):

```
MYSQL_DATABASE=u123456789_samaaltariq
MYSQL_USER=u123456789_sama
MYSQL_PASSWORD=<the password you set>
MYSQL_HOST=localhost
```

`localhost` is correct when the Node app runs on the same plan as the database.
Use `MYSQL_SSL=true` only for a remote or managed MySQL.

## 2. Create the mailbox

hPanel → **Emails** → create `info@samaaltariq.org` (or your address). You will
need it for `SMTP_USER` / `SMTP_PASS`.

## 3. Create the Node.js web app

hPanel → **Websites → Web Apps → Add website → Node.js**, then connect the
GitHub repository:

```
https://github.com/webdevdao-git/SamaAlTariq_Website
```

Every push to `main` redeploys automatically. (A .zip upload also works, but
does not auto-redeploy.)

Settings:

| Field | Value |
| --- | --- |
| Framework | Next.js (auto-detected) |
| Node version | 20.x or newer |
| Branch | `main` |
| Build command | `npm run build` |
| Start command | `npm start` |
| Output directory | `.next` |

`npm start` runs `.next/standalone/server.js`, and the `postbuild` step copies
`public/` and `.next/static` into that output — see the note at the end of this
file for why that matters. If the platform's Next.js preset insists on running
`next start` instead, remove `output: "standalone"` from `next.config.ts` and
set the start command to `next start`; everything else is unaffected.

## 4. Environment variables

Set these in hPanel → **Websites → Web Apps → your app → Environment variables**.
Use the dashboard rather than a `.env` file: the filesystem is reset on every
redeploy, so a committed or uploaded `.env` either disappears or has to live in
the repository. Real environment variables always win over a `.env` file.

```
NEXT_PUBLIC_SITE_URL=https://samaaltariq.org

MYSQL_HOST=localhost
MYSQL_PORT=3306
MYSQL_DATABASE=u123456789_samaaltariq
MYSQL_USER=u123456789_sama
MYSQL_PASSWORD=…

AUTH_SECRET=…                       # openssl rand -base64 48
STORAGE_DIR=/home/u123456789/sama-storage

SMTP_HOST=smtp.hostinger.com
SMTP_PORT=465
SMTP_SECURE=true
SMTP_USER=info@samaaltariq.org
SMTP_PASS=…
SMTP_FROM=info@samaaltariq.org
SMTP_FROM_NAME=Sama Al Tariq
ENQUIRY_TO=info@samaaltariq.org
```

Two that matter:

- **`AUTH_SECRET`** must be ≥32 characters. Changing it signs everyone out.
- **`STORAGE_DIR`** is only meaningful once storage moves off local disk — see
  the ephemeral-filesystem note in section 0. Leave it at the default until then.

## 5. Migrate the database and seed the admin

The build itself runs on Hostinger. These two are one-off commands you run
against the same database — from your own machine with the Hostinger MySQL
credentials in `.env`, or over SSH if the plan includes it:

```bash
npm ci
npm run db:init                                        # creates the tables — safe to re-run
npm run admin:create -- admin@samaaltariq.org "Site Admin"
```

If you run them locally, the database must accept a remote connection: hPanel →
**Databases → Remote MySQL**, and whitelist your IP. Remove the whitelist entry
afterwards.

`admin:create` prints a temporary password and flags the account to require a
change at first sign-in. Run it once.

## 6. Start

The app starts automatically after the first successful deploy; use **Restart**
in the Web Apps dashboard after changing environment variables. Hostinger
injects `PORT` and the app binds to it.

Verify:

```bash
curl -I https://samaaltariq.org
curl -X POST https://samaaltariq.org/api/enquiries \
  -H 'Content-Type: application/json' \
  -d '{"name":"Test","email":"you@example.com","phone":"+971500000000","projectType":"Villa"}'
```

A `200` and a row in `enquiries` means the database, the API, and SMTP are wired
up.

---

## Redeploying

Push to `main`. The GitHub integration rebuilds and restarts the app.

`npm run db:init` only needs re-running when `db/schema.sql` changes; every
statement is `CREATE TABLE IF NOT EXISTS`, so it is safe either way.

---

## Why the startup file is `.next/standalone/server.js`

`next start` does not work with `output: "standalone"`, and standalone is what
keeps the deployed bundle small enough for a shared plan. One catch: Next does
**not** copy `public/` or `.next/static` into the standalone output — on Vercel
a CDN serves those. Here the Node process serves everything, so
`scripts/prepare-standalone.mjs` copies them in as a `postbuild` step. Skip the
build step and the site loads with no CSS, no JavaScript, and no images.

---

## Troubleshooting

**App will not start.** Check the deploy log in hPanel → Websites → Web Apps. `EADDRINUSE` means a
previous process is still bound — use Restart, not Start.

**Site renders unstyled.** `npm run build` was skipped, or the build ran before
the last `git pull`. Rebuild and restart.

**`AUTH_SECRET is missing or shorter than 32 characters`.** Set it in hPanel and
restart; it is read at request time, not build time.

**Enquiries return 500.** MySQL is unreachable. Confirm `MYSQL_HOST=localhost`
and that the user is attached to the database in hPanel.

**Enquiries save but no email arrives.** Look for `[enquiries] SMTP send failed`
in the log. Port 465 with `SMTP_SECURE=true` is the reliable combination on
Hostinger; 587 needs `SMTP_SECURE=false`.

**Uploads vanish after a deploy.** Expected — the Web Apps filesystem is
ephemeral. Move `lib/storage.ts` to object storage (see section 0).

**Too many connections.** Lower `MYSQL_POOL_SIZE` (default 5); shared plans
commonly cap at 25.
