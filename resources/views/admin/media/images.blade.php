@extends('layouts.admin')
@section('title', 'Images Upload')
@section('icon', 'image-plus')
@section('heading', 'Images Upload')
@section('subheading', 'Upload, organize, and share project media with clients')

@section('content')
    {{-- min-w-0 on both columns: grid items default to min-width:auto, so a
         long filename in the activity list would otherwise push the whole grid
         wider than the viewport instead of truncating. --}}
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_340px]">
        <div class="min-w-0">
            <section class="rounded-2xl border border-portal-ink/10 bg-white p-[clamp(1.25rem,2.2vw,28px)]">
                <form method="GET" class="grid gap-4 sm:grid-cols-[minmax(0,240px)_minmax(0,1fr)]">
                    <div>
                        <label for="project" class="mb-2 block text-[14px] text-portal-ink">Select Project</label>
                        <select id="project" name="project" data-auto-submit class="portal-field">
                            <option value="">All Projects</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @selected($projectId === $project->id)>{{ $project->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="q" class="mb-2 block text-[14px] text-portal-ink">Search</label>
                        <div class="relative">
                            <span aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-4 grid place-items-center text-ink-muted">
                                <x-icon name="search"/>
                            </span>
                            <input id="q" name="q" type="search" value="{{ request('q') }}" placeholder="Search media..." class="portal-field pl-12">
                        </div>
                    </div>
                </form>

                <h2 class="mt-8 text-[13px] font-bold tracking-[0.08em] text-ink-muted">UPLOAD MEDIA FILES</h2>

                <form method="POST" action="{{ route('admin.images.store') }}" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $projectId }}">

                    {{-- The whole zone is a <label> for the file input, so click,
                         keyboard and drop all reach the same control without any
                         custom drag handling. --}}
                    <label for="files"
                           class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-portal-ink/15 px-6 py-12 text-center transition-colors hover:border-portal">
                        <span class="text-portal"><x-icon name="download" size="34" class="rotate-180"/></span>
                        <span class="mt-4 text-[19px] font-semibold text-portal-ink">Drag &amp; drop files here</span>
                        <span class="mt-1 text-[14px] text-ink-muted">or tap to browse</span>
                        <span class="mt-4 rounded-lg border border-portal-ink/14 px-5 py-2.5 text-[14px] font-semibold text-portal">Browse Files</span>
                        <span class="mt-4 text-[13px] text-ink-muted">Supports JPG, PNG, WEBP · up to 15 MB each</span>
                    </label>
                    <input id="files" name="files[]" type="file" multiple accept="image/*" class="sr-only" data-submit-on-change>

                    @error('project_id')<span class="field-error" role="alert">Choose a project before uploading.</span>@enderror
                    @error('files')<span class="field-error" role="alert">{{ $message }}</span>@enderror
                    @error('files.*')<span class="field-error" role="alert">{{ $message }}</span>@enderror

                    <noscript>
                        <button type="submit" class="mt-3 rounded-[10px] bg-portal px-5 py-3 text-[13px] font-semibold text-white">Upload</button>
                    </noscript>
                </form>
            </section>

            <section class="mt-6 rounded-2xl border border-portal-ink/10 bg-white p-[clamp(1.25rem,2.2vw,28px)]">
                <div class="flex flex-wrap items-baseline gap-3">
                    <h2 class="font-wordmark text-[17px] tracking-[0.08em] text-portal-ink">PROJECT MEDIA LIBRARY</h2>
                    <span class="text-[14px] font-semibold text-portal">{{ $items->count() }} Items</span>
                </div>

                @if ($items->isEmpty())
                    <p class="mt-6 text-ink-muted">No media {{ request('q') ? 'matches that search' : 'uploaded yet' }}.</p>
                @else
                    <ul class="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                        @foreach ($items as $image)
                            <li class="overflow-hidden rounded-xl border border-portal-ink/12">
                                <span class="relative block aspect-[4/3] bg-alabaster">
                                    <img src="{{ route('portal.files.show', ['path' => $image->storage_path]) }}" alt=""
                                         loading="lazy" class="absolute inset-0 h-full w-full object-cover">
                                </span>
                                <span class="block p-4">
                                    <span class="block truncate text-[15px] font-semibold text-portal-ink">
                                        {{ $image->caption ?: pathinfo($image->storage_path, PATHINFO_FILENAME) }}
                                    </span>
                                    <span class="mt-0.5 block truncate text-[13px] text-ink-muted">{{ $image->project?->title ?? '—' }}</span>

                                    <span class="mt-3 flex items-center justify-between">
                                        <span class="text-[13px] text-ink-muted">{{ $image->created_at->format('M j, Y') }}</span>
                                        <span class="flex items-center gap-1">
                                            <a href="{{ route('portal.files.show', ['path' => $image->storage_path]) }}" target="_blank" rel="noopener"
                                               aria-label="View image" class="grid size-8 place-items-center rounded-lg text-ink-muted hover:bg-alabaster hover:text-portal">
                                                <x-icon name="eye" size="17"/>
                                            </a>
                                            <a href="{{ route('portal.files.show', ['path' => $image->storage_path, 'download' => 1]) }}"
                                               aria-label="Download image" class="grid size-8 place-items-center rounded-lg text-ink-muted hover:bg-alabaster hover:text-portal">
                                                <x-icon name="download" size="17"/>
                                            </a>
                                            <form method="POST" action="{{ route('admin.images.destroy', $image) }}"
                                                  onsubmit="return confirm('Delete this image? The file is removed permanently.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" aria-label="Delete image"
                                                        class="grid size-8 place-items-center rounded-lg text-ink-muted hover:bg-red-50 hover:text-red-600">
                                                    <x-icon name="logout" size="17"/>
                                                </button>
                                            </form>
                                        </span>
                                    </span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>

        <section class="min-w-0 rounded-2xl border border-portal-ink/10 bg-white p-[clamp(1.25rem,2.2vw,28px)]">
            <h2 class="font-wordmark text-[16px] tracking-[0.08em] text-portal-ink">RECENT UPLOAD ACTIVITY</h2>
            <ul class="mt-5 grid min-w-0 gap-4">
                @forelse ($recent as $image)
                    <li class="flex min-w-0 items-center gap-3">
                        <span class="block size-11 shrink-0 overflow-hidden rounded-lg bg-alabaster">
                            <img src="{{ route('portal.files.show', ['path' => $image->storage_path]) }}" alt=""
                                 loading="lazy" class="h-full w-full object-cover">
                        </span>
                        <span class="min-w-0">
                            <span class="block truncate text-[14px] font-semibold text-portal-ink">
                                {{ $image->caption ?: pathinfo($image->storage_path, PATHINFO_FILENAME) }}
                            </span>
                            <span class="block truncate text-[12px] text-ink-muted">
                                {{ $image->project?->title ?? '—' }} · {{ $image->created_at->format('M j, Y · g:i A') }}
                            </span>
                        </span>
                    </li>
                @empty
                    <li class="text-[14px] text-ink-muted">Nothing uploaded yet.</li>
                @endforelse
            </ul>
        </section>
    </div>
@endsection
