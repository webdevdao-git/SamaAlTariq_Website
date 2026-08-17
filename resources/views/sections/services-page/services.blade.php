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

    THE FRAME DOES NOT MOVE. THE PHOTOGRAPH INSIDE IT DOES.

    Measured on halston's own services listing at 1440x900, walking a card the
    whole way up the screen: the box that clips the picture tracks the page at
    exactly -1.000 px per px — it never budges — while the picture inside runs
    +0.192, starting flush with the frame's foot and ending flush with its head.
    It uses its whole slack and no more, which is where their 140%-at--20% comes
    from: 40% of the frame is exactly what one pass through the viewport spends.

    An earlier pass at this read a translate on their inner wrapper and gave the
    clipper the same slide, on 64px of data-parallax. That was a real transform,
    but not a visible one: their wrapper is clipped by the frame around it, and
    ours had nothing clipping it, so where they move a picture within a fixed
    window we moved the window itself and the photograph hung over the bands
    above and below. The frame is fixed here now, as it is there.

    Slack in the markup rather than bought with a scale, so nothing is enlarged
    and the crop stays the frame's own.
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
                <div class="absolute inset-0 overflow-hidden">
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
                {{-- 535 of the column's 602, which is the frame's own title box
                     and not the width of the track it sits in. It is what makes
                     the titles break where the design breaks them: "Fit-Out
                     Contracting" measures 548 here, so given the whole 602 it
                     sat on one line where the file has two. The frame's line
                     counts, in order, are 2 2 2 2 1 2 2 1 3 2 — all of them
                     this box's own wrapping, except Design AND Build, which
                     carries a newline in the text itself.

                     Through a custom property because the width is the band's
                     own: the frame widens the last one to 589, and a class
                     built out of config would never reach Tailwind's scanner. --}}
                <h2 style="--title-box:{{ $service['title_box'] ?? '88.87%' }}"
                    class="reveal font-display text-[clamp(1.5rem,2.78vw,48px)] font-medium uppercase leading-[0.9375] tracking-normal text-ink lg:max-w-[var(--title-box)]">
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
