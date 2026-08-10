@props(['placeholder' => 'Search…', 'action'])

@php
    $q    = request('q');
    $sort = request('sort', 'newest');
    $from = request('from');
    $to   = request('to');
    $dateActive = filled($from) || filled($to);
@endphp

{{--
    One GET form, so search, sort and the date range submit together and every
    combination survives in the URL — shareable, bookmarkable, and working with
    JavaScript disabled. The selects auto-submit on change; the text field
    submits on Enter.

    `project` is carried through as a hidden field: without it, filtering would
    silently bounce the admin back to their first project.
--}}
<form method="GET" action="{{ $action }}"
      class="mb-6 rounded-2xl border border-portal-ink/10 bg-white p-[clamp(0.75rem,1.2vw,18px)]">

    @if (request('project'))
        <input type="hidden" name="project" value="{{ request('project') }}">
    @endif

    <div class="flex flex-wrap items-center gap-3">
        <div class="relative min-w-[220px] flex-1">
            <label for="filter-q" class="sr-only">{{ $placeholder }}</label>
            <span aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-4 grid place-items-center text-ink-muted">
                <x-icon name="search"/>
            </span>
            <input id="filter-q" name="q" type="search" value="{{ $q }}" placeholder="{{ $placeholder }}"
                   class="portal-field pl-12">
        </div>

        <div>
            <label for="filter-sort" class="sr-only">Sort by</label>
            <select id="filter-sort" name="sort" data-auto-submit class="portal-field !w-auto pr-9">
                <option value="newest" @selected($sort === 'newest')>Sort by: Newest First</option>
                <option value="oldest" @selected($sort === 'oldest')>Sort by: Oldest First</option>
                <option value="name"   @selected($sort === 'name')>Sort by: Name A–Z</option>
            </select>
        </div>

        {{-- <details> rather than a scripted popover: it opens natively, keeps
             keyboard behaviour, and still works if the bundle never loads. --}}
        <details class="relative" @if ($dateActive) open @endif>
            <summary class="flex cursor-pointer list-none items-center gap-2.5 rounded-[10px] border px-4 py-[11px] text-[14px] transition-colors
                            {{ $dateActive ? 'border-portal text-portal' : 'border-portal-ink/14 text-portal-ink hover:border-portal' }}">
                <x-icon name="calendar" size="18"/>
                Filter by Date
                @if ($dateActive)
                    <span class="ml-1 rounded-full bg-portal px-1.5 text-[11px] font-bold text-white">1</span>
                @endif
            </summary>

            <div class="absolute right-0 z-20 mt-2 w-[280px] rounded-xl border border-portal-ink/12 bg-white p-4 shadow-lg">
                <div class="grid gap-3">
                    <div>
                        <label for="filter-from" class="mb-1.5 block text-[12px] font-semibold text-portal-ink">From</label>
                        <input id="filter-from" name="from" type="date" value="{{ $from }}" class="portal-field">
                    </div>
                    <div>
                        <label for="filter-to" class="mb-1.5 block text-[12px] font-semibold text-portal-ink">To</label>
                        <input id="filter-to" name="to" type="date" value="{{ $to }}" class="portal-field">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 rounded-lg bg-portal px-4 py-2.5 text-[13px] font-semibold text-white hover:bg-portal-dark">
                            Apply
                        </button>
                        @if ($dateActive)
                            <a href="{{ $action }}{{ request('project') ? '?project='.request('project') : '' }}"
                               class="rounded-lg border border-portal-ink/14 px-4 py-2.5 text-[13px] font-semibold text-portal-ink hover:border-portal">
                                Clear
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </details>

        <noscript>
            <button type="submit" class="rounded-[10px] bg-portal px-5 py-[11px] text-[13px] font-semibold text-white">Search</button>
        </noscript>
    </div>
</form>
