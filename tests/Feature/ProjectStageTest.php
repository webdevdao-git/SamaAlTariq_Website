<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectStage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The stages editor on the admin project forms.
 *
 * The form posts the whole list on every save, so what matters is not that a
 * write happened but that the stored set ends up matching what was submitted —
 * including the stages that were left out of it.
 */
class ProjectStageTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'Site Admin',
            'email' => 'admin@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);
    }

    private function project(): Project
    {
        return Project::create([
            'title' => 'Marina Tower',
            'status' => 'Planning',
            'progress' => 0,
        ]);
    }

    /** The non-stage fields the update route requires alongside them. */
    private function payload(array $stages): array
    {
        return [
            'title' => 'Marina Tower',
            'status' => 'Planning',
            'progress' => 0,
            'stages' => $stages,
        ];
    }

    public function test_stages_are_created_with_their_status_and_date(): void
    {
        $project = $this->project();

        $this->actingAs($this->admin())
            ->put(route('admin.projects.update', $project), $this->payload([
                ['name' => 'Tender', 'status' => 'Completed', 'target_date' => '2026-03-01'],
                ['name' => 'Groundwork', 'status' => 'In Progress', 'target_date' => ''],
            ]))
            ->assertRedirect();

        $stages = $project->stages()->orderBy('sort_order')->get();

        $this->assertCount(2, $stages);
        $this->assertSame('Tender', $stages[0]->name);
        $this->assertSame('Completed', $stages[0]->status);
        $this->assertSame('2026-03-01', $stages[0]->target_date->toDateString());

        $this->assertSame('In Progress', $stages[1]->status);
        // An emptied date field posts '', which must land as null rather than
        // as some epoch date.
        $this->assertNull($stages[1]->target_date);
    }

    public function test_position_in_the_payload_becomes_the_sort_order(): void
    {
        $project = $this->project();

        $this->actingAs($this->admin())
            ->put(route('admin.projects.update', $project), $this->payload([
                ['name' => 'First', 'status' => 'Pending'],
                ['name' => 'Second', 'status' => 'Pending'],
                ['name' => 'Third', 'status' => 'Pending'],
            ]));

        $this->assertSame(
            ['First', 'Second', 'Third'],
            $project->stages()->orderBy('sort_order')->pluck('name')->all()
        );
    }

    public function test_a_row_carrying_an_id_is_edited_rather_than_replaced(): void
    {
        $project = $this->project();
        $stage = $project->stages()->create(['name' => 'Tender', 'status' => 'Pending', 'sort_order' => 0]);

        $this->actingAs($this->admin())
            ->put(route('admin.projects.update', $project), $this->payload([
                ['id' => $stage->id, 'name' => 'Tender and costing', 'status' => 'Completed'],
            ]));

        $this->assertSame(1, $project->stages()->count());
        $this->assertSame('Tender and costing', $stage->fresh()->name);
        $this->assertSame('Completed', $stage->fresh()->status);
    }

    public function test_a_stage_left_out_of_the_payload_is_deleted(): void
    {
        $project = $this->project();
        $kept = $project->stages()->create(['name' => 'Kept', 'status' => 'Pending', 'sort_order' => 0]);
        $dropped = $project->stages()->create(['name' => 'Dropped', 'status' => 'Pending', 'sort_order' => 1]);

        $this->actingAs($this->admin())
            ->put(route('admin.projects.update', $project), $this->payload([
                ['id' => $kept->id, 'name' => 'Kept', 'status' => 'Pending'],
            ]));

        $this->assertDatabaseHas('project_stages', ['id' => $kept->id]);
        $this->assertDatabaseMissing('project_stages', ['id' => $dropped->id]);
    }

    public function test_removing_every_row_clears_the_stages(): void
    {
        $project = $this->project();
        $project->stages()->create(['name' => 'Tender', 'status' => 'Pending', 'sort_order' => 0]);

        $this->actingAs($this->admin())
            ->put(route('admin.projects.update', $project), $this->payload([]));

        $this->assertSame(0, $project->stages()->count());
    }

    public function test_a_row_left_without_a_name_is_not_a_stage(): void
    {
        $project = $this->project();

        $this->actingAs($this->admin())
            ->put(route('admin.projects.update', $project), $this->payload([
                ['name' => '  ', 'status' => 'Pending', 'target_date' => ''],
            ]));

        $this->assertSame(0, $project->stages()->count());
    }

    public function test_a_status_outside_the_list_is_rejected(): void
    {
        $project = $this->project();

        $this->actingAs($this->admin())
            ->put(route('admin.projects.update', $project), $this->payload([
                ['name' => 'Tender', 'status' => 'Cancelled'],
            ]))
            ->assertSessionHasErrors('stages.0.status');

        $this->assertSame(0, $project->stages()->count());
    }

    /**
     * The id is a claim about which row is being edited, and an admin editing
     * project A must not be able to reach into project B by changing it.
     */
    public function test_an_id_from_another_project_is_inserted_not_stolen(): void
    {
        $mine = $this->project();
        $theirs = Project::create(['title' => 'Other', 'status' => 'Planning', 'progress' => 0]);
        $foreign = $theirs->stages()->create(['name' => 'Theirs', 'status' => 'Pending', 'sort_order' => 0]);

        $this->actingAs($this->admin())
            ->put(route('admin.projects.update', $mine), $this->payload([
                ['id' => $foreign->id, 'name' => 'Mine now', 'status' => 'Pending'],
            ]));

        $this->assertSame($theirs->id, $foreign->fresh()->project_id);
        $this->assertSame('Theirs', $foreign->fresh()->name);
        $this->assertSame(1, $mine->stages()->count());
        $this->assertSame('Mine now', $mine->stages()->first()->name);
    }

    public function test_stages_can_be_set_while_creating_the_project(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.projects.store'), [
                'title' => 'Marina Tower',
                'status' => 'Planning',
                'progress' => 0,
                'stages' => [
                    ['name' => 'Tender', 'status' => 'Completed', 'target_date' => '2026-03-01'],
                    ['name' => '', 'status' => 'Pending', 'target_date' => ''],
                ],
            ])
            ->assertRedirect(route('admin.projects'));

        $stages = Project::firstWhere('title', 'Marina Tower')->stages;

        $this->assertCount(1, $stages);
        $this->assertSame('Completed', $stages[0]->status);
    }

    public function test_the_edit_form_renders_a_row_per_saved_stage(): void
    {
        $project = $this->project();
        $stage = $project->stages()->create([
            'name' => 'Tender', 'status' => 'In Progress',
            'target_date' => '2026-03-01', 'sort_order' => 0,
        ]);

        $this->actingAs($this->admin())
            ->get(route('admin.projects.edit', $project))
            ->assertOk()
            ->assertSee('name="stages[0][id]" value="'.$stage->id.'"', false)
            ->assertSee('name="stages[0][name]" value="Tender"', false)
            ->assertSee('name="stages[0][target_date]" value="2026-03-01"', false);
    }

    public function test_every_status_in_the_model_list_is_accepted(): void
    {
        $project = $this->project();
        $admin = $this->admin();

        foreach (ProjectStage::STATUSES as $status) {
            $this->actingAs($admin)
                ->put(route('admin.projects.update', $project), $this->payload([
                    ['name' => 'Tender', 'status' => $status],
                ]))
                ->assertSessionHasNoErrors();
        }
    }
}
