@php
    /*
     * One floor per project, not per tile: the same flatten the list view
     * uses, keyed on `project` so a job drawn twice in the grid is one floor.
     */
    $floors = collect(config('site.projects_page.groups'))
        ->flatMap(fn ($group) => collect($group['rows'])
            ->flatMap(fn ($row) => collect($row['columns'])->flatMap(fn ($column) => $column['tiles'])))
        ->unique(fn ($item) => $item['project'] ?? $item['image'])
        ->values();
@endphp

{{--
    The tower: every project a floor slab of one building, seen from above at
    an architect's axonometric (motion/project-tower.js).

    As the section arrives the slabs drop onto one another in order, bottom
    floor first, until the building stands. Pinned, the scroll then walks up
    it: each floor slides out of the stack, swings upright to face the viewer
    at full size, and settles back into its place as the next one comes out.

    Nothing here is new copy — the covers, titles and categories are the
    grid's own entries below, and each slab opens the same project page.

    Without the script, or under reduced motion, the section is not pinned
    and the tower simply stands assembled at rest — every slab a working link.
--}}
<section data-tower aria-label="Projects" class="project-tower relative bg-night text-white"
         style="--count:{{ $floors->count() }}">
    <div class="project-tower__stage">
        <div data-tower-body class="project-tower__body">
            @foreach ($floors as $i => $item)
                <div class="project-tower__floor" style="--i:{{ $i }}"
                     data-tower-floor data-title="{{ $item['title'] }}" data-category="{{ $item['category'] }}">
                    <a href="{{ route('projects.show', $item['project'] ?? $item['image']) }}"
                       aria-label="{{ $item['title'] }}"
                       class="project-tower__slab focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white">
                        <img src="{{ \App\Support\Asset::versioned('images/projects/covers/' . $item['image'] . '.webp') }}"
                             alt="" loading="lazy" decoding="async"
                             class="h-full w-full object-cover">
                    </a>
                </div>
            @endforeach
        </div>

        {{-- The floor out of the stack, named. aria-hidden: each slab already
             carries its title as its link's name. --}}
        <div aria-hidden="true" class="project-tower__caption shell">
            <div class="flex items-end justify-between gap-6 border-t border-white/20 pt-[clamp(1rem,1.39vw,24px)]">
                <p class="text-fluid-body font-semibold">
                    <span data-tower-title>{{ $floors->first()['title'] ?? '' }}</span>
                    <span data-tower-category class="ml-3 text-white/55">{{ $floors->first()['category'] ?? '' }}</span>
                </p>
                <p class="shrink-0 text-fluid-body font-semibold tabular-nums">
                    <span data-tower-index>01</span><span class="text-white/45"> / {{ str_pad($floors->count(), 2, '0', STR_PAD_LEFT) }}</span>
                </p>
            </div>
        </div>
    </div>
</section>
