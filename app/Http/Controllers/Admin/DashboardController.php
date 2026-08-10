<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        /*
         * Counts come from one grouped query rather than four count() calls,
         * and deliberately ignore archived projects — an admin reading "4
         * projects" while three of them are in the bin would be misleading.
         */
        $counts = Project::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            ['label' => 'TOTAL PROJECTS',     'value' => $counts->sum(),                'note' => 'All Projects',            'icon' => 'building',     'tint' => 'text-portal-ink/60'],
            ['label' => 'ONGOING PROJECTS',   'value' => $counts['In Progress'] ?? 0,   'note' => 'In Progress',             'icon' => 'ruler',        'tint' => 'bg-sky-50 text-sky-600'],
            ['label' => 'ON HOLD PROJECTS',   'value' => $counts['On Hold'] ?? 0,       'note' => 'Currently Paused',        'icon' => 'clock',        'tint' => 'bg-amber-50 text-amber-600'],
            ['label' => 'COMPLETED PROJECTS', 'value' => $counts['Completed'] ?? 0,     'note' => 'Successfully Completed',  'icon' => 'check-circle', 'tint' => 'bg-emerald-50 text-emerald-600'],
        ];

        $query = Project::query()->with('client:id,name');

        if (($status = $request->string('status')->value()) && $status !== 'all') {
            $query->where('status', $status);
        }

        if (filled($term = $request->string('q')->trim()->value())) {
            // Escaped so % and _ in the term cannot act as wildcards.
            $like = '%'.addcslashes($term, '%_\\').'%';
            $query->where(fn ($q) => $q
                ->where('title', 'like', $like)
                ->orWhere('location', 'like', $like)
                ->orWhere('project_type', 'like', $like));
        }

        $projects = $query->latest()->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'projects' => $projects,
            'total' => $counts->sum(),
            'statuses' => ['Planning', 'In Progress', 'On Hold', 'Completed'],
        ]);
    }
}
