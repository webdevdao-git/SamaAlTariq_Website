<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The portal's headline guarantee: only accounts an administrator created can
 * sign in, and each one sees only the projects assigned to it.
 *
 * These assertions are deliberately blunt and duplicate a little of the policy
 * tests. They exist so that if someone later adds a registration route, widens
 * a query, or drops the scope from a controller, a test fails by name rather
 * than the leak being noticed in production.
 */
class PortalAccessBoundaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_there_is_no_self_registration_route(): void
    {
        foreach (['/register', '/signup', '/sign-up'] as $path) {
            $this->get($path)->assertNotFound();
            $this->post($path)->assertNotFound();
        }
    }

    public function test_an_unknown_email_cannot_sign_in(): void
    {
        $this->post(route('login'), [
            'identifier' => 'stranger@example.com',
            'password' => 'password',
        ])->assertSessionHasErrors('identifier');

        $this->assertGuest();
    }

    public function test_a_client_sees_only_the_projects_assigned_to_them(): void
    {
        $alice = User::factory()->create(['role' => 'client']);
        $bob = User::factory()->create(['role' => 'client']);

        $hers = Project::create(['client_id' => $alice->id, 'title' => 'Her Villa', 'status' => 'Planning']);
        $his = Project::create(['client_id' => $bob->id, 'title' => 'His Tower', 'status' => 'Planning']);
        $spare = Project::create(['client_id' => null, 'title' => 'Unassigned Site', 'status' => 'Planning']);

        $this->actingAs($alice)->get(route('portal.dashboard'))
            ->assertOk()
            ->assertSee('Her Villa')
            ->assertDontSee('His Tower')
            ->assertDontSee('Unassigned Site');

        $this->assertSame([$hers->id], Project::visibleTo($alice)->pluck('id')->all());
        $this->assertSame([$his->id], Project::visibleTo($bob)->pluck('id')->all());
    }

    public function test_a_client_with_no_assignment_sees_nothing(): void
    {
        $nobody = User::factory()->create(['role' => 'client']);
        Project::create(['client_id' => null, 'title' => 'Unassigned Site', 'status' => 'Planning']);

        $this->actingAs($nobody)->get(route('portal.dashboard'))
            ->assertOk()
            ->assertSee('No projects have been assigned')
            ->assertDontSee('Unassigned Site');

        $this->assertCount(0, Project::visibleTo($nobody)->get());
    }

    public function test_a_client_cannot_read_another_clients_file(): void
    {
        Storage::fake('local');

        $alice = User::factory()->create(['role' => 'client', 'can_download' => true]);
        $bob = User::factory()->create(['role' => 'client', 'can_download' => true]);

        $his = Project::create(['client_id' => $bob->id, 'title' => 'His Tower', 'status' => 'Planning']);
        $path = "{$his->id}/private-plan.txt";
        Storage::disk('local')->put($path, 'confidential');
        ProjectImage::create(['project_id' => $his->id, 'storage_path' => $path]);

        $this->actingAs($alice)
            ->get(route('portal.files.show', ['path' => $path]))
            ->assertForbidden();

        $this->actingAs($bob)
            ->get(route('portal.files.show', ['path' => $path]))
            ->assertOk();
    }

    /**
     * Unassigning is the revocation mechanism, so it has to actually revoke —
     * not merely hide the project from a listing.
     */
    public function test_unassigning_a_project_revokes_access_immediately(): void
    {
        $alice = User::factory()->create(['role' => 'client']);
        $project = Project::create(['client_id' => $alice->id, 'title' => 'Her Villa', 'status' => 'Planning']);

        $this->actingAs($alice)->get(route('portal.dashboard'))->assertSee('Her Villa');

        $project->update(['client_id' => null]);

        $this->actingAs($alice)->get(route('portal.dashboard'))
            ->assertOk()
            ->assertDontSee('Her Villa');
    }
}
