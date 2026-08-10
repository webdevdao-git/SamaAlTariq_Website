@extends('layouts.admin')
@section('title', 'Dashboard')
@section('icon', 'grid')
@section('heading', 'Dashboard')
@section('subheading', 'Welcome back, ' . auth()->user()->name)

@section('content')
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <div class="rounded-2xl border border-portal-ink/10 bg-white p-6">
                <div class="flex items-start justify-between gap-4">
                    <p class="text-[11px] font-bold tracking-[0.1em] text-ink-muted">{{ $stat['label'] }}</p>
                    <span class="grid size-11 shrink-0 place-items-center rounded-xl {{ $stat['tint'] }}">
                        <x-icon :name="$stat['icon']" size="21"/>
                    </span>
                </div>
                <p class="mt-4 text-[38px] leading-none font-semibold text-portal-ink">{{ $stat['value'] }}</p>
                <p class="mt-4 text-[14px] text-ink-muted">{{ $stat['note'] }}</p>
            </div>
        @endforeach
    </div>

    <section class="mt-6 rounded-2xl border border-portal-ink/10 bg-white p-[clamp(1.25rem,2.2vw,32px)]">
        <div class="flex items-center gap-4">
            <span class="text-portal-ink/60"><x-icon name="grid" size="24"/></span>
            <div>
                <h2 class="font-wordmark text-[19px] tracking-[0.06em] text-portal-ink">ALL PROJECTS</h2>
                <p class="mt-0.5 text-[14px] text-ink-muted">
                    {{ $projects->count() }} of {{ $total }} {{ Str::plural('project', $total) }}
                </p>
            </div>
        </div>

        {{-- One GET form so status and search combine and stay in the URL. --}}
        <form method="GET" class="mt-7 grid gap-4 sm:grid-cols-[minmax(0,240px)_minmax(0,1fr)]">
            <div>
                <label for="status" class="mb-2 block text-[14px] text-portal-ink">Status</label>
                <select id="status" name="status" data-auto-submit class="portal-field">
                    <option value="all">All status</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="q" class="mb-2 block text-[14px] text-portal-ink">Search</label>
                <div class="relative">
                    <span aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-4 grid place-items-center text-ink-muted">
                        <x-icon name="search"/>
                    </span>
                    <input id="q" name="q" type="search" value="{{ request('q') }}"
                           placeholder="Search projects..." class="portal-field pl-12">
                </div>
            </div>

            <noscript>
                <button type="submit" class="rounded-[10px] bg-portal px-5 py-3 text-[13px] font-semibold text-white">Apply</button>
            </noscript>
        </form>

        <ul class="mt-8">
            @forelse ($projects as $project)
                @php($badge = $project->statusBadge())
                <li class="grid items-center gap-4 border-t border-portal-ink/10 py-6 lg:grid-cols-[minmax(0,1fr)_260px_120px_120px_44px]">
                    <div class="min-w-0">
                        <p class="text-[17px] font-semibold text-portal-ink">{{ $project->title }}</p>
                        <p class="mt-1.5 flex items-center gap-1.5 text-[14px] text-ink-muted">
                            <x-icon name="map-pin" size="15"/>
                            <span class="truncate">
                                {{ $project->location ?: '—' }}@if ($project->project_type) · {{ $project->project_type }}@endif
                            </span>
                        </p>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-[14px]">
                            <span class="text-ink-muted">Progress</span>
                            <span class="font-semibold text-portal-ink">{{ $project->progress }}%</span>
                        </div>
                        <div role="progressbar" aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100"
                             aria-label="{{ $project->title }} progress"
                             class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-portal-ink/10">
                            <span class="block h-full rounded-full bg-portal" style="width: {{ $project->progress }}%"></span>
                        </div>
                    </div>

                    <div>
                        <span class="inline-block rounded-md px-2.5 py-1 text-[13px] font-semibold {{ $badge['classes'] }}">
                            {{ $badge['label'] }}
                        </span>
                    </div>

                    <div class="text-right">
                        <p class="text-[11px] font-bold tracking-[0.1em] text-ink-muted">DUE</p>
                        <p class="mt-1 text-[14px] text-portal-ink">{{ $project->due_date?->format('M j, Y') ?? '—' }}</p>
                    </div>

                    <div class="lg:text-right">
                        <a href="{{ route('admin.projects.edit', $project) }}"
                           aria-label="Edit {{ $project->title }}"
                           class="inline-grid size-10 place-items-center rounded-lg text-ink-muted transition-colors hover:bg-alabaster hover:text-portal">
                            <x-icon name="pencil"/>
                        </a>
                    </div>
                </li>
            @empty
                <li class="border-t border-portal-ink/10 py-10 text-center text-ink-muted">
                    No projects match those filters.
                </li>
            @endforelse
        </ul>
    </section>
@endsection
