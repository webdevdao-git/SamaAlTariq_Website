<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function overview(Request $request): View
    {
        return view('portal.overview', $this->context($request));
    }

    public function images(Request $request): View
    {
        return view('portal.images', $this->context($request));
    }

    public function documents(Request $request): View
    {
        return view('portal.documents', $this->context($request));
    }

    /**
     * The project the portal is currently showing, plus the set it can switch
     * between.
     *
     * `visibleTo` is the scope form of the old `projects_select` RLS policy —
     * an admin sees everything, a client only their own live projects — so a
     * `?project=` for someone else's project simply is not in the set and falls
     * back to the first, rather than 403-ing and confirming it exists.
     */
    private function context(Request $request): array
    {
        $switchable = Project::query()
            ->visibleTo($request->user())
            ->orderByDesc('created_at')
            ->get(['id', 'title', 'location']);

        $requested = $request->integer('project');

        $currentId = $switchable->contains('id', $requested)
            ? $requested
            : $switchable->first()?->id;

        $current = $currentId
            ? Project::with([
                'images' => fn ($q) => $q->latest(),
                'documents' => fn ($q) => $q->latest(),
                'stages' => fn ($q) => $q->orderBy('sort_order'),
                'updates' => fn ($q) => $q->latest(),
            ])->find($currentId)
            : null;

        return compact('current', 'switchable');
    }
}
