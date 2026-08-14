{{--
    Figma 1472:1340, 1728x1117. The project's own photograph full bleed, the
    title on the gutter, and a hairline over a row that reads "Scroll" on the
    left and returns to the gallery on the right.

    Laid out from the foot rather than the top, because that is where the frame
    fixes it: 60 under the row, the row itself 27, 31 up to the line, and 70
    from the line to the foot of the title. Anchoring to the top instead would
    let a long title push the row off the bottom of the picture.

    Below lg the frame's 1.55:1 would leave a 250px band on a phone, so the
    picture takes the viewport there and the proportion resumes at lg.
--}}
<section id="top" class="relative isolate flex min-h-[100svh] flex-col overflow-hidden bg-night lg:aspect-[1728/1117] lg:min-h-0">

    <img src="{{ asset('images/projects/' . $slug . '/hero.webp') }}"
         alt="{{ $project['title'] }} — {{ $project['location'] }}"
         fetchpriority="high" decoding="async"
         class="absolute inset-0 -z-10 h-full w-full object-cover">

    {{-- The frame's own scrim: one linear gradient, black at the foot to a
         transparent grey that runs out past the top of the picture, the whole
         fill at 46%. Written out at that strength rather than layered. --}}
    <div aria-hidden="true" class="absolute inset-0 -z-10"
         style="background-image:linear-gradient(0deg,rgba(0,0,0,0.46) 0%,rgba(102,102,102,0) 117%)"></div>

    <x-site-header :login="false"/>

    <div class="relative z-10 mt-auto pb-[clamp(1.5rem,3.47vw,60px)] text-white">
        <div class="shell">

            {{-- 108/99 Juana Alt Medium, uppercase, measured to 1187 so the
                 longer titles break where the frame breaks this one. --}}
            <h1 class="font-display text-[clamp(2.25rem,6.25vw,108px)] font-medium uppercase leading-[0.917] tracking-normal lg:max-w-[68.7vw]">
                <span data-split data-split-by="word">{{ $project['title'] }}</span>
            </h1>

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
