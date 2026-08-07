@php($projects = config('site.projects'))
@php($items = $projects['items'])

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
        <h2 class="display text-fluid-section uppercase text-ink">
            @foreach ($projects['heading'] as $i => $line)
                <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
            @endforeach
        </h2>
    </div>

    <div class="shell-flush mt-[clamp(2rem,3.4vw,58px)]">
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

    <div class="reveal mt-[clamp(2.5rem,4.5vw,78px)] flex justify-center">
        <a href="{{ $projects['cta']['href'] }}" class="pill group">
            {{ $projects['cta']['label'] }}
            <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
        </a>
    </div>
</section>
