<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * The landing page. Entirely driven by config/site.php, so it renders
     * without touching the database — the enquiry form is the only part of the
     * page that needs MySQL, and only on submit.
     */
    public function home(): View
    {
        return view('home');
    }

    /**
     * The About page. Config-driven in the same way, and it reuses the landing
     * page's enquiry section, so it too only reaches MySQL on submit.
     */
    public function about(): View
    {
        return view('about');
    }

    /**
     * The projects page. Config-driven like the other two, and it reuses the
     * landing page's enquiry section, so it too only reaches MySQL on submit.
     */
    public function projects(): View
    {
        return view('projects');
    }

    /**
     * The Contact page. The enquiry form posts to EnquiryController like the
     * card's does — this page renders the same component.
     */
    public function contact(): View
    {
        return view('contact');
    }

    /**
     * The Services page. Config-driven like the other marketing pages.
     */
    public function services(): View
    {
        return view('services');
    }

    /**
     * The Our Process page. Config-driven like the other marketing pages, so
     * it too only reaches MySQL when the enquiry form is submitted.
     */
    public function process(): View
    {
        return view('process');
    }

    /**
     * One project, from Figma frame 1472:1339.
     *
     * The facts a project page quotes — title, category, location, area,
     * duration — are the projects grid's own, looked up by slug rather than
     * restated, so the two pages cannot drift apart. A project the grid does
     * not carry may supply its own `facts` instead; Villa B200 does, having
     * photographs but no tile.
     *
     * Related projects resolve to the same grid, so a related tile shows the
     * cover the gallery shows and nothing has to be exported twice.
     */
    public function project(string $slug): View
    {
        $page = config("site.project_pages.$slug") ?? abort(404);
        $facts = $this->projectFacts();

        return view('project', [
            'slug' => $slug,
            'page' => $page,
            // The hero cycles: the projects page's own picture first, then the
            // extra frames cut from that project's shoot. Read off disk rather
            // than counted in config, so a project that gains or loses a
            // photograph needs no second edit.
            'slides' => collect(['hero.webp'])
                ->concat(collect(glob(public_path("images/projects/$slug/hero-*.webp")))
                    ->map(fn (string $f) => basename($f))
                    ->sort()
                    ->values())
                ->all(),
            // Every photograph the project has, counted off disk. The grid
            // draws the frame's nine; the rest are reachable once one is open.
            'photographs' => count(glob(public_path("images/projects/$slug/l*.webp"))),
            'project' => $page['facts'] ?? $facts[$slug] ?? abort(404),
            'related' => collect($page['related'])
                ->map(fn (string $s) => isset($facts[$s]) ? $facts[$s] + ['slug' => $s] : null)
                ->filter()
                ->values()
                ->all(),
        ]);
    }

    /**
     * Every tile on the projects grid, indexed by the slug it draws — which is
     * its `image`, because Hospitality and Fitness each show one project twice
     * and the two tiles differ only in photograph. The first wins, so a project
     * keeps one set of figures.
     *
     * @return array<string, array<string, string>>
     */
    private function projectFacts(): array
    {
        $facts = [];

        foreach (config('site.projects_page.groups') as $group) {
            foreach ($group['rows'] as $row) {
                foreach ($row['columns'] as $column) {
                    foreach ($column['tiles'] as $tile) {
                        $facts[$tile['image']] ??= $tile;
                    }
                }
            }
        }

        return $facts;
    }
}
