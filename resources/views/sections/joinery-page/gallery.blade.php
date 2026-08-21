@php($section = config('site.joinery_page.gallery'))
@php($cta = config('site.joinery_page.cta'))

{{--
    Two large pictures under a centred serif slab, which is how the reference
    closes: a heading across the middle and a pair of equal photographs beneath
    it, wide rather than upright, so the band reads differently from the row of
    three above.

    `$gallery` is passed by PageController@joinery and is the projects grid's
    own tiles, resolved from the slugs in the config — so each carries the
    cover and the title the gallery carries, and is a way into that project
    rather than a photograph that stops where it is.

    The pills after it are this site's, not the reference's: every other band
    on this site closes with them, and a page that ends on two pictures and
    nothing else is a dead end.
--}}
<section class="bg-white py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">

        <h2 class="reveal editorial-heading text-center text-fluid-h2 uppercase text-ink">{{ $section['heading'] }}</h2>

        <div class="mt-[clamp(2rem,3.7vw,64px)] grid gap-[clamp(1rem,1.85vw,32px)] md:grid-cols-2">
            @foreach ($gallery as $project)
                <a href="{{ route('projects.show', $project['slug']) }}"
                   class="reveal group flex flex-col gap-[clamp(0.75rem,1.16vw,20px)]"
                   style="transition-delay:{{ $loop->index * 110 }}ms">
                    <figure class="relative aspect-[4/3] w-full overflow-hidden bg-mist">
                        <img src="{{ \App\Support\Asset::versioned('images/projects/covers/'.$project['slug'].'.webp') }}"
                             alt="{{ $project['title'] }}"
                             loading="lazy" decoding="async"
                             class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]">
                    </figure>

                    <div class="flex flex-col gap-1 text-center">
                        <h3 class="display text-[clamp(1.25rem,1.62vw,28px)] leading-[1.2] text-ink transition-colors group-hover:text-teal">
                            {{ $project['title'] }}
                        </h3>
                        <p class="text-fluid-sm font-medium text-ink-muted">{{ $project['category'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>

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
