<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Emails a reset link.
     *
     * The response is identical whether or not the address exists. Reporting
     * "we don't know that email" would turn this form into a way to discover
     * which of your clients have accounts, and the form is public.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email:rfc', 'max:254']]);

        Password::sendResetLink($request->only('email'));

        return back()->with('status', 'If that address has an account, a reset link is on its way.');
    }
}
