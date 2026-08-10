@extends('layouts.portal')
@section('title', 'Project Overview')

@section('content')
    <header class="mb-7">
        <h1 class="font-wordmark text-[clamp(1.7rem,2.6vw,34px)] text-portal-ink">Project Overview</h1>
        <p class="mt-1 text-[15px] text-ink-muted">Track your project status and latest updates.</p>
    </header>

    @if (! $current)
        <x-portal.card>
            <p class="text-ink-muted">No projects have been assigned to your account yet.</p>
        </x-portal.card>
    @else
        <x-portal.card class="!p-[clamp(1rem,1.6vw,24px)]">
            <div class="grid gap-7 lg:grid-cols-[minmax(0,420px)_minmax(0,1fr)]">
                <div class="relative aspect-[4/3] w-full overflow-hidden rounded-xl bg-alabaster">
                    @if ($cover = $current->images->first())
                        <img src="{{ route('portal.files.show', ['path' => $cover->storage_path]) }}"
                             alt="{{ $cover->caption ?: $current->title }}" loading="eager"
                             class="absolute inset-0 h-full w-full object-cover">
                    @endif
                </div>

                <div class="flex flex-col justify-center">
                    <h2 class="font-wordmark text-[clamp(1.5rem,2.2vw,30px)] text-portal-ink">{{ $current->title }}</h2>

                    @if ($current->location)
                        <p class="mt-2 flex items-center gap-2 text-[15px] text-ink-muted">
                            <x-icon name="map-pin" size="17"/>
                            {{ $current->location }}
                        </p>
                    @endif

                    <dl class="mt-7 grid gap-5 sm:grid-cols-3">
                        <div>
                            <dt class="text-[13px] text-ink-muted">Expected Handover</dt>
                            <dd class="mt-1 text-[16px] font-semibold text-portal-ink">
                                {{ $current->due_date?->format('M j, Y') ?? '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-[13px] text-ink-muted">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-block rounded-md bg-emerald-50 px-2.5 py-1 text-[13px] font-semibold text-emerald-700">
                                    {{ $current->status }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-[13px] text-ink-muted">Project Start</dt>
                            <dd class="mt-1 text-[16px] font-semibold text-portal-ink">
                                {{ $current->start_date?->format('M j, Y') ?? '—' }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-7">
                        <div class="flex items-center justify-between">
                            <span class="text-[16px] text-portal-ink">Overall Progress</span>
                            <span class="text-[16px] font-bold text-portal-ink">{{ $current->progress }}%</span>
                        </div>
                        {{-- Announced as a progress bar so the percentage is not
                             only conveyed by the width of a coloured div. --}}
                        <div role="progressbar" aria-valuenow="{{ $current->progress }}" aria-valuemin="0" aria-valuemax="100"
                             aria-label="Overall progress"
                             class="mt-2.5 h-2 w-full overflow-hidden rounded-full bg-portal-ink/10">
                            <span class="block h-full rounded-full bg-portal" style="width: {{ $current->progress }}%"></span>
                        </div>
                    </div>
                </div>
            </div>
        </x-portal.card>

        @if ($current->stages->isNotEmpty())
            <x-portal.card class="mt-6" title="Project Timeline" subtitle="Stages and their target completion dates.">
                <x-portal.timeline :stages="$current->stages"/>
            </x-portal.card>
        @endif

        @if ($current->images->isNotEmpty())
            <x-portal.card class="mt-6 max-w-[760px]" title="Recent Images">
                <x-slot:action>
                    <a href="{{ route('portal.images', ['project' => $current->id]) }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-portal-ink/12 px-4 py-2.5 text-[14px] font-semibold text-portal-ink transition-colors hover:border-portal hover:text-portal">
                        View All Images
                        <x-icon name="chevron-right" size="15"/>
                    </a>
                </x-slot:action>

                <ul class="grid gap-3">
                    @foreach ($current->images->take(2) as $image)
                        <li class="flex items-center gap-4 rounded-xl border border-portal/25 p-3">
                            <span class="block size-12 shrink-0 overflow-hidden rounded-lg bg-alabaster">
                                <img src="{{ route('portal.files.show', ['path' => $image->storage_path]) }}" alt=""
                                     loading="lazy" class="h-full w-full object-cover">
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-[16px] font-semibold text-portal-ink">
                                    {{ $image->caption ?: pathinfo($image->storage_path, PATHINFO_FILENAME) }}
                                </span>
                                <span class="mt-0.5 block text-[12px] tracking-[0.06em] text-ink-muted">
                                    IMAGE · {{ Str::upper($image->created_at->format('M j, Y')) }}
                                </span>
                            </span>

                            @can('download', $current)
                                <a href="{{ route('portal.files.show', ['path' => $image->storage_path, 'download' => 1]) }}"
                                   aria-label="Download {{ $image->caption ?: 'image' }}"
                                   class="grid size-11 shrink-0 place-items-center rounded-xl border border-portal-ink/12 text-portal-ink transition-colors hover:border-portal hover:text-portal">
                                    <x-icon name="download"/>
                                </a>
                            @endcan
                        </li>
                    @endforeach
                </ul>
            </x-portal.card>
        @endif
    @endif
@endsection
