@php($footer = config('site.footer'))
@php($nav = \App\Support\Nav::items())
@php($social = config('site.social'))

{{--
    Figma: frame 1226:1038, 1728×774, background #3FA7B3.

    Three columns above the wordmark lock-up: SAMA AL TARIQ over BUILDING
    CONTRACTING LLC., both set in Juana Alt Medium and sized by measurement
    rather than a fixed vw (see motion/fit-text.js).

    The columns are laid out on the same 12-column grid as the rest of the page
    and share a top edge, so they read as one band instead of three floating
    blocks. The design leaves columns 4–7 empty: the band is navigation at the
    left gutter, the project card past the two-thirds mark, and social at the
    right gutter, with nothing between them.
--}}
<footer class="overflow-hidden bg-teal pt-[clamp(2.5rem,4.63vw,80px)] text-white">
    <div class="shell">
        <div class="grid gap-x-[clamp(1.5rem,2.3vw,40px)] gap-y-[clamp(2.5rem,4vw,68px)] lg:grid-cols-12">

            {{-- Brand + navigation --}}
            <div class="reveal flex flex-col gap-[clamp(1.25rem,2.14vw,37px)] lg:col-span-3">
                <img src="{{ asset('images/logo-mark.png') }}" alt="" width="540" height="462"
                     class="h-auto w-[clamp(44px,3.65vw,63px)]">

                {{--
                    Leading, not a row gap, sets the rhythm here: the design
                    stacks the six links at roughly 1.28× their own size, which a
                    gap on top of default leading overshoots by a third.
                --}}
                <nav aria-label="Footer">
                    <ul class="flex flex-col text-fluid-body leading-[1.28] font-medium">
                        @foreach ($nav as $item)
                            <li>
                                <a href="{{ $item['href'] }}"
                                   class="inline-block transition-opacity hover:opacity-70">{{ $item['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>

            {{--
                Recently completed. Starts at column 8 rather than flowing on
                from the navigation: the design sets the card two thirds across,
                and the empty teal to its left is what gives the wordmark below
                room to read as the footer's subject.

                The card is 417px in the design, which is wider than the three
                columns it starts on, so above 2xl it overflows its track to the
                right instead of claiming a fourth column: column 11 belongs to
                the social list, and taking it drops the list onto its own row.
                The teal it overflows into is empty, so nothing collides.
            --}}
            <div class="reveal lg:col-start-8 lg:col-end-11" style="transition-delay:120ms">
                <a href="{{ \App\Support\Nav::href($footer['recent']['href']) }}" class="group relative block w-full max-w-[417px] 2xl:min-w-[417px]">
                    <span class="mb-[clamp(0.35rem,0.5vw,8.5px)] flex items-center gap-1.5">
                        <x-icon name="dot" class="text-white"/>
                        <span class="text-[clamp(11px,0.81vw,14px)] font-semibold">{{ $footer['recent']['label'] }}</span>
                    </span>

                    <span class="relative block aspect-[417/259] w-full overflow-hidden bg-white/10">
                        <img src="{{ asset($footer['recent']['image']) }}" alt="{{ $footer['recent']['alt'] }}"
                             loading="lazy" decoding="async"
                             class="h-full w-full object-cover transition-transform duration-[900ms] ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-[1.05]">
                    </span>

                    {{--
                        Straddles the bottom edge two thirds along, as in the
                        design — a bare white arrow half on the photograph and
                        half on the teal, not a button. It sits outside the frame
                        because that element clips its own overflow for the hover
                        zoom, which would cut the arrow in half.
                    --}}
                    <span aria-hidden="true"
                          class="pointer-events-none absolute bottom-0 left-[67%] translate-y-1/2 text-white transition-transform duration-500 group-hover:translate-y-[calc(50%-4px)] group-hover:translate-x-1">
                        <x-icon name="diagonal-arrow" class="w-[clamp(28px,2.52vw,44px)]"/>
                    </span>
                </a>
            </div>

            {{--
                Social, as marks rather than as words. The same treatment the
                navigation overlay gives them, which is where the rest of the
                site meets these five.

                Each mark carries no text, so the link takes its accessible name
                from an aria-label and the glyph itself is hidden — otherwise
                the row reads to a screen reader as five unnamed links. The 44px
                box is the tap target: the mark is ~24, which is under the size
                a thumb reliably hits.

                Three across from lg — a 128px cap on a wrapping row, which is
                exactly three of them — so five marks read as a block of 3 over
                2, both lines against the gutter, rather than a broken line. The two columns the design gives this
                are 135 wide at lg and 228 at 1728 — five 44s never fit the
                first, and at the second they fit by 8px, so a single row would
                have held only at the widest window and come apart as 4 and 1
                just below it. Three fit everywhere. Below lg the footer stacks
                and the block has the width for all five, so they run in one
                line there.

                Placed explicitly rather than flowed: at 2xl the card takes the
                grid as far as column 11, and auto-placement would answer that by
                opening a thirteenth column and pushing the list off the page.
            --}}
            <div class="reveal lg:col-start-11 lg:col-end-13 lg:justify-self-end" style="transition-delay:180ms">
                <ul class="flex flex-wrap items-center gap-1 lg:max-w-[128px] lg:justify-end">
                    @foreach ($social as $s)
                        <li>
                            <a href="{{ $s['href'] }}" target="_blank" rel="noreferrer noopener"
                               aria-label="{{ $s['label'] }} — opens in a new tab"
                               class="grid size-11 place-items-center rounded-full text-white/80 transition-colors duration-300 hover:bg-white/10 hover:text-white lg:size-10">
                                <x-icon :name="$s['icon']" class="w-[clamp(20px,1.5vw,24px)]"/>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{--
            Wordmark lock-up. No rule above it: the design separates it from the
            band by distance alone, and a hairline turned the two into stacked
            sections rather than one field with a mark sitting in it.
        --}}
        <div class="mt-[clamp(2.75rem,7.06vw,122px)] pb-[clamp(1.75rem,3.7vw,64px)]">
            {{--
                Face, weight, leading and the subtitle's tracking are in
                .logotype / .logotype-sub — they are one typographic unit and
                the tracking value in particular is load-bearing, so it is
                commented where it is set rather than buried in a class list.

                Sized by measurement (see motion/fit-text.js). The vw values are
                only the no-JavaScript fallback, set low enough not to overflow
                with a wider fallback face.

                Spacing is set in `em` so it scales with the measured font size
                rather than the viewport — the lock-up keeps the same optical
                proportions whatever width fit-text lands on.

                No bottom padding on the first line: in the reference the Q's
                tail sweeps down past the subtitle's cap line and finishes
                beside "LLC.", and padding that cleared the descender pushed the
                two lines apart into stacked lines rather than one lock-up.

                The subtitle's fallback vw is far below the title's because its
                0.86em tracking adds most of the line's rendered width — the vw
                that would fit these characters untracked overflows the shell
                several times over once the tracking is on.
            --}}
            <p data-fit-text class="logotype text-[10.5vw]">
                {{ Str::upper($footer['wordmark']) }}
            </p>
            <p data-fit-text class="logotype logotype-sub mt-[0.28em] text-[1.7vw]">
                {{ Str::upper($footer['wordmark_sub']) }}
            </p>
        </div>

        {{-- Legal bar --}}
        <div class="flex flex-wrap items-center justify-between gap-4 border-t border-white/20 py-[clamp(1rem,1.5vw,26px)]">
            <p class="text-fluid-sm text-white/80">{{ config('site.copyright') }}</p>
            <a href="#top" class="group inline-flex items-center gap-1.5 text-fluid-sm font-medium transition-opacity hover:opacity-70">
                Back to top
                <span class="inline-block rotate-[-90deg] transition-transform duration-300 group-hover:-translate-y-0.5">
                    <x-icon name="arrow-right" class="w-[clamp(16px,1.16vw,20px)]"/>
                </span>
            </a>
        </div>
    </div>
</footer>
