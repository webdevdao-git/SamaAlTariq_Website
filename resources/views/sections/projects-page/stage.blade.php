@php
    $stage = collect(config('site.projects_page.groups'))
        ->flatMap(fn ($group) => collect($group['rows'])
            ->flatMap(fn ($row) => collect($row['columns'])->flatMap(fn ($column) => $column['tiles'])))
        ->unique(fn ($item) => $item['project'] ?? $item['image'])
        ->map(function ($item) {
            $slug = $item['project'] ?? $item['image'];

            return [
                'title' => $item['title'],
                'category' => $item['category'],
                'location' => $item['location'] ?? null,
                'cover' => \App\Support\Asset::versioned("images/projects/covers/$slug.webp"),
                'href' => route('projects.show', $slug),
            ];
        })
        ->values();
@endphp

{{--
    The projects as lit 3D panels (motion/project-stage.js, Three.js), the
    scroll walking them past. The full list follows below, so the stage
    carries no closing "explore" panel of its own.
--}}
<section id="projects" class="bg-mist">
    <div data-stage data-items='@json($stage)' class="project-stage" hidden>
        <canvas data-stage-canvas class="project-stage__canvas" aria-hidden="true"></canvas>
        <div class="project-stage__caption shell">
            <div class="project-stage__info">
                <p class="project-stage__meta">
                    <span data-stage-category>{{ $stage[0]['category'] ?? '' }}</span>
                    <span data-stage-location class="project-stage__location">{{ $stage[0]['location'] ?? '' }}</span>
                </p>
                <a data-stage-link href="{{ $stage[0]['href'] ?? '#' }}" class="project-stage__title display">
                    <span data-stage-title>{{ $stage[0]['title'] ?? '' }}</span>
                    <x-icon name="arrow-right" class="project-stage__arrow"/>
                </a>
            </div>
            <p class="project-stage__count" aria-hidden="true">
                <span data-stage-index>01</span><span class="opacity-45"> / {{ str_pad($stage->count(), 2, '0', STR_PAD_LEFT) }}</span>
            </p>
        </div>
    </div>
</section>
