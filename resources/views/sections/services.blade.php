@php($services = config('site.services'))

{{--
    Our Expertise.

    Figma stacks two flattened 1728×980 panels that differ only in which tab
    pill is active — two states of one component, which is what this is: one
    panel-sized box with the three services stacked in it and the tab row
    switching between them. It was three full-viewport panels scrolled through
    instead, which made the section three screens tall and turned a choice into
    a journey.

    STILL NO JAVASCRIPT. The tabs are anchor links and :target does the
    switching: the panel whose id is in the URL is the one shown, and
    :not(:has(:target)) covers the state before anything has been clicked by
    showing the first. Both are in app.css beside the crossfade they share.
--}}
<section id="services" class="bg-white pt-[clamp(3rem,4.63vw,80px)]">
    <div class="shell">
        <div class="reveal flex flex-col gap-[clamp(1rem,3vw,52px)] pb-[clamp(2.5rem,5.79vw,100px)] lg:flex-row lg:items-start lg:justify-between">
            <p class="shrink-0 text-fluid-label font-medium text-teal">{{ $services['label'] }}</p>
            <h2 class="display max-w-[922px] text-fluid-h2 leading-[1.3] text-ink lg:w-[59%]">{{ $services['heading'] }}</h2>
        </div>
    </div>

    {{-- The box the three share. A one-cell grid with all three in that cell
         rather than absolute positioning: stacked this way they still take
         part in the layout, so the box is as tall as the tallest of them and a
         long panel on a narrow phone cannot be cut off at the fold. --}}
    <div class="service-slides relative isolate grid min-h-[100svh] w-full overflow-hidden">
    @foreach ($services['items'] as $i => $item)
        @php($number = str_pad($i + 1, 2, '0', STR_PAD_LEFT))

        <article id="service-{{ $i + 1 }}"
                 class="service-slide [grid-area:1/1] flex w-full flex-col overflow-hidden
                        px-[var(--spacing-gutter)] pt-[clamp(1.5rem,3.47vw,60px)] pb-[clamp(2rem,5vw,88px)]"
                 aria-labelledby="service-heading-{{ $i + 1 }}">

            <img src="{{ asset($item['image']) }}" alt=""
                 loading="{{ $i === 0 ? 'eager' : 'lazy' }}" decoding="async"
                 class="absolute inset-0 -z-20 h-full w-full object-cover">

            {{--
                Two gradients, because the type sits in two places. The 90deg
                one carries the weight: it shades the left column the headline
                occupies and lets the photograph open up to the right, which is
                what keeps the panel from reading as a flat scrim. The 180deg
                one is a light top wash for the tab row, which runs the full
                width and would otherwise cross whatever the image happens to
                be bright in — the ceilings in two of these three.
            --}}
            <div aria-hidden="true" class="absolute inset-0 -z-10"
                 style="background:
                        linear-gradient(180deg,rgba(0,0,0,0.42) 0%,rgba(0,0,0,0.10) 22%,rgba(0,0,0,0) 40%),
                        linear-gradient(90deg,rgba(0,0,0,0.52) 0%,rgba(0,0,0,0.26) 45%,rgba(0,0,0,0.08) 100%)"></div>

            {{--
                The row repeats in every panel as a visual device, but there is
                only one navigation here. The first copy is the real one; the
                rest are hidden from assistive tech and taken out of the tab
                order, so a screen reader or keyboard user gets one clean list
                instead of the same six links six times over.
            --}}
            {{--
                Every item carries the pill's padding, active or not, so moving
                between panels cannot shift the row sideways. The gap is small
                because that padding already supplies most of the space between
                labels: gap + 2×padding is what the eye reads as the spacing,
                and it comes to roughly 60px at desktop.
            --}}
            <nav class="-mx-[var(--spacing-gutter)] flex snap-x items-center gap-[clamp(0.25rem,0.41vw,7px)] overflow-x-auto px-[var(--spacing-gutter)] pb-1
                        [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
                 aria-label="Our areas of expertise">
                @foreach ($services['items'] as $j => $tab)
                    <a href="#service-{{ $j + 1 }}"
                       @if ($i === $j) aria-current="true" @endif
                       class="service-tab shrink-0 snap-start rounded-full border px-[clamp(1rem,1.56vw,27px)] py-[clamp(0.5rem,0.75vw,13px)]
                              text-[clamp(0.875rem,1.04vw,18px)] whitespace-nowrap transition duration-300
                              {{ $i === $j ? 'is-current' : '' }}">
                        {{ $tab['tab'] }}
                    </a>
                @endforeach
            </nav>

            {{--
                Top-left, not bottom: the headline sits just under the tab row
                with a controlled gap, and the rest of the panel is left as open
                photograph.

                No max-width on the headline. Its two lines are already split in
                the config and each is short enough to hold at desktop; a `ch`
                cap sized for the paragraph broke them into four. Below about
                1100px a line wraps on its own, which is the intended behaviour
                rather than something to prevent.
            --}}
            <div class="mt-[clamp(2rem,3.7vw,64px)] flex flex-col gap-[clamp(0.75rem,1.4vw,24px)]">
                <h3 id="service-heading-{{ $i + 1 }}"
                    class="service-title text-[clamp(2.25rem,2.9vw,50px)] uppercase text-white">
                    <span class="block">
                        {{--
                            Sans, and sized against the headline in `em` so it
                            tracks it — an editorial superscript on the line,
                            not a badge beside it.
                        --}}
                        {{ $item['title'][0] }}<sup class="ml-[0.16em] font-sans text-[0.46em] font-medium tracking-[0.01em] [vertical-align:0.5em]">({{ $number }})</sup>
                    </span>
                    <span class="block">{{ $item['title'][1] }}</span>
                </h3>

                <p class="max-w-[46ch] text-fluid-body font-medium text-white/80">{{ $item['description'] }}</p>
            </div>
        </article>
    @endforeach
    </div>

    <div class="flex justify-center py-[clamp(2.5rem,4.63vw,80px)]">
        <a href="{{ $services['cta']['href'] }}" class="pill group">
            {{ $services['cta']['label'] }}
            <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
        </a>
    </div>
</section>
