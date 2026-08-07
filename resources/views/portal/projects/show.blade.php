@extends('layouts.portal')
@section('title', $project->title)

@section('content')
    <a href="{{ route('portal.dashboard') }}" class="text-sm text-ink-muted hover:text-teal">← All projects</a>

    <h1 class="display mt-4 text-4xl text-ink">{{ $project->title }}</h1>
    <p class="mt-2 text-ink-muted">{{ $project->location }} · {{ $project->status }} · {{ $project->progress }}%</p>

    @if ($project->description)
        <p class="mt-6 max-w-[70ch] text-ink">{{ $project->description }}</p>
    @endif

    <section class="mt-10">
        <h2 class="text-lg font-semibold text-ink">Stages</h2>
        <ul class="mt-4 grid gap-2">
            @forelse ($project->stages as $stage)
                <li class="flex items-center justify-between rounded-lg bg-white px-4 py-3">
                    <span class="text-ink">{{ $stage->name }}</span>
                    <span class="text-sm text-ink-muted">
                        {{ $stage->status }}@if ($stage->target_date) · {{ $stage->target_date->format('d M Y') }}@endif
                    </span>
                </li>
            @empty
                <li class="text-ink-muted">No stages recorded.</li>
            @endforelse
        </ul>
    </section>

    <section class="mt-10">
        <h2 class="text-lg font-semibold text-ink">Images</h2>
        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($project->images as $image)
                <figure class="overflow-hidden rounded-lg bg-white">
                    <img src="{{ route('portal.files.show', ['path' => $image->storage_path]) }}"
                         alt="{{ $image->caption }}" loading="lazy" class="aspect-[4/3] w-full object-cover">
                    <figcaption class="flex items-center justify-between gap-2 p-3 text-sm">
                        <span class="text-ink-muted">{{ $image->caption ?: '—' }}</span>
                        @can('download', $project)
                            <a href="{{ route('portal.files.show', ['path' => $image->storage_path, 'download' => 1]) }}"
                               class="text-teal hover:underline">Download</a>
                        @endcan
                    </figcaption>
                </figure>
            @empty
                <p class="text-ink-muted">No images yet.</p>
            @endforelse
        </div>
    </section>

    <section class="mt-10">
        <h2 class="text-lg font-semibold text-ink">Reports</h2>
        <ul class="mt-4 grid gap-2">
            @forelse ($project->documents as $document)
                <li class="flex items-center justify-between rounded-lg bg-white px-4 py-3">
                    <span class="text-ink">{{ $document->name }}</span>
                    @can('download', $project)
                        <a href="{{ route('portal.files.show', ['path' => $document->storage_path, 'download' => 1]) }}"
                           class="text-sm text-teal hover:underline">Download</a>
                    @else
                        <a href="{{ route('portal.files.show', ['path' => $document->storage_path]) }}"
                           class="text-sm text-teal hover:underline">View</a>
                    @endcan
                </li>
            @empty
                <li class="text-ink-muted">No reports yet.</li>
            @endforelse
        </ul>
    </section>

    <section class="mt-10">
        <h2 class="text-lg font-semibold text-ink">Updates</h2>
        <ul class="mt-4 grid gap-2">
            @forelse ($project->updates as $update)
                <li class="rounded-lg bg-white px-4 py-3">
                    <p class="text-ink">{{ $update->note }}</p>
                    <p class="mt-1 text-xs text-ink-muted">{{ $update->created_at->format('d M Y') }}</p>
                </li>
            @empty
                <li class="text-ink-muted">No updates yet.</li>
            @endforelse
        </ul>
    </section>
@endsection
