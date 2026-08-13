@php($values = config('site.about_page.values'))

{{--
    Figma: frame 1377:102, 1728×980.

    The band the file's metadata returns empty — it only became readable once
    the page render came down, which is why it arrived after the rest of the
    page rather than with it.

    A full-bleed dark photograph carrying two things:

      · a label row at y≈205 — "Modern" on the left gutter, the sentence
        centred on the page, "Construction" ending on the right gutter. Three
        separate anchors, so it is a three-column grid rather than a flex row
        with space-between, which would only centre the middle item when the
        two labels happened to be the same width;

      · four rows starting at y 332, each 120 tall and 8 apart, inset 130px
        from both gutters rather than sitting on the 80px one. Each is a
        frosted panel — translucent white over the photo, not a solid — with a
        50px teal block carrying the number, then the title, then the copy.

    The panels are the reason the section carries its own dark backdrop rather
    than borrowing the night token: they only read as glass over a picture.
--}}
<section class="relative isolate overflow-hidden bg-night pt-[clamp(2rem,8.28vw,143px)] pb-[clamp(2.5rem,8.33vw,144px)]">
    {{-- The one picture on the page that drifts. It is a full-bleed backdrop
         with no designed crop to protect, so the 3% the drift needs for
         headroom cannot be read against anything — see initMediaDrift. It is
         also deliberately not a .reveal-media: fading a backdrop in would flash
         the section's own dark ground before the photograph arrived. --}}
    <img data-drift src="{{ asset($values['image']) }}" alt="{{ $values['alt'] }}"
         loading="lazy" decoding="async"
         class="absolute inset-0 -z-10 h-full w-full object-cover">
    {{-- The photo is bright enough in places to lose white text, so it is sat
         under a flat scrim rather than trusted as-is. --}}
    <div aria-hidden="true" class="absolute inset-0 -z-10 bg-black/55"></div>

    {{-- 1fr_auto_1fr, not three equal thirds: the sentence is wider than a
         third of the page and would wrap, and the design runs it across the
         side labels' tracks rather than between them. Same construction as the
         site header, for the same reason. --}}
    {{-- The rule the band opens on: frame y 143, running the shell's own
         width (x 80 to 1647) rather than full-bleed. It is the first thing in
         the section, and the label row hangs 46px under it — 46 and not 47
         because the rule is itself a pixel tall, and the band has to close on
         980 or every section below it is shunted down by one. --}}
    <div class="shell">
        <span aria-hidden="true" class="reveal block h-px w-full bg-white/25"></span>

        <div class="reveal mt-[clamp(1.25rem,2.66vw,46px)] grid gap-2 text-white sm:grid-cols-[1fr_auto_1fr] sm:items-center sm:gap-6"
             style="transition-delay:120ms">
            <p class="text-fluid-body">{{ $values['label_left'] }}</p>
            <h2 data-split data-split-by="word"
                class="text-fluid-label font-medium sm:text-center">{{ $values['heading'] }}</h2>
            <p class="text-fluid-body sm:justify-self-end">{{ $values['label_right'] }}</p>
        </div>
    </div>

    {{-- 130px inset, expressed as the gutter plus the extra 50 the design adds
         on top of it, so the band still clears the screen edge on a phone. --}}
    <ol class="mx-auto mt-[clamp(2rem,5.79vw,100px)] flex w-full max-w-[1728px] flex-col gap-2 px-[clamp(1rem,7.52vw,130px)]">
        @foreach ($values['items'] as $i => $item)
            {{-- Frosted at rest, and the glass thickens under the pointer.
                 Measured off the frame rather than guessed: sampling the four
                 rows against the photograph behind them gives a white overlay
                 at 0.21 on the three at rest and 0.56 on the one the file draws
                 hovered, so this is the same white carried further, not a tint
                 of another colour. Colour only — the row must not move, or the
                 four would jostle each other as the pointer runs down. --}}
            <li class="reveal group flex min-h-[120px] items-stretch overflow-hidden bg-white/12 backdrop-blur-md hover:bg-white/55"
                style="transition-delay:{{ $i * 100 }}ms">
                <span aria-hidden="true"
                      class="grid w-[50px] shrink-0 place-items-center bg-teal/90 text-fluid-sm font-medium text-white">
                    {{ $item['number'] }}
                </span>

                <div class="grid flex-1 items-center gap-x-[clamp(1rem,2vw,34px)] gap-y-2 px-[clamp(1rem,1.16vw,20px)] py-[clamp(1rem,1.39vw,24px)] text-white md:grid-cols-[300fr_768fr]">
                    <h3 class="text-fluid-body font-semibold uppercase tracking-[0.02em]">{{ $item['title'] }}</h3>
                    <p class="text-fluid-sm leading-[1.4] text-white/85">{{ $item['body'] }}</p>
                </div>
            </li>
        @endforeach
    </ol>
</section>
