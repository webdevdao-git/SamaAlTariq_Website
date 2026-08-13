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
}
