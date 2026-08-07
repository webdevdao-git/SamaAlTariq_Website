<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The replacement for the Supabase storage policies. Files sit outside the
 * document root, so this route is the only way to reach them.
 */
class PrivateFileTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private User $stranger;
    private Project $project;
    private string $path;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->owner = User::factory()->create(['role' => 'client', 'can_download' => false]);
        $this->stranger = User::factory()->create(['role' => 'client', 'can_download' => true]);

        $this->project = Project::create([
            'client_id' => $this->owner->id,
            'title' => 'Emirates Hills Villa',
            'status' => 'In Progress',
        ]);

        $this->path = "{$this->project->id}/site-photo.txt";
        Storage::disk('local')->put($this->path, 'confidential');
    }

    public function test_the_owner_may_view_the_file_inline(): void
    {
        $this->actingAs($this->owner)
            ->get(route('portal.files.show', ['path' => $this->path]))
            ->assertOk()
            ->assertHeader('Cache-Control', 'no-store, private');
    }

    public function test_a_client_without_the_download_flag_is_refused_the_attachment(): void
    {
        $this->actingAs($this->owner)
            ->get(route('portal.files.show', ['path' => $this->path, 'download' => 1]))
            ->assertForbidden();
    }

    public function test_a_client_who_does_not_own_the_project_is_refused(): void
    {
        $this->actingAs($this->stranger)
            ->get(route('portal.files.show', ['path' => $this->path]))
            ->assertForbidden();
    }

    public function test_an_admin_may_download_anything(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('portal.files.show', ['path' => $this->path, 'download' => 1]))
            ->assertOk();
    }

    public function test_a_path_outside_a_project_folder_is_not_served(): void
    {
        Storage::disk('local')->put('secrets.txt', 'nope');

        $this->actingAs($this->owner)
            ->get(route('portal.files.show', ['path' => 'secrets.txt']))
            ->assertNotFound();
    }
}
