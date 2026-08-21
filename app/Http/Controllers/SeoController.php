<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

/**
 * robots.txt and sitemap.xml, served by the application rather than dropped in
 * public/ as files.
 *
 * The reason is the host: this site answers on the hostingersite.com address
 * today and on samaaltariq.org once the domain is attached, and a file has to
 * name one of them. Rendered, both documents point at whichever host the
 * request arrived on, so neither needs editing on the day it moves.
 */
class SeoController extends Controller
{
    /** The pages a crawler should never index — everything behind the login. */
    private const PRIVATE_PATHS = [
        '/portal', '/admin', '/login', '/admin/login',
        '/forgot-password', '/reset-password',
    ];

    public function robots(): Response
    {
        $lines = ['User-agent: *'];

        foreach (self::PRIVATE_PATHS as $path) {
            $lines[] = "Disallow: {$path}";
        }

        $lines[] = '';
        $lines[] = 'Sitemap: '.url('/sitemap.xml');

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap(): Response
    {
        // Priorities say what the site thinks of itself, which is all they are
        // worth: the home page first, then the pages that sell the work, then
        // the individual projects.
        $pages = [
            ['/', '1.0', 'weekly'],
            ['/projects', '0.9', 'weekly'],
            ['/services', '0.9', 'monthly'],
            ['/about', '0.8', 'monthly'],
            ['/joinery', '0.8', 'monthly'],
            ['/process', '0.8', 'monthly'],
            ['/contact', '0.7', 'monthly'],
        ];

        foreach (array_keys(config('site.project_pages', [])) as $slug) {
            $pages[] = ["/projects/{$slug}", '0.6', 'monthly'];
        }

        // The copy is the config file, so its own mtime is the honest answer to
        // "when did this last change" for every page drawn from it.
        $lastmod = date('Y-m-d', filemtime(config_path('site.php')) ?: time());

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
            ."<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach ($pages as [$path, $priority, $frequency]) {
            $xml .= "    <url>\n"
                ."        <loc>".e(url($path))."</loc>\n"
                ."        <lastmod>{$lastmod}</lastmod>\n"
                ."        <changefreq>{$frequency}</changefreq>\n"
                ."        <priority>{$priority}</priority>\n"
                ."    </url>\n";
        }

        $xml .= "</urlset>\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
