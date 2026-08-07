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
        $this->get(route('portal.projects.show', $this->project))->assertRedirect(route('login'));
        $this->get(route('admin.clients.index'))->assertRedirect(route('login'));
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

        $this->actingAs($this->owner)
            ->get(route('portal.projects.show', $other))
            ->assertForbidden();
    }

    public function test_a_soft_deleted_project_disappears_for_its_client_but_not_for_an_admin(): void
    {
        $this->project->delete();

        $this->actingAs($this->owner)
            ->get(route('portal.projects.show', $this->project))
            ->assertForbidden();

        $this->actingAs($this->admin)
            ->get(route('portal.projects.show', $this->project))
            ->assertOk();
    }

    public function test_clients_cannot_reach_the_admin_area(): void
    {
        $this->actingAs($this->owner)->get(route('admin.clients.index'))->assertForbidden();
        $this->actingAs($this->admin)->get(route('admin.clients.index'))->assertOk();
    }

    public function test_an_admin_cannot_delete_their_own_account(): void
    {
        $this->actingAs($this->admin)
            ->delete(route('admin.clients.destroy', $this->admin))
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }
}
