@php($band = config('site.joinery_page.context'))

{{--
    Figma 1803:2, y6258–7500. The slab on the left with its copy opposite,
    a rule under both, then two 772x727 tiles with the title and category on
    one line beneath each, and the pill centred under them.

    The photographs are the frame's own rather than the projects grid's covers
    — it cuts these two differently there — but each tile opens that project's
    page, so nothing here is a picture that leads nowhere.
--}}
<section class="bg-white py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">

        <div class="grid gap-[clamp(1.5rem,3vw,52px)] lg:grid-cols-2 lg:items-start lg:gap-[clamp(2rem,3.7vw,64px)]">
            <h2 class="reveal editorial-heading text-fluid-section uppercase text-ink">
                @foreach ($band['heading'] as $line)
                    <span class="block">{{ $line }}</span>
                @endforeach
            </h2>

            {{-- Opposite the slab and set to its foot, which is where the frame
                 has it: level with the second line rather than the first. --}}
            <p class="reveal max-w-[46ch] text-fluid-body font-medium leading-[1.5] text-ink-muted lg:mt-auto lg:pb-[0.5em]"
               style="transition-delay:100ms">{{ $band['body'] }}</p>
        </div>

        <span aria-hidden="true" class="reveal-line mt-[clamp(1.5rem,2.8vw,48px)] block h-px w-full bg-black/15"></span>

        <div class="mt-[clamp(1.25rem,2.31vw,40px)] grid gap-[clamp(1rem,1.85vw,32px)] md:grid-cols-2">
            @foreach ($band['tiles'] as $tile)
                <a href="{{ route('projects.show', $tile['project']) }}"
                   class="reveal group flex flex-col gap-[clamp(0.75rem,1.16vw,20px)]"
                   style="transition-delay:{{ $loop->index * 110 }}ms">
                    <figure class="relative aspect-[772/727] w-full overflow-hidden bg-mist">
                        <img src="{{ \App\Support\Asset::versioned($tile['image']) }}" alt="{{ $tile['title'] }}"
                             loading="lazy" decoding="async"
                             class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]">
                    </figure>

                    {{-- Title left, category right, on one line — the same
                         caption the projects grid gives its tiles. --}}
                    <div class="flex items-baseline justify-between gap-4">
                        <h3 class="text-fluid-sm font-medium text-ink transition-colors group-hover:text-teal">{{ $tile['title'] }}</h3>
                        <p class="shrink-0 text-fluid-sm font-medium text-ink-muted">{{ $tile['category'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="reveal mt-[clamp(2rem,3.24vw,56px)] flex justify-center">
            <a href="{{ $band['cta']['href'] }}" class="pill group">
                {{ $band['cta']['label'] }}
                <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
            </a>
        </div>
    </div>
</section>
