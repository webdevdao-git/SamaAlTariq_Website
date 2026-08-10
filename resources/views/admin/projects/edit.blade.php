@extends('layouts.admin')
@section('title', 'Edit ' . $project->title)
@section('icon', 'pencil')
@section('heading', 'Edit Project')
@section('subheading', $project->title)

@section('content')
    <a href="{{ route('admin.dashboard') }}" class="mb-6 inline-flex items-center gap-2 text-[14px] text-ink-muted hover:text-portal">
        ← Back to dashboard
    </a>

    <form method="POST" action="{{ route('admin.projects.update', $project) }}"
          class="rounded-2xl border border-portal-ink/10 bg-white p-[clamp(1.25rem,2.2vw,32px)]">
        @csrf
        @method('PUT')

        <div class="grid gap-6 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="title" class="mb-2 block text-[14px] text-portal-ink">Project Name <span class="text-red-500">*</span></label>
                <input id="title" name="title" required maxlength="200" value="{{ old('title', $project->title) }}"
                       class="portal-field" @error('title') aria-invalid="true" @enderror>
                @error('title')<span class="field-error" role="alert">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="location" class="mb-2 block text-[14px] text-portal-ink">Location</label>
                <input id="location" name="location" maxlength="200" value="{{ old('location', $project->location) }}" class="portal-field">
                @error('location')<span class="field-error" role="alert">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="project_type" class="mb-2 block text-[14px] text-portal-ink">Project Type</label>
                <input id="project_type" name="project_type" maxlength="120" value="{{ old('project_type', $project->project_type) }}" class="portal-field">
            </div>

            <div>
                <label for="status" class="mb-2 block text-[14px] text-portal-ink">Status <span class="text-red-500">*</span></label>
                <select id="status" name="status" class="portal-field">
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(old('status', $project->status) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="progress" class="mb-2 block text-[14px] text-portal-ink">Progress (%) <span class="text-red-500">*</span></label>
                <input id="progress" name="progress" type="number" min="0" max="100" required
                       value="{{ old('progress', $project->progress) }}" class="portal-field"
                       @error('progress') aria-invalid="true" @enderror>
                @error('progress')<span class="field-error" role="alert">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="start_date" class="mb-2 block text-[14px] text-portal-ink">Start Date</label>
                <input id="start_date" name="start_date" type="date"
                       value="{{ old('start_date', $project->start_date?->toDateString()) }}" class="portal-field">
            </div>

            <div>
                <label for="due_date" class="mb-2 block text-[14px] text-portal-ink">Due Date</label>
                <input id="due_date" name="due_date" type="date"
                       value="{{ old('due_date', $project->due_date?->toDateString()) }}" class="portal-field"
                       @error('due_date') aria-invalid="true" @enderror>
                @error('due_date')<span class="field-error" role="alert">{{ $message }}</span>@enderror
            </div>

            <div class="sm:col-span-2">
                <label for="client_id" class="mb-2 block text-[14px] text-portal-ink">Client</label>
                <select id="client_id" name="client_id" class="portal-field">
                    <option value="">Unassigned</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" @selected((int) old('client_id', $project->client_id) === $client->id)>
                            {{ $client->name }} — {{ $client->email }}
                        </option>
                    @endforeach
                </select>
                <span class="mt-2 block text-xs text-ink-muted">
                    Only the assigned client can see this project in their portal.
                </span>
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="mb-2 block text-[14px] text-portal-ink">Description</label>
                <textarea id="description" name="description" rows="4" maxlength="5000" class="portal-field resize-y">{{ old('description', $project->description) }}</textarea>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-portal-ink/10 pt-6">
            <button type="submit" class="rounded-[10px] bg-portal px-6 py-3 text-[13px] font-bold tracking-[0.1em] text-white transition-colors hover:bg-portal-dark">
                SAVE CHANGES
            </button>
            <a href="{{ route('admin.dashboard') }}" class="rounded-[10px] border border-portal-ink/14 px-6 py-3 text-[13px] font-semibold text-portal-ink hover:border-portal">
                Cancel
            </a>
        </div>
    </form>

    @if ($project->stages->isNotEmpty())
        <section class="mt-6 rounded-2xl border border-portal-ink/10 bg-white p-[clamp(1.25rem,2.2vw,32px)]">
            <h2 class="font-wordmark text-[19px] text-portal-ink">Stages</h2>
            <p class="mt-1 text-[14px] text-ink-muted">Shown on the client's project timeline.</p>
            <ul class="mt-5 grid gap-2">
                @foreach ($project->stages as $stage)
                    <li class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-portal-ink/10 px-4 py-3">
                        <span class="text-[15px] text-portal-ink">{{ $stage->name }}</span>
                        <span class="text-[14px] text-ink-muted">
                            {{ $stage->status }}@if ($stage->target_date) · {{ $stage->target_date->format('M j, Y') }}@endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    {{-- Archiving is separated from the edit form: it is a different intent,
         and a stray Enter in a text field must never trigger it. --}}
    <section class="mt-6 rounded-2xl border border-red-200 bg-white p-[clamp(1.25rem,2.2vw,32px)]">
        <h2 class="font-wordmark text-[19px] text-portal-ink">Archive project</h2>
        <p class="mt-1 max-w-[60ch] text-[14px] text-ink-muted">
            The client stops seeing it immediately. Nothing is deleted — images, reports and
            stages are kept, and an administrator can still open it.
        </p>
        <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" class="mt-5"
              onsubmit="return confirm('Archive “{{ $project->title }}”? The client will no longer see it.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-[10px] border border-red-300 px-5 py-2.5 text-[13px] font-semibold text-red-700 transition-colors hover:bg-red-50">
                Archive project
            </button>
        </form>
    </section>
@endsection
