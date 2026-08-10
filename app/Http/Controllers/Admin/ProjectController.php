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
            'projects' => Project::with('client:id,name')->latest()->get(),
        ]);
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
