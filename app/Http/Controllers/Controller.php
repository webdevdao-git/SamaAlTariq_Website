<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    /**
     * Laravel 11+ leaves the base controller empty, so `$this->authorize()` and
     * `authorizeResource()` are unavailable until this trait is added. Without
     * it those calls fail as undefined methods — which would surface as a 500,
     * not as a denied request, so it is worth being explicit about.
     */
    use AuthorizesRequests;
}
