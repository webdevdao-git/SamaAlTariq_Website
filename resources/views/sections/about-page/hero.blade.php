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

    <x-site-header :login="false"/>

    <div class="relative z-10 mt-auto text-white">

        {{-- Headline and page tag. items-end puts the tag on the baseline
             of the headline's last line, which is where the design has it,
             rather than level with its top. --}}
        <div class="shell flex flex-col gap-4 pb-[clamp(1.5rem,2.31vw,40px)] sm:flex-row sm:items-end sm:justify-between sm:gap-8">
            <h1 class="display max-w-[900px] text-[clamp(2rem,3.7vw,64px)] leading-[1.15]">
                @foreach ($hero['heading'] as $i => $line)
                    <span data-split data-split-by="word" data-split-delay="{{ $i * 260 }}"
                          class="block">{{ $line }}</span>
                @endforeach
            </h1>

            <p class="reveal shrink-0 text-fluid-sm font-medium whitespace-nowrap" style="transition-delay:620ms">[&nbsp;{{ $hero['tag'] }}&nbsp;]</p>
        </div>

        {{-- The foot band, 192 tall: its hairline is at frame y 925 and
             the frame is 1117. No fill of its own — the hero gradient above
             already carries the darkening the design has down here. --}}
        <div class="border-t border-white/20 pt-[clamp(1rem,2.55vw,44px)] pb-[clamp(1rem,1.68vw,29px)]">
            <div class="shell flex flex-col gap-[clamp(1.5rem,2.31vw,40px)] lg:flex-row lg:items-start lg:justify-between">

                <p class="reveal text-fluid-lead font-semibold leading-[1.375]">
                    @foreach ($hero['lead'] as $line)
                        <span class="block">{{ $line }}</span>
                    @endforeach
                </p>

                {{-- Figures end on the right gutter. Each divider belongs to
                     the item that follows it, so the row can wrap without
                     leaving a rule hanging at the end of a line. --}}
                <dl class="flex flex-wrap items-start gap-[clamp(1.25rem,2.31vw,40px)]">
                    @foreach ($hero['stats'] as $i => $stat)
                        {{-- The reveal is on each figure, not on the row, so
                             they count in from the left one at a time — the
                             rule beside a figure belongs to it and arrives
                             with it. --}}
                        <div class="reveal flex items-start gap-[clamp(1.25rem,2.31vw,40px)]"
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
