@php($services = config('site.services_page.services'))

{{--
    Figma 1588:1176 and the nine bands that repeat it: 1728x640 on #F9F9F9,
    each a number on the left gutter, a 600x640 picture, and a 602 column of
    title, lead and copy — 163, 283 and 963 across the frame, which is what the
    three tracks below say.

    The picture runs the full height of the band with no padding above or below
    it, so the bands stack into one continuous column of photographs with the
    text alternating beside them. Nothing separates them: the frame gives them
    no rule and no gap.

    The text block is centred against the picture rather than set to its top —
    the frame's own blocks sit at 178 and 201 down a 640 band, which is what
    centring gives for the two heights they come in.

    The tracks are the frame's own and they account for the whole width:
    163 of margin, the number's 40, 80, the picture's 600, 80, the text's 602
    and 163 of margin — 1728. Written out rather than as columns and a gap,
    because the number is not on the gutter and the text does not reach it.

    THE PICTURES MOVE ON TWO LAYERS, which is what the reference's listing
    actually does — the first pass at this caught only one of them.

    The frame that clips the photograph slides about 112 of its 1080 as the row
    comes up, and the photograph inside it travels about 300 more. The frame's
    own slide is why their pictures appear to pass one another at the seams:
    the clipper is out of flow, so while it is displaced it hangs over the row
    above or below. The photograph's slack is in the markup rather than bought
    with a scale — 140% of the frame, hung at -20% — so nothing is enlarged and
    the crop stays the frame's own.

    Both are the site's existing mechanisms: data-parallax translates an
    element by its own pixel figure across the viewport, data-drift measures
    the slack around a picture and travels within it.
--}}
@foreach ($services as $service)
    <section class="bg-[#F9F9F9]">
        <div class="grid items-stretch lg:grid-cols-[163fr_40fr_80fr_600fr_80fr_602fr_163fr]">

            <div aria-hidden="true" class="hidden lg:block"></div>

            {{-- 28/38 Manrope Medium in teal, level with the copy beside it. --}}
            <p class="reveal flex items-center justify-center pt-[clamp(1.5rem,2.31vw,40px)] text-[clamp(1.25rem,1.62vw,28px)] font-medium leading-[1.357] text-teal lg:justify-start lg:pt-0">
                {{ $service['number'] }}
            </p>

            <div aria-hidden="true" class="hidden lg:block"></div>

            {{-- The outer box holds the row's height; the clipper inside it is
                 out of flow, so its slide can overhang the neighbouring rows
                 without moving anything. --}}
            <div class="reveal-rise relative w-full" style="aspect-ratio:600/640">
                <div data-parallax="64" class="absolute inset-0 overflow-hidden">
                    <div data-drift class="absolute inset-x-0 -top-[20%] h-[140%]">
                        <img src="{{ \App\Support\Asset::versioned($service['image']) }}" alt="" loading="lazy" decoding="async"
                             class="h-full w-full object-cover">
                    </div>
                </div>
            </div>

            <div aria-hidden="true" class="hidden lg:block"></div>

            <div class="flex flex-col justify-center gap-[clamp(1rem,1.852vw,32px)] px-[clamp(1.25rem,4.63vw,80px)] py-[clamp(2rem,4.63vw,80px)] lg:px-0 lg:py-0">
                {{-- 48/45 Juana Alt: the leading is tighter than the type, so a
                     title that runs to two lines closes into a block. --}}
                {{-- Set upper in the frame, and it matters more than it looks:
                     at 48/45 the leading is tighter than the type, which only
                     resolves as a block when there are no descenders. --}}
                <h2 class="reveal font-display text-[clamp(1.5rem,2.78vw,48px)] font-medium uppercase leading-[0.9375] tracking-normal text-ink">
                    @foreach ($service['title'] as $line)
                        <span class="block">{{ $line }}</span>
                    @endforeach
                </h2>

                {{-- 140 of teal at 2px under the title, 32 either side of it.
                     The only rule on this page that is not a hairline, and the
                     only teal one — it is a mark rather than a division. --}}
                <span aria-hidden="true" class="reveal-line block h-[2px] w-[140px] shrink-0 bg-teal"></span>

                {{-- Lead and copy are one block, 16 apart, and the 32 belongs
                     between that block and the rule above it. --}}
                <div class="flex flex-col gap-[clamp(0.75rem,0.926vw,16px)]">
                    {{-- Teal, not ink: the lead is the line that carries the
                         service's promise and the frame colours it. --}}
                    <p class="reveal text-[clamp(1.125rem,1.389vw,24px)] font-bold leading-[1.375] text-teal" style="transition-delay:100ms">{{ $service['lead'] }}</p>

                    <p class="reveal text-[clamp(1rem,1.157vw,20px)] font-medium leading-[1.35] text-ink" style="transition-delay:180ms">{{ $service['body'] }}</p>
                </div>
            </div>

            <div aria-hidden="true" class="hidden lg:block"></div>
        </div>
    </section>
@endforeach
