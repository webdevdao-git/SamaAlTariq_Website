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
}
