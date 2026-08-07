# Deploying to Hostinger

Target: **Hostinger Node.js Web App → Hostinger MySQL**.

This is a stock Next.js app — one process, no second server, no shell steps.
Deployment is: connect the repository, paste environment variables, done. The
app builds with `next build`, runs with `next start`, and creates its own
database tables on first use.

## 0. Check the plan first

**Node.js Web Apps are not available on Premium / Unlimited shared plans.**

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

The Web Apps runtime resets the filesystem on every redeploy. Pages, the enquiry
form, and everything in MySQL are unaffected — the database is a separate
managed service.

It does affect `STORAGE_DIR`, which holds client project images and reports:
uploads are wiped on the next deploy. Before the client portal is used,
`lib/storage.ts` needs to point at object storage (any S3-compatible bucket)
instead of local disk. Every call site goes through that one module and the
stored path format is unchanged, so it is a contained edit.

The landing page and enquiries never touch storage, so they can go live now.

---

## 1. Create the database

hPanel → **Databases → Management → Create new database**.

Note the values Hostinger generates — database and user names are prefixed with
your account id (`u123456789_…`). You do **not** need to import a schema; the
app creates its own tables on first request.

## 2. Create the mailbox

hPanel → **Emails** → create `info@samaaltariq.org` (or your address). Needed for
`SMTP_USER` / `SMTP_PASS`.

## 3. Create the Node.js web app

hPanel → **Websites → Web Apps → Add website → Node.js**, then connect:

```
https://github.com/webdevdao-git/SamaAlTariq_Website
```

Every push to `main` redeploys automatically.

| Field | Value |
| --- | --- |
| Framework | Next.js (auto-detected) |
| Node version | 20.x or newer |
| Branch | `main` |
| Build command | `npm run build` |
| Start command | `npm start` |
| Output directory | `.next` |

These are the stock Next.js commands, which is what the platform's auto-detection
expects — there is nothing custom to configure.

## 4. Environment variables

hPanel → **Websites → Web Apps → your app → Environment variables**. Use the
dashboard rather than a `.env` file: the filesystem resets on redeploy, so an
uploaded `.env` disappears.

```
NEXT_PUBLIC_SITE_URL=https://samaaltariq.org

MYSQL_HOST=localhost
MYSQL_PORT=3306
MYSQL_DATABASE=u123456789_samaaltariq
MYSQL_USER=u123456789_sama
MYSQL_PASSWORD=…

AUTH_SECRET=…                     # openssl rand -base64 48

ADMIN_EMAIL=admin@samaaltariq.org
ADMIN_NAME=Site Admin
ADMIN_PASSWORD=…                  # remove after first boot

SMTP_HOST=smtp.hostinger.com
SMTP_PORT=465
SMTP_SECURE=true
SMTP_USER=info@samaaltariq.org
SMTP_PASS=…
SMTP_FROM=info@samaaltariq.org
SMTP_FROM_NAME=Sama Al Tariq
ENQUIRY_TO=info@samaaltariq.org
```

- **`AUTH_SECRET`** must be ≥32 characters. Changing it signs everyone out.
- **`ADMIN_EMAIL` / `ADMIN_PASSWORD`** create the first administrator on first
  boot, and only when no admin exists — so this can never overwrite a real
  account. Delete `ADMIN_PASSWORD` once the account is created.
- **`STORAGE_DIR`** only matters once storage moves off local disk (section 0).

## 5. Deploy

The app builds and starts automatically. Use **Restart** in the Web Apps
dashboard after changing environment variables.

On the first request that touches the database, the app creates its tables and
seeds the administrator, then logs `[migrate] schema ready`. Nothing to run by
hand.

Verify:

```bash
curl -I https://samaaltariq.org
curl -X POST https://samaaltariq.org/api/enquiries \
  -H 'Content-Type: application/json' \
  -d '{"name":"Test","email":"you@example.com","phone":"+971500000000","projectType":"Villa"}'
```

A `200` and a row in `enquiries` means the database, the API, and SMTP are all
wired up.

---

## Redeploying

Push to `main`. The GitHub integration rebuilds and restarts.

---

## Troubleshooting

**App will not start.** Check the deploy log in hPanel → Websites → Web Apps.

**Site renders unstyled.** The build did not complete — check the deploy log for
a failed `npm run build`.

**`AUTH_SECRET is missing or shorter than 32 characters`.** Set it and restart;
it is read per request, not at build time.

**Enquiries return 500.** MySQL is unreachable, or the credentials are wrong.
Confirm `MYSQL_HOST=localhost` and that the user is attached to the database in
hPanel. The log shows the underlying driver error; the response never does.

**Tables are not being created.** Look for `[migrate]` lines in the log. The
schema runs on the first database request, not at boot, so hit
`/api/enquiries` once. If `AUTO_MIGRATE=false` is set, it is disabled on purpose.

**Enquiries save but no email arrives.** Look for `[enquiries] SMTP send failed`.
Port 465 with `SMTP_SECURE=true` is the reliable combination on Hostinger; 587
needs `SMTP_SECURE=false`.

**Uploads vanish after a deploy.** Expected — the filesystem is ephemeral. Move
`lib/storage.ts` to object storage (section 0).

**Too many connections.** Lower `MYSQL_POOL_SIZE` (default 5); shared plans
commonly cap at 25.
