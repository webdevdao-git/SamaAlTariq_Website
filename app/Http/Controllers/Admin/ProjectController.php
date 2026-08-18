<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Project management.
 *
 * Admin-only access is applied once by `can:viewAny,App\Models\User` on the
 * route group, so these methods carry the per-record checks only.
 */
class ProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => Project::with(['client:id,name', 'images'])->latest()->get(),
            'clients' => \App\Models\User::where('role', 'client')->orderBy('name')->get(['id', 'name', 'email']),
            'statuses' => ['Planning', 'In Progress', 'On Hold', 'Completed'],
            'activity' => $this->activity(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $project = Project::create(collect($validated)->except('stages')->all());
        $this->syncStages($project, $validated['stages'] ?? []);

        return redirect()
            ->route('admin.projects')
            ->with('status', "“{$project->title}” created.");
    }

    /**
     * A recent-activity feed assembled from the records themselves.
     *
     * There is no audit table, so this is derived from created_at across
     * projects, images and documents rather than invented. That means it is
     * accurate about *what* happened and *when*, but cannot attribute *who* —
     * so it does not pretend to.
     *
     * Twelve rather than eight: the panel shows five and folds the rest away,
     * so the limit is now how much there is to open rather than how much is on
     * screen. Each of the three queries takes that many and the merge keeps the
     * newest twelve of the up-to-36 — the same shape as before, one notch
     * wider.
     */
    private function activity(int $limit = 12): \Illuminate\Support\Collection
    {
        $projects = Project::latest()->take($limit)->get()
            ->map(fn ($p) => ['icon' => 'file-plus', 'text' => "New project “{$p->title}” was added.", 'at' => $p->created_at]);

        $images = \App\Models\ProjectImage::with('project:id,title')->latest()->take($limit)->get()
            ->map(fn ($i) => ['icon' => 'image-plus',
                'text' => trim(($i->caption ?: 'An image').' was uploaded to “'.($i->project?->title ?? 'a project').'”.'),
                'at' => $i->created_at]);

        $documents = \App\Models\ProjectDocument::with('project:id,title')->latest()->take($limit)->get()
            ->map(fn ($d) => ['icon' => 'document',
                'text' => "“{$d->name}” was shared with “".($d->project?->title ?? 'a project').'”.',
                'at' => $d->created_at]);

        return $projects->concat($images)->concat($documents)
            ->sortByDesc('at')->take($limit)->values();
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.edit', [
            'project' => $project->load(['stages' => fn ($q) => $q->orderBy('sort_order')]),
            'clients' => \App\Models\User::where('role', 'client')->orderBy('name')->get(['id', 'name', 'email']),
            'statuses' => ['Planning', 'In Progress', 'On Hold', 'Completed'],
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $this->validated($request, $project);

        $project->update(collect($validated)->except('stages')->all());
        $this->syncStages($project, $validated['stages'] ?? []);

        return redirect()
            ->route('admin.dashboard')
            ->with('status', "“{$project->title}” updated.");
    }

    public function destroy(Project $project): RedirectResponse
    {
        // Soft delete: the client stops seeing it, an admin can still review it.
        $project->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('status', "“{$project->title}” archived.");
    }

    /**
     * Writes the submitted stage rows over the project's current ones.
     *
     * The form posts the whole list every time, so this is a replace rather
     * than a patch: a row carrying an id updates that stage, a row without one
     * inserts, and a stage whose row did not come back is gone from the form
     * and so is deleted. Position in the payload becomes `sort_order`, which is
     * what the client's timeline orders by.
     */
    private function syncStages(Project $project, array $rows): void
    {
        $own = $project->stages()->pluck('id')->all();
        $kept = [];

        foreach (array_values($rows) as $i => $row) {
            $name = trim((string) ($row['name'] ?? ''));

            // A row with no name is the blank the form always offers, not a
            // stage the admin meant to create.
            if ($name === '') {
                continue;
            }

            $attributes = [
                'name' => $name,
                'status' => $row['status'] ?? 'Pending',
                // An emptied date field posts '', which is not a null date.
                'target_date' => ($row['target_date'] ?? '') ?: null,
                'sort_order' => $i,
            ];

            $id = (int) ($row['id'] ?? 0);

            // Checked against this project's own ids: an id belonging to some
            // other project is treated as a new stage rather than stolen.
            if ($id && in_array($id, $own, true)) {
                $project->stages()->whereKey($id)->update($attributes);
                $kept[] = $id;
            } else {
                $kept[] = $project->stages()->create($attributes)->id;
            }
        }

        // Empty $kept means every row was removed, and whereNotIn on an empty
        // list matches everything — which is the intent, not an accident.
        $project->stages()->whereNotIn('id', $kept)->delete();
    }

    private function validated(Request $request, ?Project $project = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'client_id' => ['nullable', Rule::exists('users', 'id')->where('role', 'client')],
            'description' => ['nullable', 'string', 'max:5000'],
            'location' => ['nullable', 'string', 'max:200'],
            'project_type' => ['nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in(['Planning', 'In Progress', 'On Hold', 'Completed'])],
            'progress' => ['required', 'integer', 'between:0,100'],
            'start_date' => ['nullable', 'date'],
            // A handover before the start date is a data-entry slip, not a plan.
            'due_date' => ['nullable', 'date', 'after_or_equal:start_date'],

            // Stages are optional, and a row may be left entirely blank — the
            // name is what decides whether it becomes a stage at all, so it is
            // nullable here and filtered in syncStages rather than rejected.
            'stages' => ['nullable', 'array'],
            'stages.*.id' => ['nullable', 'integer'],
            'stages.*.name' => ['nullable', 'string', 'max:200'],
            'stages.*.status' => ['nullable', Rule::in(\App\Models\ProjectStage::STATUSES)],
            'stages.*.target_date' => ['nullable', 'date'],
        ]);
    }
}
