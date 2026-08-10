@extends('layouts.portal')
@section('title', 'Project Images')

@section('content')
    <header class="mb-7">
        <h1 class="font-wordmark text-[clamp(1.7rem,2.6vw,34px)] text-portal-ink">Project Images</h1>
        <p class="mt-1 text-[15px] text-ink-muted">Site and finish photography for {{ $current?->title ?? 'your project' }}.</p>
    </header>

    <x-portal.filter-bar :action="route('portal.images')" placeholder="Search images..."/>

    <x-portal.card>
        @if (! $current || $current->images->isEmpty())
            <p class="text-ink-muted">No images have been shared yet.</p>
        @elseif ($items->isEmpty())
            <p class="text-ink-muted">No images match those filters.</p>
        @else
            <p class="mb-5 text-[13px] text-ink-muted">
                {{ $items->count() }} of {{ $current->images->count() }} {{ Str::plural('image', $current->images->count()) }}
            </p>
            <ul class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($items as $image)
                    <li class="overflow-hidden rounded-xl border border-portal-ink/10">
                        <span class="relative block aspect-[4/3] bg-alabaster">
                            <img src="{{ route('portal.files.show', ['path' => $image->storage_path]) }}"
                                 alt="{{ $image->caption ?: 'Project photograph' }}" loading="lazy"
                                 class="absolute inset-0 h-full w-full object-cover">
                        </span>
                        <span class="flex items-center gap-3 p-4">
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-[15px] font-semibold text-portal-ink">
                                    {{ $image->caption ?: pathinfo($image->storage_path, PATHINFO_FILENAME) }}
                                </span>
                                <span class="mt-0.5 block text-[12px] tracking-[0.06em] text-ink-muted">
                                    IMAGE · {{ Str::upper($image->created_at->format('M j, Y')) }}
                                </span>
                            </span>
                            @can('download', $current)
                                <a href="{{ route('portal.files.show', ['path' => $image->storage_path, 'download' => 1]) }}"
                                   aria-label="Download {{ $image->caption ?: 'image' }}"
                                   class="grid size-10 shrink-0 place-items-center rounded-lg border border-portal-ink/12 text-portal-ink transition-colors hover:border-portal hover:text-portal">
                                    <x-icon name="download" size="18"/>
                                </a>
                            @endcan
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-portal.card>
@endsection
