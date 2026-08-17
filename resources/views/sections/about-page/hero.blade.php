@php($hero = config('site.about_page.hero'))

{{--
    Figma: frame 1377:4, 1728×1117.

    The same photo-under-gradients hero as the landing page — same header, same
    two stacked gradients — but the lower third is arranged differently, so it
    is written out here rather than shared:

      · a sentence-case headline on the gutter (Figma x 80, y 720–850), set in
        the display serif at ~64px with normal leading rather than the landing
        page's 108px uppercase slabs;
      · the page tag opposite it on the right gutter, sitting on the headline's
        last line, with the brackets drawn by the view so the config holds a
        plain label;
      · a band across the foot (Figma y 933–1117) carrying two lines of copy on
        the left gutter and three figures that end on the right one.

    The stats are the band's own, not the landing page's about section — "95%
    On-time handovers" appears nowhere else — so they live under about_page in
    the config.
--}}
{{--
    Scroll behaviour: the photograph pins while the page moves over it.

    The backdrop is a layer twice the height of the hero, and the picture inside
    it is `sticky top-0` at exactly one viewport tall. Scrolling down, the
    picture holds against the top of the screen while the headline, the band and
    then the whole next section ride up across it, and it is uncovered again on
    the way back. The section clips the half of the backdrop that hangs below it.

    `overflow-clip`, not `overflow-hidden`, and this is the load-bearing detail:
    `hidden` would make the section a scrolling box, which becomes the sticky
    element's scrollport and pins it to a container that never scrolls — the
    effect silently does nothing. `clip` cuts the overflow without creating that
    box, so the picture still sticks to the viewport.

    No JavaScript: the landing hero's sink-and-zoom is driven by a scroll
    listener, and this deliberately is not. Dropping the data-hero and
    data-hero-media hooks is also what keeps initHeroParallax off this page —
    it needs both and returns when either is missing, so the landing page keeps
    its own behaviour untouched.
--}}
<section id="top" class="relative isolate flex min-h-[100svh] flex-col overflow-clip bg-night">

    <div data-hero-bg aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[200svh]">
        <div data-hero-pin class="sticky top-0 h-[100svh] w-full overflow-hidden">
            <img src="{{ asset($hero['image']) }}" alt="{{ $hero['alt'] }}"
                 fetchpriority="high" decoding="async"
                 class="absolute inset-0 h-full w-full object-cover">
            {{-- One gradient, not the landing hero's two. Measured off the
                 frame: the overlay is ~0.05 at the top and ~0.46 at the foot,
                 which is this ramp alone — the flat 25% black the landing page
                 also carries would darken the whole photo by a further third. --}}
            <div class="absolute inset-0"
                 style="background-image:linear-gradient(0deg,rgba(0,0,0,0.47) 0%,rgba(102,102,102,0) 116.97%)"></div>
        </div>
    </div>

    <x-site-header/>

    <div class="relative z-10 mt-auto pb-[clamp(1rem,1.91vw,33px)] text-white">
        <div class="shell">

            {{--
                The same arrangement the process page's hero carries, which is
                where this one's figures come from: that frame was measured at
                1728 and this is the same 1117 hero with the same parts. A rule,
                26 down to a row of label, summary and the link out, then 280 to
                the page's word on the gutter.
            --}}
            <span aria-hidden="true" class="reveal-line block h-px w-full bg-white/50"></span>

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

            {{-- The word on the left gutter and the figures against the right
                 one, sharing a baseline — which is what items-end gives, and
                 what puts the figures level with the foot of the word rather
                 than with its cap. --}}
            <div class="mt-[clamp(2.5rem,16.2vw,280px)] flex flex-col gap-[clamp(1.5rem,2.31vw,40px)] lg:flex-row lg:items-end lg:justify-between">
                <h1 class="font-display text-[clamp(2.5rem,6.25vw,108px)] font-medium uppercase leading-[1.37] tracking-normal">
                    <span data-split data-split-by="word">{{ $hero['word'] }}</span>
                </h1>

                {{-- Each divider belongs to the figure that follows it, so the
                     row can wrap without leaving a rule hanging at the end of a
                     line.

                     The rule is centred on the figure beside it, not sat on its
                     foot: it is 52 against a figure of about 110, so aligned to
                     the bottom it hung off the label and read as underlining
                     the pair rather than separating them. Centred it is the
                     same rule the landing page's stats use. The figures
                     themselves stay bottom-aligned to each other — that is the
                     dl's own items-end, which is a different alignment doing a
                     different job. --}}
                <dl class="flex flex-wrap items-end gap-[clamp(1.25rem,2.31vw,40px)]">
                    @foreach ($hero['stats'] as $i => $stat)
                        <div class="reveal flex items-center gap-[clamp(1.25rem,2.31vw,40px)]"
                             style="transition-delay:{{ 140 + $i * 110 }}ms">
                            @if ($i > 0)
                                <span aria-hidden="true" class="h-[52px] w-px bg-white/30"></span>
                            @endif
                            <div>
                                <dt class="sr-only">{{ $stat['label'] }}</dt>
                                <dd>
                                    <span class="block text-fluid-stat font-medium tracking-[-0.03em]">{{ $stat['value'] }}</span>
                                    <span class="mt-1 block text-fluid-body">{{ $stat['label'] }}</span>
                                </dd>
                            </div>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>
    </div>
</section>

{{--
    The overlay the header's MENU button opens, exactly as sections/hero.blade.php
    pairs them on the landing page. It has to be rendered by whichever hero draws
    the button: motion/menu.js looks for [data-menu] and returns if it is absent,
    and the button's aria-controls points at this element's id — without it the
    control is inert and refers to nothing.
--}}
<x-site-menu/>
