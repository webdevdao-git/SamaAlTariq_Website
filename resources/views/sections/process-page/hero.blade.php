@php($hero = config('site.process_page.hero'))

{{--
    Figma 1510:385, 1728x1117. A photograph with the page's own summary over
    it and the title split across the two gutters — "OUR" on the left,
    "PROCESS" ending on the right.

    Laid out from the foot, as the frame fixes it: 33 under the title, the
    title 148, 280 up to the row of copy, 26 more to the hairline. Anchoring
    from the top would let the row drift as the copy reflows.
--}}
<section id="top" class="relative isolate flex h-[100svh] flex-col overflow-hidden bg-night">

    {{-- Bottom, not centre. The frame's SVG gives the photograph's placement
         as matrix(0.00025 0 0 0.00038675 0 -0.160687) on the 1728x1117 rect:
         4000 x 0.00025 is exactly the frame's width, 3000 x 0.00038675 is
         1296 against its 1117, and the -0.160687 is -179.5px — the whole 179
         of overflow taken off the head, none off the foot. Centred, as this
         was, it takes 90 off each and the room sits 90 too low. --}}
    <img src="{{ \App\Support\Asset::versioned($hero['image']) }}" alt=""
         fetchpriority="high" decoding="async"
         class="absolute inset-0 -z-10 h-full w-full object-cover object-bottom">

    {{-- The same scrim the project heroes carry, so a page of white type over
         a photograph reads the same wherever it appears on this site. --}}
    <div aria-hidden="true" class="absolute inset-0 -z-10"
         style="background-image:linear-gradient(0deg,rgba(0,0,0,0.47) 0%,rgba(102,102,102,0) 117%)"></div>

    {{-- And the flat black the frame lays over that gradient. Its SVG is
         explicit about both: a 0.47 gradient rect and then a black rect at
         0.2, one on top of the other. Only the gradient was here, which left
         the photograph a fifth brighter than the file. --}}
    <div aria-hidden="true" class="absolute inset-0 bg-black/20"></div>

    <x-site-header/>

    <div class="relative z-10 mt-auto pb-[clamp(1rem,1.91vw,33px)] text-white">
        <div class="shell">

            {{-- 0.5px of white at half strength, drawn left to right. --}}
            <span aria-hidden="true" class="reveal-line block h-[0.5px] w-full bg-white/50"></span>

            {{-- Label, summary and the link out, 26 under the rule.

                 THREE TRACKS, NOT SPACE-BETWEEN. Space-between puts the middle
                 item wherever the leftover room falls, which is not where the
                 frame has it: measured off the frame's own render, the label
                 runs 82–187, the summary 658–1302 and the link ends on the
                 right gutter, and space-between started the summary at 509 —
                 149 to the left of the file, and 60 narrower, so it broke a
                 line earlier as well.

                 578 from the gutter to the summary, the summary's own 670, and
                 320 to the right gutter: 1568. As fractions, so the three hold
                 their proportions at every width rather than only at 1728.

                 lg:gap-0 because those three already account for the space
                 between them — leaving the stack's 40 on would take 80 out of
                 the 1568 and push the summary 13 past where the frame has it.
                 The gap still applies below lg, where the three are a
                 column. --}}
            <div class="mt-[clamp(1rem,1.505vw,26px)] flex flex-col gap-[clamp(1rem,2.31vw,40px)] lg:grid lg:grid-cols-[578fr_670fr_320fr] lg:items-start lg:gap-0">
                <p class="reveal text-[clamp(1rem,1.157vw,20px)] font-semibold leading-[1.35]">{{ $hero['label'] }}</p>

                <p class="reveal text-[clamp(1.125rem,1.389vw,24px)] font-medium leading-[1.375]"
                   style="transition-delay:120ms">{{ $hero['body'] }}</p>

                <a href="{{ \App\Support\Nav::href($hero['cta']['href']) }}"
                   class="reveal group flex shrink-0 items-center gap-0 text-[clamp(0.875rem,1.042vw,18px)] font-medium transition-opacity hover:opacity-70 lg:justify-self-end"
                   style="transition-delay:220ms">
                    {{ $hero['cta']['label'] }}
                    <x-icon name="arrow-right" :size="28" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                </a>
            </div>

            {{-- 280 to the title, then the two words on the two gutters. --}}
            <h1 class="mt-[clamp(2.5rem,16.2vw,280px)] flex items-baseline justify-between gap-8 font-display text-[clamp(2.5rem,6.25vw,108px)] font-medium uppercase leading-[1.37] tracking-normal">
                <span data-split data-split-by="word">{{ $hero['heading'][0] }}</span>
                <span data-split data-split-by="word" data-split-delay="140">{{ $hero['heading'][1] }}</span>
            </h1>
        </div>
    </div>
</section>

<x-site-menu/>
