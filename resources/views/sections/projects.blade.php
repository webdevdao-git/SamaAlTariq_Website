@php
    $projects = config('site.projects');
    $items = $projects['items'];

    /*
     * Every project, once, for the 3D stage (motion/project-stage.js): the
     * same flatten the projects page's list uses, so the two never disagree
     * about what has been built. Each carries its own cover and page.
     */
    $all = collect(config('site.projects_page.groups'))
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

    // The stage shows the featured few, in their listed order; the rest are
    // counted for its closing panel.
    $featured = $projects['featured'] ?? [];
    $stage = collect($featured)
        ->map(fn ($slug) => $all->first(fn ($item) => str_ends_with($item['href'], '/' . $slug)))
        ->filter()
        ->values();
    if ($stage->isEmpty()) {
        $stage = $all;
    }
    $more = $all->count() - $stage->count();
@endphp

{{--
    Figma: frame 1219:209, 1728×2078.
    Two-line display heading on the 80px gutter, then an asymmetric grid that
    runs to a 24px gutter: a tall hero card beside two stacked cards, and a
    second row of two equal cards.

    No .reveal on the grids — it animates the same transform the per-card
    parallax writes to. The cards carry their own motion.
--}}
<section id="projects" class="bg-white py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">
        <h2 class="editorial-heading text-fluid-section uppercase text-ink">
            @foreach ($projects['heading'] as $i => $line)
                <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
            @endforeach
        </h2>
    </div>

    <div class="shell mt-[clamp(2rem,3.4vw,58px)]">
        <div class="grid gap-6 lg:grid-cols-[992fr_660fr]">
            <x-project-card :project="$items[0]"/>
            <div class="grid gap-6 lg:grid-rows-2">
                <x-project-card :project="$items[1]"/>
                <x-project-card :project="$items[2]"/>
            </div>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <x-project-card :project="$items[3]"/>
            <x-project-card :project="$items[4]"/>
        </div>
    </div>

    {{--
        The 3D stage. Inert markup until project-stage.js finds WebGL and adds
        .has-stage to the section, which hides the grid above and shows this.
        Without it — no WebGL, reduced motion, no script — the grid is the
        section, exactly as drawn.

        The caption and the name strip are real text the script only moves
        and re-points; every project in it is a link a keyboard can reach.
    --}}
    <div data-stage data-items='@json($stage)'
         data-more="{{ $more }}" data-more-label="{{ $projects['cta']['label'] }}" data-more-href="{{ $projects['cta']['href'] }}"
         class="project-stage" hidden>
        <canvas data-stage-canvas class="project-stage__canvas" aria-hidden="true"></canvas>

        {{-- The way out to every project: the section's own button, drawn as
             a turning seal — the label run round a circle about an arrow. --}}
        <a href="{{ $projects['cta']['href'] }}" class="project-stage__seal" data-magnetic
           aria-label="{{ $projects['cta']['label'] }}">
            <svg viewBox="0 0 120 120" aria-hidden="true" class="project-stage__seal-ring">
                <defs>
                    <path id="seal-path" d="M60,60 m-46,0 a46,46 0 1,1 92,0 a46,46 0 1,1 -92,0"/>
                </defs>
                <text><textPath href="#seal-path" textLength="289">{{ Str::upper($projects['cta']['label']) }} • {{ Str::upper($projects['cta']['label']) }} •</textPath></text>
            </svg>
            <x-icon name="arrow-right" class="project-stage__seal-arrow"/>
        </a>

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

    <div class="reveal mt-[clamp(2.5rem,4.5vw,78px)] flex justify-center">
        <a href="{{ $projects['cta']['href'] }}" class="pill group">
            {{ $projects['cta']['label'] }}
            <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
        </a>
    </div>
</section>
