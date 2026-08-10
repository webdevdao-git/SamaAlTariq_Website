@extends('layouts.portal')
@section('title', 'Reports & Documents')

@section('content')
    <header class="mb-7">
        <h1 class="font-wordmark text-[clamp(1.7rem,2.6vw,34px)] text-portal-ink">Reports &amp; Documents</h1>
        <p class="mt-1 text-[15px] text-ink-muted">Progress reports and paperwork shared with you.</p>
    </header>

    <x-portal.card>
        @if (! $current || $current->documents->isEmpty())
            <p class="text-ink-muted">No reports have been shared yet.</p>
        @else
            <ul class="grid gap-3">
                @foreach ($current->documents as $document)
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

                        {{-- A client without download rights can still open the
                             report in the browser; only the attachment is gated. --}}
                        <a href="{{ route('portal.files.show', ['path' => $document->storage_path] + (auth()->user()->canDownloadFiles() ? ['download' => 1] : [])) }}"
                           class="grid size-11 shrink-0 place-items-center rounded-xl border border-portal-ink/12 text-portal-ink transition-colors hover:border-portal hover:text-portal"
                           aria-label="{{ auth()->user()->canDownloadFiles() ? 'Download' : 'View' }} {{ $document->name }}">
                            <x-icon :name="auth()->user()->canDownloadFiles() ? 'download' : 'eye'"/>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-portal.card>
@endsection
