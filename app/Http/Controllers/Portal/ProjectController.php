<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Projects the signed-in user may see.
     *
     * `visibleTo` is the scope form of the old `projects_select` RLS policy:
     * admins see everything, a client sees only their own live projects. The
     * policy on `show` covers the single-record case; the scope covers the list,
     * because a policy cannot filter a query.
     */
    public function index(Request $request): View
    {
        $projects = Project::query()
            ->visibleTo($request->user())
            ->with('client:id,name')
            ->latest()
            ->paginate(20);

        return view('portal.projects.index', compact('projects'));
    }

    public function show(Request $request, Project $project): View
    {
        $this->authorize('view', $project);

        $project->load(['images', 'documents', 'updates', 'stages', 'client:id,name']);

        return view('portal.projects.show', compact('project'));
    }
}
