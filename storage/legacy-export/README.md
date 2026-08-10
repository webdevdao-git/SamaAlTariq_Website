# Legacy export drop-point

`php artisan import:legacy` reads from here by default. Place the Supabase
export in this directory:

    profiles.json  projects.json  project_images.json  project_documents.json
    project_stages.json  project_updates.json  enquiries.json
    files/<uuid>_<filename>          (storage objects, separator-flattened)

The JSON and the files are **not committed** — they contain client data and
53 MB of project photography. Export them with an admin token:

    curl "$SUPABASE_URL/auth/v1/token?grant_type=password" \
      -H "apikey: $ANON_KEY" -H 'Content-Type: application/json' \
      -d '{"email":"…","password":"…"}'

then `GET /rest/v1/<table>?select=*` and
`GET /storage/v1/object/project-images/<path>` with that token as Bearer.
RLS grants an admin full read; the anon key alone returns `[]`.
