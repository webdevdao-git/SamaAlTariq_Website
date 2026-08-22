@php($band = config('site.joinery_page.capabilities'))

{{--
    "INTERIOR CAPABILITIES" as a two-line slab, then three numbered rows on the
    left against a tall photograph on the right — the frame's own arrangement,
    with a rule under each row and the number set opposite its title.

    `$capabilities` is passed by PageController@joinery: the services page's own
    entries 05 and 06, quoted by number rather than described again, with the
    frame's third row appended from the config. Editing a service edits this.
--}}
<section class="bg-white pb-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">

        <h2 class="reveal editorial-heading text-fluid-section uppercase text-ink">
            @foreach ($band['heading'] as $line)
                <span class="block">{{ $line }}</span>
            @endforeach
        </h2>

        {{-- The list a little wider than the picture, as the frame sets them.
             Written 1fr against 496fr first, which is not a ratio but a
             starvation: the left column came out 3 characters wide and every
             title broke down the page. --}}
        <div class="mt-[clamp(2rem,3.7vw,64px)] grid gap-[clamp(2rem,3vw,52px)] lg:grid-cols-[560fr_496fr] lg:items-start lg:gap-[clamp(2rem,3.7vw,64px)]">

            <div class="flex flex-col">
                @foreach ($capabilities as $item)
                    {{-- Title and number on one line with the rule under them,
                         the note beneath: the frame sets the number hard right
                         of its row, in teal, at the title's own size. --}}
                    <div class="reveal border-t border-black/15 py-[clamp(1rem,1.62vw,28px)] first:border-t-0 first:pt-0"
                         style="transition-delay:{{ $loop->index * 100 }}ms">
                        <div class="flex items-baseline justify-between gap-6">
                            <h3 class="text-[clamp(1.0625rem,1.25vw,22px)] font-semibold text-ink">{{ $item['title'] }}</h3>
                            <span aria-hidden="true" class="shrink-0 text-[clamp(1rem,1.16vw,20px)] font-medium text-teal">{{ $item['number'] }}</span>
                        </div>

                        <p class="mt-[clamp(0.5rem,0.7vw,12px)] max-w-[52ch] text-fluid-sm font-medium text-ink-muted">{{ $item['body'] }}</p>
                    </div>
                @endforeach
            </div>

            <figure class="reveal relative aspect-[496/470] w-full overflow-hidden bg-mist" style="transition-delay:120ms">
                <img src="{{ \App\Support\Asset::versioned($band['image']['src']) }}" alt="{{ $band['image']['alt'] }}"
                     loading="lazy" decoding="async"
                     class="absolute inset-0 h-full w-full object-cover">
            </figure>
        </div>
    </div>
</section>
