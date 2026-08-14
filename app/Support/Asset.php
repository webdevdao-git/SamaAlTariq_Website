<?php

namespace App\Support;

/**
 * A URL for a picture that changes when the picture does.
 *
 * Hostinger's CDN caches static files for seven days by filename, so replacing
 * a photograph at a path that already exists leaves the old one being served
 * from the edge — for a week, and only to some visitors, since one edge can
 * hold it while another fetches fresh. That is exactly what happened to Villa
 * PV39: the file on the server was the new photograph and the browser was
 * shown the old one.
 *
 * The favicons already carry an mtime for this reason. This is the same trick
 * for the project pictures, which are the other files on this site that get
 * replaced in place: the query string changes with the file, so the edge treats
 * it as something it has never seen.
 *
 * Vite's own output does not need this — it hashes filenames already.
 */
class Asset
{
    /** The asset URL with the file's modification time on it. */
    public static function versioned(string $path): string
    {
        $file = public_path($path);

        return asset($path).'?v='.(@filemtime($file) ?: 1);
    }
}
