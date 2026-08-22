@php($band = config('site.joinery_page.capabilities'))

{{--
    Figma 1803:2, y3174–4216. The slab in two lines, then three cards on the
    left against one tall picture on the right.

    Each card is copy at the top and the title and its number on one line at
    the foot — the number in teal at 40 against a 28 title, set hard right of
    the row. The cards sit on the mist ground with white between them, which is
    what makes them read as three rather than as a list with rules.

    The tracks are the frame's: the cards run 23333–24098 (765) and the picture
    24130–24903 (773), which is all but even.
--}}
<section id="capabilities" class="bg-white pb-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">

        <h2 class="reveal editorial-heading text-fluid-section uppercase text-ink">
            @foreach ($band['heading'] as $line)
                <span class="block">{{ $line }}</span>
            @endforeach
        </h2>

        <div class="mt-[clamp(2rem,3.7vw,64px)] grid gap-[clamp(1rem,1.85vw,32px)] lg:grid-cols-[765fr_773fr] lg:items-start">

            {{-- The three cards divide the picture's height between them, so
                 the column ends level with it rather than short. --}}
            {{-- Sticky from lg, and `self-start` is what lets it be: a grid
                 item stretches to its row by default, and a full-height item
                 has nothing to stick within. The offset clears the header. --}}
            <div class="flex flex-col gap-[clamp(0.75rem,1.16vw,20px)] lg:sticky lg:top-[12vh] lg:self-start">
                @foreach ($band['items'] as $item)
                    <div class="reveal flex flex-col justify-between gap-[clamp(1.5rem,2.8vw,48px)] bg-mist p-[clamp(1rem,1.5vw,26px)]"
                         style="transition-delay:{{ $loop->index * 100 }}ms">
                        <p class="max-w-[62ch] text-fluid-sm font-medium leading-[1.45] text-ink-muted">{{ $item['body'] }}</p>

                        <div class="flex items-baseline justify-between gap-6">
                            <h3 class="text-[clamp(1.0625rem,1.62vw,28px)] font-medium text-ink">{{ $item['title'] }}</h3>
                            <span aria-hidden="true" class="shrink-0 text-[clamp(1.25rem,2.31vw,40px)] font-medium text-teal">{{ $item['number'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- A COLUMN OF THREE, which is the arrangement Halston's stats
                 section uses and what the client asked for here: the cards
                 hold while the photographs slide past them. No JavaScript —
                 the cards are sticky and this column is simply taller, so the
                 page's own scrolling does the work. Below lg nothing sticks
                 and the four blocks read in order. --}}
            <div class="flex flex-col gap-[clamp(1rem,1.85vw,32px)]">
                @foreach ($band['images'] as $image)
                    <figure class="reveal relative aspect-[773/740] w-full overflow-hidden bg-mist"
                            style="transition-delay:{{ $loop->index * 90 }}ms">
                        <img src="{{ \App\Support\Asset::versioned($image['src']) }}" alt="{{ $image['alt'] }}"
                             loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async"
                             class="absolute inset-0 h-full w-full object-cover">
                    </figure>
                @endforeach
            </div>
        </div>
    </div>
</section>
