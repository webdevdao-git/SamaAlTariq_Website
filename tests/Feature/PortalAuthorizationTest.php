<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The rules that used to be Postgres Row Level Security.
 *
 * These are the tests that matter most: MySQL cannot enforce any of this, so a
 * regression here is a data leak rather than a broken page.
 */
class PortalAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $owner;
    private User $stranger;
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->owner = User::factory()->create(['role' => 'client', 'can_download' => false]);
        $this->stranger = User::factory()->create(['role' => 'client', 'can_download' => true]);

        $this->project = Project::create([
            'client_id' => $this->owner->id,
            'title' => 'Emirates Hills Villa',
            'status' => 'In Progress',
            'progress' => 45,
        ]);
    }

    public function test_guests_are_sent_to_login(): void
    {
        $this->get(route('portal.dashboard'))->assertRedirect(route('login'));
        $this->get(route('portal.images'))->assertRedirect(route('login'));
        $this->get(route('portal.documents'))->assertRedirect(route('login'));
        // Admin URLs now bounce to the staff door — see the test below.
        $this->get(route('admin.settings'))->assertRedirect(route('admin.login'));
    }

    public function test_a_client_sees_only_their_own_projects(): void
    {
        $other = Project::create([
            'client_id' => $this->stranger->id,
            'title' => 'Someone Else Tower',
            'status' => 'Planning',
        ]);

        $this->actingAs($this->owner)
            ->get(route('portal.dashboard'))
            ->assertOk()
            ->assertSee('Emirates Hills Villa')
            ->assertDontSee('Someone Else Tower');
    }

    /**
     * Asking for someone else's project falls back to your own rather than
     * 403-ing. A refusal would confirm the id exists; a silent fallback tells
     * an attacker nothing, and the content proves the scope held.
     */
    public function test_requesting_another_clients_project_falls_back_to_your_own(): void
    {
        $other = Project::create([
            'client_id' => $this->stranger->id,
            'title' => 'Someone Else Tower',
            'status' => 'Planning',
        ]);

        $this->actingAs($this->owner)
            ->get(route('portal.dashboard', ['project' => $other->id]))
            ->assertOk()
            ->assertSee('Emirates Hills Villa')
            ->assertDontSee('Someone Else Tower');
    }

    public function test_a_soft_deleted_project_disappears_for_its_client(): void
    {
        $this->project->delete();

        $this->actingAs($this->owner)
            ->get(route('portal.dashboard', ['project' => $this->project->id]))
            ->assertOk()
            ->assertDontSee('Emirates Hills Villa')
            ->assertSee('No projects have been assigned');
    }

    public function test_clients_cannot_reach_the_admin_area(): void
    {
        foreach (['admin.dashboard', 'admin.settings', 'admin.projects'] as $route) {
            $this->actingAs($this->owner)->get(route($route))->assertForbidden();
            $this->actingAs($this->admin)->get(route($route))->assertOk();
        }
    }

    /** A guest deep-linking an admin URL lands on the staff door, not the client one. */
    public function test_guests_are_bounced_to_the_matching_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.settings'))->assertRedirect(route('admin.login'));
        $this->get(route('portal.dashboard'))->assertRedirect(route('login'));
    }

    /** Signing in lands each role where it belongs, from either door. */
    public function test_sign_in_lands_on_the_right_home(): void
    {
        $this->post(route('admin.login'), ['identifier' => $this->admin->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
        $this->post(route('logout'));

        // A client reaching the staff door is sent to their portal rather than
        // refused, so the response cannot be used to discover who is an admin.
        $this->post(route('admin.login'), ['identifier' => $this->owner->email, 'password' => 'password'])
            ->assertRedirect(route('portal.dashboard'));
        $this->post(route('logout'));

        $this->post(route('login'), ['identifier' => $this->admin->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
    }

    /** The edit screen is reachable by an admin and refused to a client. */
    public function test_only_an_admin_can_edit_a_project(): void
    {
        $this->actingAs($this->owner)
            ->get(route('admin.projects.edit', $this->project))
            ->assertForbidden();

        $this->actingAs($this->admin)
            ->get(route('admin.projects.edit', $this->project))
            ->assertOk();
    }

    public function test_an_admin_cannot_delete_their_own_account(): void
    {
        $this->actingAs($this->admin)
            ->delete(route('admin.clients.destroy', $this->admin))
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    /**
     * Revoking access has to actually revoke it. Unticking a project sets
     * client_id back to null, and a null owner must match nobody — if that ever
     * degrades to "visible to everyone", every client sees every unassigned
     * project and the access overview becomes a lie.
     */
    public function test_a_revoked_project_is_visible_to_nobody(): void
    {
        // Name and email ride along because the edit form posts the whole
        // client, not just the tickboxes — omitting them fails validation and
        // the revoke would silently never run.
        $this->actingAs($this->admin)
            ->put(route('admin.clients.access', $this->owner), [
                'name' => $this->owner->name,
                'email' => $this->owner->email,
                'projects' => [],
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('projects', ['id' => $this->project->id, 'client_id' => null]);

        foreach ([$this->owner, $this->stranger] as $client) {
            $this->actingAs($client)
                ->get(route('portal.dashboard', ['project' => $this->project->id]))
                ->assertOk()
                ->assertDontSee('Emirates Hills Villa');
        }
    }

    /** A client removed from the access overview loses the ability to sign in. */
    public function test_a_removed_client_can_no_longer_sign_in(): void
    {
        $this->actingAs($this->admin)
            ->delete(route('admin.clients.destroy', $this->owner))
            ->assertRedirect();

        $this->assertDatabaseMissing('users', ['id' => $this->owner->id]);

        // The login form is behind `guest`, so drop the admin session first —
        // otherwise the POST is bounced before the credentials are ever checked.
        $this->post(route('logout'));

        $this->post(route('login'), ['identifier' => $this->owner->email, 'password' => 'password'])
            ->assertSessionHasErrors('identifier');

        $this->assertGuest();
    }

    /**
     * Accounts exist only because an admin created one. If a self-service
     * registration route is ever added, the access overview stops being the
     * complete list of who can reach the portal.
     */
    public function test_there_is_no_self_service_registration(): void
    {
        foreach (['register', 'signup', 'sign-up'] as $path) {
            $this->get("/{$path}")->assertNotFound();
        }
    }
}
