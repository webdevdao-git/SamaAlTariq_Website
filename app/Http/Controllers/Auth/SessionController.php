<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    /** The staff entrance. Same credentials, different door and branding. */
    public function createAdmin(): View
    {
        return view('auth.admin-login');
    }

    /**
     * Sign in with either an email address or a username.
     *
     * Throttling lives on the route, not here, so a failed attempt is counted
     * even when validation short-circuits before the credential check.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'identifier' => ['required', 'string', 'max:254'],
            'password' => ['required', 'string'],
        ]);

        $field = filter_var($credentials['identifier'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (! Auth::attempt(
            [$field => $credentials['identifier'], 'password' => $credentials['password']],
            $request->boolean('remember')
        )) {
            // One message for both cases, so the response cannot be used to
            // discover which accounts exist.
            throw ValidationException::withMessages([
                'identifier' => 'Incorrect email/username or password.',
            ]);
        }

        $request->session()->regenerate();

        $user = $request->user();
        $user->forceFill(['last_login_at' => now()])->save();

        /*
         * Land where the account actually belongs. An administrator signing in
         * previously arrived in the client portal and had to type /admin by
         * hand; a client reaching the staff door is sent to their own portal
         * rather than refused, which avoids confirming who is and is not an
         * administrator.
         */
        return redirect()->intended(
            $user->isAdmin() ? route('admin.dashboard') : route('portal.dashboard')
        );
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
