@extends('layouts.admin')
@section('title', 'Add Projects')
@section('icon', 'file-plus')
@section('heading', 'Add Projects')
@section('subheading', 'Create a project, then assign media and a client.')

@section('content')
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">

        <section class="min-w-0 rounded-2xl border border-portal-ink/10 bg-white p-[clamp(1.25rem,2.2vw,32px)]">
            <h2 class="font-wordmark text-[17px] tracking-[0.08em] text-portal-ink">QUICK ADD NEW PROJECT</h2>

            <form method="POST" action="{{ route('admin.projects.store') }}" class="mt-6">
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="title" class="mb-2 block text-[14px] text-portal-ink">Project Name <span class="text-red-500">*</span></label>
                        <input id="title" name="title" required maxlength="200" value="{{ old('title') }}"
                               placeholder="e.g. Villa 142" class="portal-field" @error('title') aria-invalid="true" @enderror>
                        @error('title')<span class="field-error" role="alert">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="location" class="mb-2 block text-[14px] text-portal-ink">Location</label>
                        <input id="location" name="location" maxlength="200" value="{{ old('location') }}"
                               placeholder="e.g. Jumeirah, Dubai" class="portal-field">
                    </div>

                    <div>
                        <label for="project_type" class="mb-2 block text-[14px] text-portal-ink">Project Type</label>
                        <input id="project_type" name="project_type" maxlength="120" value="{{ old('project_type') }}"
                               placeholder="e.g. Villa" class="portal-field">
                    </div>

                    <div>
                        <label for="start_date" class="mb-2 block text-[14px] text-portal-ink">Start Date</label>
                        <input id="start_date" name="start_date" type="date" value="{{ old('start_date') }}" class="portal-field">
                    </div>

                    <div>
                        <label for="due_date" class="mb-2 block text-[14px] text-portal-ink">Due Date</label>
                        <input id="due_date" name="due_date" type="date" value="{{ old('due_date') }}" class="portal-field"
                               @error('due_date') aria-invalid="true" @enderror>
                        @error('due_date')<span class="field-error" role="alert">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="status" class="mb-2 block text-[14px] text-portal-ink">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" class="portal-field">
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected(old('status', 'Planning') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="progress" class="mb-2 block text-[14px] text-portal-ink">Progress (%) <span class="text-red-500">*</span></label>
                        <input id="progress" name="progress" type="number" min="0" max="100" required
                               value="{{ old('progress', 0) }}" class="portal-field">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="client_id" class="mb-2 block text-[14px] text-portal-ink">Client <span class="text-ink-muted">(optional)</span></label>
                        <select id="client_id" name="client_id" class="portal-field">
                            <option value="">Unassigned</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" @selected((int) old('client_id') === $client->id)>
                                    {{ $client->name }} — {{ $client->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="mb-2 block text-[14px] text-portal-ink">Description</label>
                        <textarea id="description" name="description" rows="3" maxlength="5000" class="portal-field resize-y">{{ old('description') }}</textarea>
                    </div>
                </div>

                <x-admin.stage-rows/>

                <button type="submit"
                        class="mt-7 flex w-full items-center justify-center gap-2 rounded-[10px] bg-portal px-6 py-4 text-[15px] font-semibold text-white transition-colors hover:bg-portal-dark">
                    <span class="text-[18px] leading-none">+</span> Add Project
                </button>
            </form>
        </section>

        <section class="min-w-0 rounded-2xl border border-portal-ink/10 bg-white p-[clamp(1.25rem,2.2vw,28px)]">
            <h2 class="font-wordmark text-[17px] tracking-[0.08em] text-portal-ink">RECENT ACTIVITY</h2>
            <ul class="mt-5 grid gap-5">
                @forelse ($activity as $entry)
                    <li class="flex gap-3">
                        <span class="mt-0.5 shrink-0 text-portal"><x-icon :name="$entry['icon']" size="19"/></span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-[14px] leading-snug text-portal-ink">{{ $entry['text'] }}</span>
                            <span class="mt-0.5 block text-[12px] text-ink-muted">{{ $entry['at']->diffForHumans() }}</span>
                        </span>
                    </li>
                @empty
                    <li class="text-[14px] text-ink-muted">Nothing yet.</li>
                @endforelse
            </ul>
        </section>
    </div>

    <section class="mt-6 rounded-2xl border border-portal-ink/10 bg-white p-[clamp(1.25rem,2.2vw,32px)]">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-wordmark text-[17px] tracking-[0.08em] text-portal-ink">PROJECT OVERVIEW</h2>
            <a href="{{ route('admin.dashboard') }}"
               class="rounded-xl border border-portal-ink/12 px-4 py-2.5 text-[14px] font-semibold text-portal-ink hover:border-portal hover:text-portal">
                View All Projects
            </a>
        </div>

        <ul class="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($projects->take(4) as $project)
                @php($badge = $project->statusBadge())
                <li class="overflow-hidden rounded-xl border border-portal-ink/12">
                    <span class="relative block aspect-[16/10] bg-alabaster">
                        @if ($cover = $project->images->first())
                            <img src="{{ route('portal.files.show', ['path' => $cover->storage_path]) }}" alt=""
                                 loading="lazy" class="absolute inset-0 h-full w-full object-cover">
                        @endif
                        <span class="absolute top-3 left-3 rounded-md px-2.5 py-1 text-[12px] font-semibold {{ $badge['classes'] }}">
                            {{ $badge['label'] }}
                        </span>
                    </span>

                    <span class="block p-4">
                        <span class="block font-wordmark text-[17px] text-portal-ink">{{ $project->title }}</span>
                        <span class="mt-1 flex items-center gap-1.5 text-[13px] text-ink-muted">
                            <x-icon name="map-pin" size="14"/>
                            <span class="truncate">{{ $project->location ?: '—' }}</span>
                        </span>

                        <span class="mt-4 flex items-center justify-between text-[13px]">
                            <span class="text-ink-muted">Progress</span>
                            <span class="font-semibold text-portal-ink">{{ $project->progress }}%</span>
                        </span>
                        <span role="progressbar" aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100"
                              aria-label="{{ $project->title }} progress"
                              class="mt-1.5 block h-1.5 w-full overflow-hidden rounded-full bg-portal-ink/10">
                            <span class="block h-full rounded-full bg-portal" style="width: {{ $project->progress }}%"></span>
                        </span>

                        <span class="mt-4 flex items-center justify-between border-t border-portal-ink/10 pt-3 text-[13px]">
                            <span class="flex items-center gap-1.5 text-ink-muted"><x-icon name="calendar" size="14"/> Due Date</span>
                            <span class="text-portal-ink">{{ $project->due_date?->format('M j, Y') ?? '—' }}</span>
                        </span>
                    </span>
                </li>
            @endforeach
        </ul>
    </section>
@endsection
