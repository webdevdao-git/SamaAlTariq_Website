# Deploying to Hostinger

Target: **Hostinger Node.js Web App → Hostinger MySQL**. The whole site — pages,
API, and static assets — runs as one Node process, which is what Hostinger's
Node.js app manager supervises.

Requires a plan with Node.js support (Business shared, Cloud, or a VPS). The
"Website" / static-only plans cannot run this.

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

## 3. Create the Node.js application

hPanel → **Advanced → Node.js** → *Create application*:

| Field | Value |
| --- | --- |
| Node version | 20.x or newer |
| Application root | e.g. `domains/samaaltariq.org/app` |
| Application URL | your domain |
| Application startup file | `.next/standalone/server.js` |

## 4. Upload the code

**Option A — Git (recommended).** hPanel → **Advanced → Git**, point it at
`https://github.com/webdevdao-git/SamaAlTariq_Website`, deploy into the
application root, then redeploy from that panel for each release.

**Option B — SSH.**

```bash
ssh -p 65002 u123456789@your-server-ip
cd ~/domains/samaaltariq.org/app
git clone https://github.com/webdevdao-git/SamaAlTariq_Website.git .
```

**Option C — File manager.** Upload everything *except* `node_modules`,
`.next`, `.env`, `storage`, and `_figma_assets`.

## 5. Environment variables

Set these in hPanel → Node.js → your app → **Environment variables** (preferred:
they survive redeploys and stay out of the filesystem), or in a `.env` file in
the application root. Real environment variables always win.

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
- **`STORAGE_DIR`** must be **outside the application root**. Uploaded client
  files live there; a directory inside the app is wiped by the next Git deploy.

## 6. Install, migrate, build

Over SSH from the application root:

```bash
npm ci --omit=dev=false     # devDependencies are needed to build
npm run db:init             # creates the tables — safe to re-run
npm run build               # postbuild packages the standalone output
npm run admin:create -- admin@samaaltariq.org "Site Admin"
```

`admin:create` prints a temporary password and flags the account to require a
change at first sign-in. Run it once.

## 7. Start

hPanel → Node.js → **Restart**. Hostinger runs the startup file and injects
`PORT`; the app binds to it automatically.

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

```bash
git pull
npm ci --omit=dev=false
npm run build
# hPanel → Node.js → Restart
```

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

**App will not start.** Check the log in hPanel → Node.js. `EADDRINUSE` means a
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

**Uploads vanish after a deploy.** `STORAGE_DIR` is inside the application root.
Move it out and re-upload.

**Too many connections.** Lower `MYSQL_POOL_SIZE` (default 5); shared plans
commonly cap at 25.
