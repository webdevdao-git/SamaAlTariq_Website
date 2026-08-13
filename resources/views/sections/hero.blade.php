@php($hero = config('site.hero'))

{{--
    Figma: frame 1195:3, 1728×1117.
    Photo fills the frame under two stacked gradients, the header sits at the
    top, a hairline rule separates a three-column intro row, and the display
    type occupies the lower third.

    Motion: the section sinks at a quarter of scroll speed while the photo
    pushes in to 1.1×, and the display words rise out of line masks.
--}}
<section id="top" class="relative isolate min-h-[100svh] overflow-hidden bg-night">
    <div data-hero class="relative flex min-h-[100svh] flex-col" style="will-change:transform">

        <div data-hero-media class="absolute inset-0 -z-10" style="will-change:transform;transform-origin:center">
            <img src="{{ asset($hero['image']) }}" alt="{{ $hero['alt'] }}"
                 fetchpriority="high" decoding="async"
                 class="absolute inset-0 h-full w-full object-cover">
            <div aria-hidden="true" class="absolute inset-0"
                 style="background-image:linear-gradient(0deg,rgba(0,0,0,0.47) 0%,rgba(102,102,102,0) 116.97%),linear-gradient(90deg,rgba(0,0,0,0.25) 0%,rgba(0,0,0,0.25) 100%)"></div>
        </div>

        <x-site-header/>

        {{-- The stack is anchored to the bottom, so this gap is what places the
             hairline: the design sits it just under the half-height mark. --}}
        <div class="relative z-10 mt-auto flex flex-col gap-[clamp(2.75rem,15vh,9rem)] pt-[clamp(9rem,30vh,20rem)] pb-[clamp(2rem,5.5vh,4.5rem)]">
            {{-- Intro row --}}
            <div class="shell">
                <div class="border-t border-white/25 pt-[clamp(1.25rem,1.5vw,26px)]">
                    <div class="grid gap-x-6 gap-y-5 text-white md:grid-cols-12 md:items-start">
                        <p data-split data-split-delay="520"
                           class="text-fluid-body font-semibold md:col-span-3 md:max-w-[170px]">{{ $hero['eyebrow'] }}</p>

                        {{--
                            From lg the intro sits on columns 5–10 rather than
                            centred on the page: the design puts it much closer
                            to the CTA than to the eyebrow, which is what stops
                            the row reading as three evenly spaced blocks. At md
                            the tracks are too narrow to give the link its own
                            two columns, so the row stays evenly spaced there.
                        --}}
                        <p data-split data-split-delay="600"
                           class="text-fluid-lead font-medium md:col-span-6 md:justify-self-center md:text-center lg:col-start-5 lg:max-w-[670px]">{{ $hero['intro'] }}</p>

                        <a href="{{ $hero['cta']['href'] }}"
                           class="group inline-flex items-center gap-1 text-fluid-sm font-medium md:col-span-3 md:justify-self-end md:text-right lg:col-span-2 lg:whitespace-nowrap">
                            {{ $hero['cta']['label'] }}
                            <x-icon name="arrow-right" class="w-[clamp(20px,1.62vw,28px)] transition-transform duration-300 group-hover:translate-x-1"/>
                        </a>
                    </div>
                </div>
            </div>

            {{--
                Display type: two lines, not three. The first runs gutter to
                gutter with the words pushed apart, the second centres under the
                space they leave.

                The first line is a flex row rather than columns of a grid
                because the display face here is a substitute that sets wider
                than the licensed one in the Figma file — on a twelve-column
                split "Precision" fell off the end of its track and wrapped.
                Pushing the two words to opposite gutters lets the gap between
                them, not the line count, absorb the difference. `shrink-0`
                keeps flex from squeezing them back into a wrap.
            --}}
            <h1 class="shell editorial-heading text-fluid-hero uppercase text-white">
                <span class="grid gap-y-[0.08em]">
                    <span class="md:flex md:items-baseline md:justify-between">
                        <span data-split data-split-delay="120"
                              class="block md:shrink-0 md:whitespace-nowrap">{{ $hero['words']['first'] }}</span>
                        <span data-split data-split-delay="220"
                              class="block md:shrink-0 md:whitespace-nowrap md:text-right">{{ $hero['words']['second'] }}</span>
                    </span>
                    <span data-split data-split-delay="320"
                          class="block md:w-9/12 md:text-center">{{ $hero['words']['third'] }}</span>
                </span>
            </h1>
        </div>
    </div>
</section>

<x-site-menu/>
