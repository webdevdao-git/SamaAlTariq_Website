@php($hero = config('site.joinery_page.hero'))

{{--
    NOT the full-bleed photographic hero the other pages open with, and
    deliberately.

    Every joinery photograph this site holds is 1200 wide. Stretched across a
    window it would land at 0.83 source pixels per CSS pixel at 1440 and half
    that again on a retina screen — the exact softness the fit-out panel was
    rebuilt to fix. Held to a 500 column it is 2.4 per pixel and sharp.

    So the page opens on the night ground the site already uses, with the
    photograph beside the title at a size it can carry. A wide workshop
    photograph from Alwan is what this page needs to open like the others, and
    the moment one exists this section can go back to the shared arrangement.
--}}
<section id="top" class="relative isolate flex min-h-[100svh] flex-col bg-night text-white">

    <x-site-header/>

    <div class="mt-auto pt-[clamp(4rem,12vh,9rem)] pb-[clamp(2.5rem,4.63vw,80px)]">
        <div class="shell">

            {{-- The same hairline the process and about heroes carry, so a
                 page that opens without a photograph still opens like one of
                 this site's pages. --}}
            <span aria-hidden="true" class="reveal-line block h-px w-full bg-white/25"></span>

            <div class="mt-[clamp(1.5rem,2.31vw,40px)] grid gap-[clamp(2rem,3.7vw,64px)] lg:grid-cols-[1fr_500px] lg:items-end lg:gap-[clamp(2.5rem,4.63vw,80px)]">

                <div class="flex flex-col gap-[clamp(1rem,1.62vw,28px)]">
                    <p class="reveal text-fluid-label font-medium text-teal">{{ $hero['label'] }}</p>

                    <h1 class="editorial-heading text-fluid-hero uppercase">
                        @foreach ($hero['heading'] as $line)
                            <span data-split data-split-delay="{{ 120 + $loop->index * 200 }}" class="block">{{ $line }}</span>
                        @endforeach
                    </h1>

                    {{-- Lead and body in one column at the measures the rest of
                         the site sets: the lead is the sentence the page is
                         about, the body the reason it matters. --}}
                    <p data-split data-split-delay="360" class="max-w-[24em] text-fluid-lead font-medium">{{ $hero['lead'] }}</p>

                    <p class="reveal max-w-[42ch] text-fluid-body font-medium text-white/70" style="transition-delay:200ms">{{ $hero['body'] }}</p>
                </div>

                {{-- Its own proportions, capped at the width the file can
                     carry rather than stretched to the column. --}}
                <figure class="reveal w-full lg:w-[500px]" style="transition-delay:160ms">
                    <div class="relative aspect-[1200/1280] w-full overflow-hidden bg-white/5">
                        <img src="{{ \App\Support\Asset::versioned($hero['image']['src']) }}" alt="{{ $hero['image']['alt'] }}"
                             fetchpriority="high" decoding="async"
                             class="absolute inset-0 h-full w-full object-cover">
                    </div>
                </figure>
            </div>
        </div>
    </div>
</section>

{{--
    The overlay the header's MENU button opens, rendered by the hero that draws
    the button — the same pairing every other hero on this site makes.
    motion/menu.js binds to the first [data-menu] on the page and returns when
    there is none, and the button's aria-controls points at this element's id,
    so without it the control is inert and refers to nothing.
--}}
<x-site-menu/>
