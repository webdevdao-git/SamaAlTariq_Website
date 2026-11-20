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
                'src' => \App\Support\Asset::versioned(file_exists(public_path("images/projects/$slug/hero.webp")) ? "images/projects/$slug/hero.webp" : "images/projects/covers/$slug.webp"),
                'href' => route('projects.show', $slug),
            ];
        })
        ->values();

    // The featured few, in their listed order, each on its chosen photograph.
    $featured = $projects['featured'] ?? [];
    $stage = collect($featured)
        ->map(function ($image, $slug) use ($all) {
            $item = $all->first(fn ($item) => str_ends_with($item['href'], '/' . $slug));

            return $item ? ['src' => \App\Support\Asset::versioned($image)] + $item : null;
        })
        ->filter()
        ->values();
    if ($stage->isEmpty()) {
        $stage = $all;
    }
@endphp

{{--
    Featured Projects as a pyramid stack (after 108.supply's "Pyramid
    Stack"): the featured projects as tall cards, the one in front largest,
    and the scroll — pinned — walks them forward one at a time
    (motion/project-stack.js, scroll mode).
--}}
<section id="projects" class="featured-stack bg-mist text-ink">
    <div class="shell flex flex-col gap-6 pt-[clamp(2.5rem,5vw,80px)] md:flex-row md:items-end md:justify-between">
        <div>
            <p class="featured-stack__eyebrow reveal">{{ $projects['eyebrow'] }}</p>
            <h2 class="featured-stack__title display">
            @foreach ($projects['heading'] as $i => $line)
                <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
            @endforeach
            </h2>
        </div>
        <a href="{{ $projects['cta']['href'] }}" class="pill reveal self-start md:self-auto">
            {{ $projects['cta']['label'] }}
            <x-icon name="arrow-pill"/>
        </a>
    </div>

    @include('sections.projects-page.stack', ['items' => $stage, 'scroll' => true])
</section>
