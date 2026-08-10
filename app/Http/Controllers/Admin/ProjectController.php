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

        $validated['stages'] = collect($request->input('stages', []))
            ->map(fn ($name) => trim((string) $name))
            ->filter()
            ->values();

        $project = Project::create(collect($validated)->except('stages')->all());

        foreach ($validated['stages'] as $i => $name) {
            $project->stages()->create(['name' => $name, 'status' => 'Pending', 'sort_order' => $i]);
        }

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
     */
    private function activity(int $limit = 8): \Illuminate\Support\Collection
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
        $project->update($this->validated($request, $project));

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
        ]);
    }
}
