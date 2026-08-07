# Deploying to Hostinger

Stack: **Laravel + MySQL on Hostinger Web Hosting**.

This runs on ordinary PHP shared hosting — the Unlimited/Premium plans included.
It does **not** need a Node.js Web App, and it does not need the Business plan.

---

## What makes this different from a normal Laravel deploy

Two things, and both will silently break the site if missed:

1. **The document root must point at `public/`.** Laravel's front controller is
   `public/index.php`; everything above it — `.env`, `storage/`, `vendor/` — must
   never be web-reachable. Uploading the project straight into `public_html`
   exposes your database password to anyone who guesses the URL.

2. **Built assets are committed to the repository.** Shared hosting has no Node
   runtime, so `npm run build` cannot run on the server. `public/build/` is
   therefore tracked in git (see `.gitignore`). Build locally and commit before
   deploying, or the site loads with no CSS and no JavaScript.

---

## 1. Create the database

hPanel → **Databases → Management → Create new database**. Note the four values;
the database and user names are prefixed with your account id.

## 2. Create the mailbox

hPanel → **Emails** → create `info@samaaltariq.org` (or your address).

## 3. Upload the code

Over SSH (hPanel → **Advanced → SSH Access** for the credentials):

```bash
ssh -p 65002 uXXXXXXXX@your-server-ip
cd ~/domains/samaaltariq.org
git clone https://github.com/webdevdao-git/SamaAlTariq_Website.git app
cd app
composer install --no-dev --optimize-autoloader
```

`--no-dev` matters: it skips the test and debug packages, which have no business
on a production host.

## 4. Point the domain at `public/`

hPanel → **Websites → your domain → Advanced → Document root**, set it to:

```
domains/samaaltariq.org/app/public
```

If your plan does not expose that setting, the fallback is to put the contents
of `app/public/` into `public_html/` and edit the two paths near the bottom of
`public_html/index.php` to point up at `../app`. Changing the document root is
cleaner — do that if you can.

## 5. Configure

```bash
cp .env.example .env
php artisan key:generate
nano .env          # fill in DB_*, MAIL_*, ADMIN_*, APP_URL
```

`APP_DEBUG=false` in production, always. With it on, a stack trace on any error
page will display your environment variables.

## 6. Migrate and seed

```bash
php artisan migrate --force
php artisan db:seed --force        # creates the first administrator
```

`db:seed` prints a temporary password and flags the account so it must be
changed at first sign-in. It refuses to touch an account that already exists, so
re-running it is safe.

## 7. Cache for production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

Re-run `config:cache` after any `.env` change — once the config is cached,
`.env` is no longer read at runtime and edits appear to do nothing.

## 8. Check permissions

```bash
chmod -R 775 storage bootstrap/cache
```

These are the only directories the web server writes to. Uploaded client files
live in `storage/app/private`, outside the document root, reachable only through
the authorised `/portal/files/...` route.

---

## Verify

```bash
curl -I https://samaaltariq.org
```

Then submit the enquiry form and confirm a row appears in `enquiries`
(hPanel → Databases → phpMyAdmin). That single test proves PHP, MySQL, the
routes, and SMTP are all wired.

---

## Redeploying

```bash
cd ~/domains/samaaltariq.org/app
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

If you changed anything under `resources/`, run `npm run build` **locally** and
commit `public/build/` first — the server cannot build it.

---

## Troubleshooting

**500 with a blank page.** Check `storage/logs/laravel.log`. Nine times in ten
it is permissions on `storage/` or a missing `APP_KEY`.

**Site renders unstyled.** `public/build/` was not committed, or the build is
stale. Run `npm run build` locally, commit, pull on the server.

**"No application encryption key has been specified."** Run
`php artisan key:generate`, then `php artisan config:cache`.

**Changes to `.env` do nothing.** The config is cached. Run
`php artisan config:cache` again.

**Enquiries save but no email arrives.** Look for the logged exception in
`storage/logs/laravel.log`. The enquiry is stored before the email is attempted,
so a mail failure never loses the lead. Port 465 needs `MAIL_SCHEME=smtps`;
port 587 needs `MAIL_SCHEME=smtp`.

**`.env` is downloadable in the browser.** The document root is wrong — it is
pointing at the project root instead of `public/`. Fix it immediately and rotate
the database password.

**Uploaded files 404 for a client.** Expected if the project was archived or
belongs to someone else — that is `ProjectPolicy` doing its job. Check with an
admin account.
