# Deploying to Hostinger

Stack: **Laravel + MySQL on Hostinger Web Hosting**.

This runs on ordinary PHP shared hosting — the Unlimited/Premium plans included.
It does **not** need a Node.js Web App, and it does not need the Business plan.

---

## What makes this different from a normal Laravel deploy

Two things, and both will silently break the site if missed:

1. **The web root has to reach Laravel's `public/`, and Hostinger shared plans
   will not let you move the document root.** Every site is served from a fixed
   `public_html`. Step 4 handles this with a purpose-built front controller.
   What must never happen is uploading the project itself into `public_html` —
   that exposes `.env`, and with it your database password, to anyone who
   guesses the URL.

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

## 4. Wire `public_html` to the app

Hostinger's shared plans serve every site from a fixed `public_html` and give
you no way to move the document root, so Laravel's `public/` cannot become the
web root directly. `deploy/hostinger/` in this repo contains a front controller
built for exactly that layout.

Target structure:

```
~/domains/samaaltariq.org/
    app/            ← this repository
    public_html/    ← index.php, .htaccess, and everything from app/public/
```

```bash
cd ~/domains/samaaltariq.org
cp app/deploy/hostinger/index.php   public_html/
cp app/deploy/hostinger/.htaccess   public_html/
cp -R app/public/build              public_html/
cp -R app/public/images             public_html/

# every other root-level public file — favicons, robots.txt, the manifest.
# `! -name index.php` is load-bearing: app/public/index.php is Laravel's stock
# front controller, and copying it here would overwrite the one from
# deploy/hostinger and take the site down.
find app/public -maxdepth 1 -type f ! -name index.php -exec cp {} public_html/ \;
```

That front controller calls `usePublicPath(__DIR__)`, which is the part people
usually miss: without it Laravel keeps generating asset URLs against
`app/public`, and every stylesheet and script 404s even though the page renders.

Nothing inside the application is modified, so `git pull` never conflicts.

**Do not** upload the project itself into `public_html`. Everything above
`public/` — `.env`, `storage/`, `vendor/` — must stay out of the web root. This
layout was tested by serving `public_html` alone: the site renders, assets load,
the enquiry form submits, and `/.env` returns 404.

*If your plan does allow a custom document root* (Business and Cloud do), point
it at `app/public` instead and skip this step entirely — it is cleaner.

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
```

There is no `storage:link` step. Client files are served through an authorised
controller rather than a public symlink, which sidesteps the symlink
restrictions common on shared hosting — and keeps the files private, which was
the point.

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

# assets changed? replace the copy in the web root
# rm first: Vite hashes filenames, so copying over the top leaves every old
# hash behind and public_html/build grows with each deploy
rm -rf ../public_html/build && cp -R public/build ../public_html/
find public -maxdepth 1 -type f ! -name index.php -exec cp {} ../public_html/ \;
```

If you changed anything under `resources/`, run `npm run build` **locally** and
commit `public/build/` first — the server cannot build it.

---

## Notes from the first deploy

Three things on this host that a generic Laravel guide will not warn you about:

- **`proc_open` is disabled** in php.ini, so bare `php artisan` and
  `composer install` both fail. Prefix artisan with
  `php -d disable_functions= artisan …`, and pass `--no-scripts` to Composer
  (then run `php -d disable_functions= artisan package:discover` yourself).
- **CLI is PHP 8.4 but the web SAPI defaults to 8.3.** `composer.lock` resolved
  against 8.4 requires ≥ 8.4.1, so the site 500s in a browser while rendering
  perfectly from the shell. `public_html/.htaccess` selects 8.4 with
  `SetHandler application/x-lsphp84`; hPanel → PHP Configuration does the same.
- **`SESSION_DRIVER=database` means every request hits MySQL**, so the static
  landing page 500s before a database exists. This deploy uses `file` for both
  sessions and cache, which suits a single shared host and removes the
  dependency.

## Troubleshooting

**500 with a blank page.** Check `storage/logs/laravel.log`. Nine times in ten
it is permissions on `storage/` or a missing `APP_KEY`.

**Site renders unstyled.** Either `public/build/` was not committed, or it was
not copied into `public_html/` after the last pull. Check that
`public_html/build/` exists and matches `app/public/build/`.

**Page loads but every asset 404s.** `usePublicPath(__DIR__)` is missing from
`public_html/index.php` — you are probably using a stock copy of Laravel's
front controller instead of the one in `deploy/hostinger/`.

**"No application encryption key has been specified."** Run
`php artisan key:generate`, then `php artisan config:cache`.

**Changes to `.env` do nothing.** The config is cached. Run
`php artisan config:cache` again.

**Enquiries save but no email arrives.** Look for the logged exception in
`storage/logs/laravel.log`. The enquiry is stored before the email is attempted,
so a mail failure never loses the lead. Port 465 needs `MAIL_SCHEME=smtps`;
port 587 needs `MAIL_SCHEME=smtp`.

**`.env` is downloadable in the browser.** The project was uploaded into
`public_html` instead of beside it. Move it out immediately and rotate the
database password — assume it was read.

**Uploaded files 404 for a client.** Expected if the project was archived or
belongs to someone else — that is `ProjectPolicy` doing its job. Check with an
admin account.
