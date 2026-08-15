@php($steps = config('site.process_page.steps'))

{{--
    Figma 1508:35, 1728x1244 on #F9F9F9, 100 above and 40 below.

    Four rows of 240, each a number, a title, a paragraph and a picture, with a
    hairline between them at 24 either side. The row's columns are the frame's
    own widths — 27 for the number, 320 for the title, 612 for the copy and 369
    for the picture, 80 apart — so they go in as fractions rather than as a
    guess at quarters.
--}}
<section class="bg-[#F9F9F9] pt-[clamp(3rem,5.79vw,100px)] pb-[clamp(1.5rem,2.315vw,40px)]">
    <div class="shell">
        @foreach ($steps as $i => $step)
            @if ($i > 0)
                {{-- The frame puts 24 either side of a line that has no height
                     of its own, so the gap between two rows is 48 and the rule
                     sits in the middle of it. Written as a 48 spacer with the
                     rule centred inside, because the two obvious ways both miss:
                     a 1px rule with 24 margins makes the gap 49, and margins on
                     a zero-height box collapse into one 24. --}}
                <div class="relative h-[clamp(2rem,2.778vw,48px)]" aria-hidden="true">
                    <span class="reveal-line absolute inset-x-0 top-1/2 block h-px -translate-y-1/2 bg-black/[0.24]"></span>
                </div>
            @endif

            <div class="reveal grid items-start gap-[clamp(1.5rem,4.63vw,80px)] lg:grid-cols-[27fr_320fr_612fr_369fr]">
                <p class="text-[clamp(1.125rem,1.389vw,24px)] font-bold leading-[1.375] text-teal">{{ $step['number'] }}</p>
                <p class="text-[clamp(1.125rem,1.389vw,24px)] font-semibold leading-[1.375] text-ink">{{ $step['title'] }}</p>
                <p class="text-[clamp(1.125rem,1.389vw,24px)] font-medium leading-[1.375] text-ink">{{ $step['body'] }}</p>

                {{-- 369x240 in the frame; the export is that box at 2x. --}}
                <div class="relative w-full overflow-hidden" style="aspect-ratio:369/240">
                    <img src="{{ \App\Support\Asset::versioned($step['image']) }}" alt="" loading="lazy" decoding="async"
                         class="h-full w-full object-cover">
                </div>
            </div>
        @endforeach
    </div>
</section>
