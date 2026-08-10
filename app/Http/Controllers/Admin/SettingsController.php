<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ClientCredentials;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Profile Settings — the admin's own profile, client account creation, and
 * per-client project access.
 */
class SettingsController extends Controller
{
    /** Profile Settings — the administrator's own account only. */
    public function index(): View
    {
        return view('admin.settings');
    }

    /** Add Clients — account creation and the access overview. */
    public function clients(): View
    {
        return view('admin.clients', [
            'projects' => Project::orderBy('title')->get(['id', 'title']),
            'clients' => User::where('role', 'client')
                ->withCount('projects')
                ->with('projects:id,client_id,title')
                ->orderBy('name')
                ->get(),
        ]);
    }

    /** The signed-in administrator's own details, and optionally their password. */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email:rfc', 'max:254', Rule::unique('users', 'email')->ignore($user)],
            'phone' => ['nullable', 'string', 'max:32'],
            'job_title' => ['nullable', 'string', 'max:120'],
            'current_password' => ['nullable', 'required_with:password', 'string'],
            'password' => ['nullable', 'confirmed', PasswordRule::min(10)],
        ]);

        if (filled($validated['password'] ?? null)) {
            // Re-checked even though the session is authenticated: without it a
            // borrowed or unattended session could lock the owner out.
            if (! Hash::check($validated['current_password'], $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => 'Your current password is incorrect.',
                ]);
            }

            $user->password = $validated['password'];
            $user->must_change_password = false;
        }

        $user->fill($request->only('name', 'email', 'phone', 'job_title'))->save();

        return back()->with('status', 'Profile saved.');
    }

    /**
     * Creates a client and, in the same step, assigns the projects they may see.
     *
     * Assignment is `projects.client_id`, so a project belongs to one client at
     * a time. Selecting a project already assigned to somebody else moves it —
     * which is why the confirmation names what changed.
     */
    public function storeClient(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email:rfc', 'max:254', Rule::unique('users', 'email')],
            'dial_code' => ['nullable', 'string', 'max:8'],
            'phone' => ['nullable', 'string', 'max:24'],
            'password' => ['required', 'confirmed', PasswordRule::min(10)],
            'can_download' => ['boolean'],
            'projects' => ['array'],
            'projects.*' => [Rule::exists('projects', 'id')],
        ]);

        $client = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => trim(($validated['dial_code'] ?? '').' '.($validated['phone'] ?? '')) ?: null,
            'password' => $validated['password'],
            'role' => 'client',
            'can_download' => $request->boolean('can_download'),
            // Admin-issued, so it must be replaced at first sign-in.
            'must_change_password' => true,
        ]);

        $assigned = $this->assign($client, $validated['projects'] ?? []);

        $emailed = $this->sendCredentials($client, $validated['password']);

        return back()->with('status', sprintf(
            '%s created%s. %s',
            $client->name,
            $assigned ? " with {$assigned} ".Str::plural('project', $assigned).' assigned' : '',
            $emailed ? "Credentials emailed to {$client->email}." : 'Email delivery failed — pass the password on yourself.'
        ));
    }

    /**
     * Edits an existing client: their details, their password, which projects
     * they can see, and their download right.
     *
     * The password is optional and blank means "leave it alone" — an admin
     * changing a phone number must not have to reissue credentials to do it.
     * When one is set it is admin-issued, so the account is flagged to replace
     * it, exactly as account creation does.
     *
     * `role` is deliberately not editable here. Everything on this screen is a
     * client by definition, and letting a row on it grant admin rights would
     * put privilege escalation behind a pencil icon.
     */
    public function updateAccess(Request $request, User $client): RedirectResponse
    {
        $this->authorize('update', $client);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email:rfc', 'max:254', Rule::unique('users', 'email')->ignore($client)],
            'phone' => ['nullable', 'string', 'max:32'],
            'password' => ['nullable', PasswordRule::min(10)],
            'can_download' => ['boolean'],
            'projects' => ['array'],
            'projects.*' => [Rule::exists('projects', 'id')],
        ]);

        $client->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            // Absent and blank both mean "no phone number" — `nullable` leaves
            // the key out entirely when the field is not submitted.
            'phone' => filled($validated['phone'] ?? null) ? $validated['phone'] : null,
            'can_download' => $request->boolean('can_download'),
        ]);

        $reissued = filled($validated['password'] ?? null);

        if ($reissued) {
            $client->password = $validated['password'];
            $client->must_change_password = true;
        }

        $client->save();

        $assigned = $this->assign($client, $validated['projects'] ?? []);

        return back()->with('status', sprintf(
            '%s updated — %d %s%s.',
            $client->name,
            $assigned,
            Str::plural('project', $assigned),
            $reissued ? ', password changed' : ''
        ));
    }

    /**
     * Points the given projects at this client and releases any others.
     *
     * The release step matters: without it, unticking a project would leave it
     * still assigned, and the client would keep seeing something the admin
     * believes they revoked.
     */
    private function assign(User $client, array $projectIds): int
    {
        Project::where('client_id', $client->id)
            ->whereNotIn('id', $projectIds)
            ->update(['client_id' => null]);

        if ($projectIds) {
            Project::whereIn('id', $projectIds)->update(['client_id' => $client->id]);
        }

        return count($projectIds);
    }

    private function sendCredentials(User $client, string $password): bool
    {
        try {
            Mail::to($client->email)->send(new ClientCredentials($client, $password));

            return true;
        } catch (\Throwable $e) {
            Log::error('Client credential email failed.', ['user_id' => $client->id, 'exception' => $e->getMessage()]);

            return false;
        }
    }
}
