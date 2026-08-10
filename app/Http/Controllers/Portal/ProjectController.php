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
        $context = $this->context($request);

        $context['items'] = $context['current']
            ? $this->filtered($context['current']->images(), $request, ['caption', 'storage_path'])
            : collect();

        return view('portal.images', $context);
    }

    public function documents(Request $request): View
    {
        $context = $this->context($request);

        $context['items'] = $context['current']
            ? $this->filtered($context['current']->documents(), $request, ['name', 'storage_path'])
            : collect();

        return view('portal.documents', $context);
    }

    /**
     * Applies the filter bar's search, sort and date range.
     *
     * Filtering runs in SQL rather than over an eager-loaded collection: a
     * project with hundreds of photographs would otherwise load every row to
     * discard most of them.
     *
     * The search term is matched against the stored path as well as the visible
     * label, because most imported images have no caption and the filename is
     * the only thing a client can actually search by.
     *
     * @param  array<int,string>  $searchable
     */
    private function filtered($relation, Request $request, array $searchable)
    {
        $query = $relation->getQuery()->clone();

        if (filled($term = $request->string('q')->trim()->value())) {
            $query->where(function ($q) use ($term, $searchable) {
                foreach ($searchable as $column) {
                    // escape() keeps % and _ in the term from acting as wildcards
                    $q->orWhere($column, 'like', '%'.addcslashes($term, '%_\\').'%');
                }
            });
        }

        // Dates are inclusive at both ends: "to 17 June" must include the 17th,
        // which a plain <= on a timestamp column would exclude.
        if ($request->date('from')) {
            $query->where('created_at', '>=', $request->date('from')->startOfDay());
        }

        if ($request->date('to')) {
            $query->where('created_at', '<=', $request->date('to')->endOfDay());
        }

        $sorted = match ($request->string('sort')->value()) {
            'oldest' => $query->oldest(),
            'name' => $query->orderBy($searchable[0])->orderBy('storage_path'),
            default => $query->latest(),
        };

        return $sorted->get();
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
