@extends('layouts.admin')
@section('title', 'Reports Upload')
@section('icon', 'document')
@section('heading', 'Reports Upload')
@section('subheading', 'Upload and share project reports and documents with clients.')

@section('content')
    <section class="rounded-2xl border border-portal-ink/10 bg-white p-[clamp(1.25rem,2.2vw,32px)]">
        <form method="GET">
            <label for="project" class="mb-2 block text-[14px] text-portal-ink">Select Project</label>
            <select id="project" name="project" data-auto-submit class="portal-field max-w-[620px]">
                <option value="">Choose a project...</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @selected($projectId === $project->id)>{{ $project->title }}</option>
                @endforeach
            </select>
        </form>

        <form method="POST" action="{{ route('admin.reports.store') }}" enctype="multipart/form-data" class="mt-6">
            @csrf
            <input type="hidden" name="project_id" value="{{ $projectId }}">

            <label for="files"
                   class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-portal-ink/15 px-6 py-14 text-center transition-colors hover:border-portal
                          {{ $projectId ? '' : 'pointer-events-none opacity-55' }}">
                <span class="text-portal"><x-icon name="download" size="34" class="rotate-180"/></span>
                <span class="mt-4 text-[19px] font-semibold text-portal-ink">Drag &amp; drop reports here</span>
                <span class="mt-1 text-[14px] text-ink-muted">PDF, DOC, DOCX, XLS or XLSX · up to 15 MB each</span>
                <span class="mt-5 rounded-[10px] bg-portal px-6 py-3 text-[13px] font-bold tracking-[0.1em] text-white">CHOOSE FILES</span>
            </label>
            <input id="files" name="files[]" type="file" multiple
                   accept=".pdf,.doc,.docx,.xls,.xlsx,.txt" class="sr-only" data-submit-on-change
                   @disabled(! $projectId)>

            {{-- The zone is disabled until a project is chosen: an upload with
                 no project has nowhere to go, and failing after the file has
                 been selected wastes the whole transfer. --}}
            @unless ($projectId)
                <p class="mt-3 text-[14px] text-ink-muted">Choose a project first.</p>
            @endunless

            @error('files')<span class="field-error" role="alert">{{ $message }}</span>@enderror
            @error('files.*')<span class="field-error" role="alert">{{ $message }}</span>@enderror

            <noscript>
                <button type="submit" class="mt-3 rounded-[10px] bg-portal px-5 py-3 text-[13px] font-semibold text-white">Upload</button>
            </noscript>
        </form>
    </section>

    <section class="mt-6 rounded-2xl border border-portal-ink/10 bg-white p-[clamp(1.25rem,2.2vw,32px)]">
        <h2 class="font-wordmark text-[21px] text-portal-ink">Uploaded Reports</h2>
        <p class="mt-1 text-[15px] text-ink-muted">These are visible to the client in their portal.</p>

        @if (! $projectId)
            <p class="mt-6 text-ink-muted">Choose a project to see its reports.</p>
        @elseif ($items->isEmpty())
            <p class="mt-6 text-ink-muted">No reports uploaded for this project yet.</p>
        @else
            <ul class="mt-6 grid gap-3">
                @foreach ($items as $document)
                    <li class="flex items-center gap-4 rounded-xl border border-portal-ink/10 p-4">
                        <span class="grid size-11 shrink-0 place-items-center rounded-lg bg-portal/10 text-portal">
                            <x-icon name="document"/>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-[16px] font-semibold text-portal-ink">{{ $document->name }}</span>
                            <span class="mt-0.5 block text-[12px] tracking-[0.06em] text-ink-muted">
                                {{ Str::upper(pathinfo($document->storage_path, PATHINFO_EXTENSION) ?: 'FILE') }}
                                · {{ Str::upper($document->created_at->format('M j, Y')) }}
                            </span>
                        </span>
                        <a href="{{ route('portal.files.show', ['path' => $document->storage_path, 'download' => 1]) }}"
                           aria-label="Download {{ $document->name }}"
                           class="grid size-10 place-items-center rounded-lg text-ink-muted hover:bg-alabaster hover:text-portal">
                            <x-icon name="download" size="18"/>
                        </a>
                        <form method="POST" action="{{ route('admin.reports.destroy', $document) }}"
                              onsubmit="return confirm('Delete “{{ $document->name }}”? The file is removed permanently.')">
                            @csrf @method('DELETE')
                            <button type="submit" aria-label="Delete {{ $document->name }}"
                                    class="grid size-10 place-items-center rounded-lg text-ink-muted hover:bg-red-50 hover:text-red-600">
                                <x-icon name="logout" size="18"/>
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
@endsection
