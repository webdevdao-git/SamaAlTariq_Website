{{--
    Figma 1472:1340, 1728x1117. The project's own photograph full bleed, the
    title on the gutter, and a hairline over a row that reads "Scroll" on the
    left and returns to the gallery on the right.

    Laid out from the foot rather than the top, because that is where the frame
    fixes it: 60 under the row, the row itself 27, 31 up to the line, and 70
    from the line to the foot of the title. Anchoring to the top instead would
    let a long title push the row off the bottom of the picture.

    THE HERO IS THE SCREEN, not the frame's 1728x1117. That proportion is
    1117 tall at the width it was drawn for, which is taller than the laptop
    it gets read on — the picture filled the window and the hairline and its
    row fell below the fold, so the first thing the page did was hide its own
    footer. At the height of the viewport the whole of it is on screen at once,
    on any window, which is what the frame draws at its own size.

    THE PICTURE CYCLES, which is the reference's hero behaviour. Four
    photographs from the project's own shoot — the client's files, not the
    frame's stock, which is where the covers on the projects page still come
    from. motion/project-hero.js crosses them, and with the script absent or
    reduced motion asked for, the first simply stays.
--}}
<section id="top" data-hero-slides
         class="relative isolate flex h-[100svh] flex-col overflow-hidden bg-night">

    @foreach ($slides as $i => $slide)
        <img data-hero-slide src="{{ asset('images/projects/' . $slug . '/' . $slide) }}"
             @if ($i === 0)
                 alt="{{ $project['title'] }} — {{ $project['location'] }}"
                 fetchpriority="high"
             @else
                 alt="" aria-hidden="true" loading="lazy"
             @endif
             decoding="async"
             class="absolute inset-0 -z-10 h-full w-full object-cover"
             style="opacity:{{ $i === 0 ? '1' : '0' }};transform:scale({{ $i === 0 ? '1' : '0.8' }})">
    @endforeach

    {{-- The frame's own scrim: one linear gradient, black at the foot to a
         transparent grey that runs out past the top of the picture, the whole
         fill at 46%. Written out at that strength rather than layered. --}}
    <div aria-hidden="true" class="absolute inset-0 -z-10"
         style="background-image:linear-gradient(0deg,rgba(0,0,0,0.46) 0%,rgba(102,102,102,0) 117%)"></div>

    <x-site-header :login="false"/>

    <div class="relative z-10 mt-auto pb-[clamp(1.5rem,3.47vw,60px)] text-white">
        <div class="shell">

            {{--
                The title, and opposite it the strip of what the hero holds.

                The reference sets four 84x58 thumbnails 28.8 apart on the right
                gutter, their feet level with the foot of the title — measured
                at its 1440, so they are carried here as fractions of the width
                and come out 101x70 at the frame's 1728.

                Its own are inert: cursor auto, no hover, and clicking one does
                not change the picture. Ours select the slide they show, because
                a strip of photographs beside a slideshow is a control whether
                or not it is wired up, and one that ignores the click is worse
                than none. The current one is at full strength and the rest at
                60%, which is this site's muted tone; the reference marks none
                of them, having nothing to mark.

                108/99 Juana Alt Medium, uppercase, measured to 1187 so the
                longer titles break where the frame breaks this one.
            --}}
            <div class="flex flex-col gap-[clamp(1.5rem,2.31vw,40px)] md:flex-row md:items-end md:justify-between">
                <h1 class="font-display text-[clamp(2.25rem,6.25vw,108px)] font-medium uppercase leading-[0.917] tracking-normal lg:max-w-[68.7vw]">
                    <span data-split data-split-by="word">{{ $project['title'] }}</span>
                </h1>

                @if (count($slides) > 1)
                    <div data-hero-thumbs
                         class="hidden shrink-0 items-end gap-[clamp(1rem,2vw,34.5px)] md:flex">
                        @foreach ($slides as $i => $slide)
                            <button type="button" data-hero-thumb="{{ $i }}"
                                    aria-label="Show photograph {{ $i + 1 }} of {{ count($slides) }}"
                                    @if ($i === 0) aria-current="true" @endif
                                    class="w-[clamp(56px,5.85vw,101px)] shrink-0 overflow-hidden opacity-60 transition-opacity duration-300 aria-[current]:opacity-100 hover:opacity-100 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white"
                                    style="aspect-ratio:84/58">
                                <img src="{{ asset('images/projects/' . $slug . '/' . $slide) }}"
                                     alt="" loading="lazy" decoding="async"
                                     class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- 70 from the title to the line, then 31 to the row. --}}
            <span aria-hidden="true"
                  class="reveal-line mt-[clamp(2rem,4.05vw,70px)] block h-px w-full bg-white/50"></span>

            <div class="mt-[clamp(1rem,1.794vw,31px)] flex items-baseline justify-between gap-4 text-[clamp(1rem,1.157vw,20px)] font-semibold leading-[1.35]">
                <span aria-hidden="true">Scroll</span>
                <a href="{{ route('projects') }}" class="transition-opacity hover:opacity-70">Return To Gallery</a>
            </div>
        </div>
    </div>
</section>

{{-- The overlay the header's MENU button opens. Rendered by whichever hero
     draws the button: motion/menu.js returns without it and the button's
     aria-controls would point at nothing. --}}
<x-site-menu/>
