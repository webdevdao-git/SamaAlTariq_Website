@php($hero = config('site.joinery_page.hero'))

{{--
    The frame's hero: a photograph filling the screen, a rule across the lower
    third with a three-part row under it — label, summary, partner — and the
    title split across the two gutters, CRAFT on the left and BEHIND THE BUILD
    ending on the right.

    The same arrangement the process and about heroes carry, so the parts are
    set the way those set them: anchored from the foot rather than the head, so
    the row cannot drift as the copy reflows.
--}}
<section id="top" class="relative isolate flex min-h-[100svh] flex-col overflow-hidden bg-night">

    {{-- 2400 wide, made from the 1200 workshop frame. This is full-bleed and
         the services file covers a window at 0.83 source pixels per CSS pixel
         — the softness the fit-out panel was rebuilt to fix, and the same
         treatment applied here. --}}
    <img src="{{ \App\Support\Asset::versioned($hero['image']['src']) }}" alt="{{ $hero['image']['alt'] }}"
         fetchpriority="high" decoding="async"
         class="absolute inset-0 -z-10 h-full w-full object-cover">

    {{-- The scrim every photographic hero on this site carries: the ramp, then
         the flat black over it, so white type reads the same here as there. --}}
    <div aria-hidden="true" class="absolute inset-0 -z-10"
         style="background-image:linear-gradient(0deg,rgba(0,0,0,0.47) 0%,rgba(102,102,102,0) 117%)"></div>
    <div aria-hidden="true" class="absolute inset-0 -z-10 bg-black/20"></div>

    <x-site-header/>

    <div class="relative z-10 mt-auto pb-[clamp(1rem,1.91vw,33px)] text-white">
        <div class="shell">

            <span aria-hidden="true" class="reveal-line block h-px w-full bg-white/50"></span>

            {{-- Three tracks rather than space-between: the summary sits in the
                 middle of the measure in the frame, and space-between would put
                 it wherever the two labels happened to leave it. --}}
            <div class="mt-[clamp(1rem,1.505vw,26px)] flex flex-col gap-[clamp(1rem,2.31vw,40px)] lg:flex-row lg:items-start lg:justify-between">
                <p class="reveal shrink-0 text-[clamp(1rem,1.157vw,20px)] font-semibold leading-[1.35] lg:w-[16%]">{{ $hero['label'] }}</p>

                <p class="reveal max-w-[670px] text-[clamp(1.125rem,1.389vw,24px)] font-medium leading-[1.375]"
                   style="transition-delay:120ms">{{ $hero['summary'] }}</p>

                <p class="reveal shrink-0 text-[clamp(1rem,1.157vw,20px)] font-semibold leading-[1.35] lg:text-right"
                   style="transition-delay:220ms">{{ $hero['partner_label'] }}</p>
            </div>

            {{--
                The title, gutter to gutter. A flex row rather than a grid, for
                the same reason the landing page's first line is one: the
                display face here sets wider than the licensed one in the file,
                so the gap between the words absorbs the difference instead of
                the line breaking. Stacked below md, where there is no room to
                push them apart.
            --}}
            <h1 class="editorial-heading mt-[clamp(2.5rem,16.2vw,280px)] text-fluid-hero uppercase">
                <span class="flex flex-col md:flex-row md:items-baseline md:justify-between">
                    <span data-split data-split-delay="120" class="block md:shrink-0 md:whitespace-nowrap">{{ $hero['words'][0] }}</span>
                    <span data-split data-split-delay="300" class="block md:shrink-0 md:whitespace-nowrap md:text-right">{{ $hero['words'][1] }}</span>
                </span>
            </h1>
        </div>
    </div>
</section>

{{-- The overlay the header's MENU button opens, rendered by the hero that
     draws the button, as every other hero on this site pairs them. --}}
<x-site-menu/>
