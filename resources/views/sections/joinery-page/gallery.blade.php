@php($section = config('site.joinery_page.gallery'))
@php($cta = config('site.joinery_page.cta'))

{{--
    Three projects and the way out of the page.

    `$gallery` is passed by PageController@joinery and is the projects grid's
    own tiles, resolved from the slugs in the config — so each tile shows the
    cover and the title the gallery shows, and is a way into that project
    rather than a photograph that stops where it is.

    Each cover sits in a third of the page rather than across it, which is the
    same reason the hero holds its picture to 500: at a third of 1728 the
    covers are still ahead of the box they fill.
--}}
<section class="bg-white py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">

        <div class="reveal flex flex-col gap-[clamp(0.75rem,1.16vw,20px)]">
            <p class="text-fluid-label font-medium text-teal">{{ $section['label'] }}</p>
            <h2 class="display max-w-[18em] text-fluid-h2 leading-[1.3] text-ink">{{ $section['heading'] }}</h2>
        </div>

        <div class="mt-[clamp(2rem,3.7vw,64px)] grid gap-[clamp(1rem,1.85vw,32px)] md:grid-cols-3">
            @foreach ($gallery as $project)
                <a href="{{ route('projects.show', $project['slug']) }}"
                   class="reveal group flex flex-col gap-[clamp(0.75rem,1.16vw,20px)]"
                   style="transition-delay:{{ $loop->index * 110 }}ms">
                    <figure class="relative aspect-[4/5] w-full overflow-hidden bg-mist">
                        <img src="{{ \App\Support\Asset::versioned('images/projects/covers/'.$project['slug'].'.webp') }}"
                             alt="{{ $project['title'] }}"
                             loading="lazy" decoding="async"
                             class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]">
                    </figure>

                    <div class="flex flex-col gap-1">
                        <h3 class="display text-[clamp(1.25rem,1.62vw,28px)] leading-[1.2] text-ink transition-colors group-hover:text-teal">
                            {{ $project['title'] }}
                        </h3>
                        <p class="text-fluid-sm font-medium text-ink-muted">{{ $project['category'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Centred under the row, the same pill the projects, services and
             process bands close with. --}}
        <div class="reveal mt-[clamp(2.5rem,4.63vw,80px)] flex flex-wrap justify-center gap-[clamp(0.75rem,1.16vw,20px)]">
            @foreach ($cta as $link)
                <a href="{{ $link['href'] }}" class="pill group">
                    {{ $link['label'] }}
                    <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                </a>
            @endforeach
        </div>
    </div>
</section>
