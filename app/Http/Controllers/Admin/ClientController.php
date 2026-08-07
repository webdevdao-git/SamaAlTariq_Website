<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ClientCredentials;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Client accounts — the `create-client` / `update-client` Edge Functions.
 *
 * Admin-only access is applied once by `can:viewAny,App\Models\User` on the
 * route group. The per-record checks below are the ones that depend on *which*
 * account is being touched, which a group middleware cannot express — notably
 * UserPolicy::delete refusing to let an admin delete themselves.
 *
 * (authorizeResource() is not used here: it registers controller middleware via
 * $this->middleware(), which Laravel 11+ removed from controllers.)
 */
class ClientController extends Controller
{
    public function index(): View
    {
        $clients = User::query()->latest()->paginate(30);

        return view('admin.clients.index', compact('clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email:rfc', 'max:254', Rule::unique('users', 'email')],
            'username' => ['nullable', 'string', 'max:60', Rule::unique('users', 'username')],
            'phone' => ['nullable', 'string', 'max:32'],
            'job_title' => ['nullable', 'string', 'max:120'],
            'can_download' => ['boolean'],
            'role' => ['required', Rule::in(['admin', 'client'])],
        ]);

        // Generated rather than admin-chosen, and the account is flagged so the
        // client must replace it at first sign-in.
        $password = Str::password(14, symbols: false);

        $client = User::create([
            ...$validated,
            'can_download' => $request->boolean('can_download'),
            'password' => $password,
            'must_change_password' => true,
        ]);

        $emailed = $this->sendCredentials($client, $password);

        return back()->with('status', $emailed
            ? "Client created and credentials emailed to {$client->email}."
            : "Client created. Email delivery failed — temporary password: {$password}");
    }

    public function update(Request $request, User $client): RedirectResponse
    {
        $this->authorize('update', $client);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:160'],
            'email' => ['sometimes', 'email:rfc', 'max:254', Rule::unique('users', 'email')->ignore($client)],
            'username' => ['nullable', 'string', 'max:60', Rule::unique('users', 'username')->ignore($client)],
            'phone' => ['nullable', 'string', 'max:32'],
            'job_title' => ['nullable', 'string', 'max:120'],
            'can_download' => ['boolean'],
            'role' => ['sometimes', Rule::in(['admin', 'client'])],
        ]);

        $client->update([...$validated, 'can_download' => $request->boolean('can_download')]);

        if ($request->boolean('reset_password')) {
            $password = Str::password(14, symbols: false);
            $client->forceFill(['password' => $password, 'must_change_password' => true])->save();

            $emailed = $this->sendCredentials($client, $password);

            return back()->with('status', $emailed
                ? 'Client updated and a new password emailed.'
                : "Client updated. Email delivery failed — temporary password: {$password}");
        }

        return back()->with('status', 'Client updated.');
    }

    public function destroy(User $client): RedirectResponse
    {
        $this->authorize('delete', $client);

        $client->delete();

        return back()->with('status', 'Client removed.');
    }

    /**
     * A failed credential email must not roll back the account — the admin can
     * still pass the password on by hand, and that is reported back to them.
     */
    private function sendCredentials(User $client, string $password): bool
    {
        try {
            Mail::to($client->email)->send(new ClientCredentials($client, $password));

            return true;
        } catch (\Throwable $e) {
            Log::error('Client credential email failed.', [
                'user_id' => $client->id,
                'exception' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
