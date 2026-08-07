@extends('layouts.portal')
@section('title', 'Projects')

@section('content')
    <h1 class="display text-4xl text-ink">Projects</h1>

    @if (auth()->user()->must_change_password)
        <p class="mt-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-900">
            You are still using a temporary password. Please change it.
        </p>
    @endif

    <div class="mt-8 grid gap-4">
        @forelse ($projects as $project)
            <a href="{{ route('portal.projects.show', $project) }}"
               class="flex flex-wrap items-center justify-between gap-4 rounded-xl bg-white p-6 transition hover:shadow-md">
                <div>
                    <p class="text-lg font-semibold text-ink">{{ $project->title }}</p>
                    <p class="text-sm text-ink-muted">
                        {{ $project->location ?: '—' }}
                        @can('viewAny', App\Models\User::class)
                            · {{ $project->client?->name ?? 'Unassigned' }}
                        @endcan
                    </p>
                </div>
                <div class="flex items-center gap-6">
                    <span class="text-sm text-ink-muted">{{ $project->status }}</span>
                    <span class="w-32">
                        <span class="block h-1.5 w-full overflow-hidden rounded-full bg-black/10">
                            <span class="block h-full rounded-full bg-teal" style="width: {{ $project->progress }}%"></span>
                        </span>
                        <span class="mt-1 block text-xs text-ink-muted">{{ $project->progress }}%</span>
                    </span>
                </div>
            </a>
        @empty
            <p class="rounded-xl bg-white p-6 text-ink-muted">No projects yet.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $projects->links() }}</div>
@endsection
