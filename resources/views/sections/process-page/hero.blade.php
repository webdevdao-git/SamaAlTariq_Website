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

    <img src="{{ \App\Support\Asset::versioned($hero['image']) }}" alt=""
         fetchpriority="high" decoding="async"
         class="absolute inset-0 -z-10 h-full w-full object-cover">

    {{-- The same scrim the project heroes carry, so a page of white type over
         a photograph reads the same wherever it appears on this site. --}}
    <div aria-hidden="true" class="absolute inset-0 -z-10"
         style="background-image:linear-gradient(0deg,rgba(0,0,0,0.46) 0%,rgba(102,102,102,0) 117%)"></div>

    <x-site-header :login="false"/>

    <div class="relative z-10 mt-auto pb-[clamp(1rem,1.91vw,33px)] text-white">
        <div class="shell">

            {{-- 0.5px of white at half strength, drawn left to right. --}}
            <span aria-hidden="true" class="reveal-line block h-px w-full bg-white/50"></span>

            {{-- Label, summary and the link out, 26 under the rule. The summary
                 is 670 wide in a 1568 column and the link ends on the right
                 gutter, which is what space-between gives at every width. --}}
            <div class="mt-[clamp(1rem,1.505vw,26px)] flex flex-col gap-[clamp(1rem,2.31vw,40px)] lg:flex-row lg:items-start lg:justify-between">
                <p class="reveal text-[clamp(1rem,1.157vw,20px)] font-semibold leading-[1.35]">{{ $hero['label'] }}</p>

                <p class="reveal max-w-[670px] text-[clamp(1.125rem,1.389vw,24px)] font-medium leading-[1.375]"
                   style="transition-delay:120ms">{{ $hero['body'] }}</p>

                <a href="{{ \App\Support\Nav::href($hero['cta']['href']) }}"
                   class="reveal group flex shrink-0 items-center gap-2 text-[clamp(0.875rem,1.042vw,18px)] font-medium transition-opacity hover:opacity-70"
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
