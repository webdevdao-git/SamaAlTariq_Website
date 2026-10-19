@php($hero = config('site.hero'))

{{--
    Figma: frame 1195:3, 1728×1117.
    Photo fills the frame under two stacked gradients, the header sits at the
    top, a hairline rule separates a three-column intro row, and the display
    type occupies the lower third.

    Motion: the section sinks at a quarter of scroll speed while the photo
    pushes in to 1.1×, and the display words rise out of line masks.
--}}
{{-- data-hero-zoom hands the hero's scroll to scenes.js, which pins it and
     zooms through the company's mark — see heroZoom() there. --}}
<section id="top" data-hero-zoom class="relative isolate min-h-[100svh] overflow-hidden bg-teal">
    <div data-hero class="relative flex min-h-[100svh] flex-col" style="will-change:transform">

        <div data-hero-media class="absolute inset-0 -z-10" style="will-change:transform;transform-origin:center">
            {{-- Versioned: the CDN holds static files for a week, so replacing
                 the photograph without changing its name would leave visitors
                 on the old one. The stamp is the file's own mtime. --}}
            @php($first = $hero['slides'][0])
            <img src="{{ \App\Support\Asset::versioned($first['src']) }}" alt="{{ $first['title'] }}"
                 fetchpriority="high" decoding="async"
                 class="absolute inset-0 h-full w-full object-cover">
            {{-- The rest of the slides, stacked over the first and shown in
                 turn by motion/hero-slider.js. Lazy: the first is what paints. --}}
            @foreach (array_slice($hero['slides'], 1) as $slide)
                <img src="{{ \App\Support\Asset::versioned($slide['src']) }}" alt="" loading="lazy" decoding="async"
                     data-hero-slide
                     class="hero-slide absolute inset-0 h-full w-full object-cover">
            @endforeach
            {{-- z-10: always over the slides, which hero-slider.js raises to 2 as
                 each one comes in. --}}
            <div aria-hidden="true" class="absolute inset-0 z-10"
                 style="background-image:linear-gradient(0deg,rgba(0,0,0,0.55) 0%,rgba(0,0,0,0.18) 45%,rgba(0,0,0,0) 70%),linear-gradient(90deg,rgba(0,0,0,0.3) 0%,rgba(0,0,0,0) 55%),linear-gradient(180deg,rgba(0,0,0,0.25) 0%,rgba(0,0,0,0) 22%)"></div>
        </div>

        <x-site-header/>

        {{-- One message per slide, stacked in place; motion/hero-slider.js
             shows the one that goes with the picture. --}}
        <div class="relative z-10 mt-auto pb-[clamp(2rem,6vh,4.5rem)] text-white [text-shadow:0_1px_18px_rgba(0,0,0,0.35)]">
            <h1 class="sr-only">{{ config('site.legal_name') }} — Interior Fit-Out in Dubai</h1>
            <div class="shell">
                @isset($hero['specialism'])
                    <p class="hero-spec">
                        @foreach ($hero['specialism'] as $i => $part)
                            @if ($i > 0)<span aria-hidden="true" class="hero-spec__dot"></span>@endif
                            <span>{{ $part }}</span>
                        @endforeach
                    </p>
                @endisset
                <div class="hero-msgs editorial-heading text-fluid-hero uppercase" aria-hidden="true">
                    @foreach ($hero['slides'] as $i => $slide)
                        <p class="hero-msg {{ $i === 0 ? 'is-current' : '' }}" data-hero-msg>
                            @foreach ($slide['lines'] as $line)
                                <span class="hero-msg__line"><span>{{ $line }}</span></span>
                            @endforeach
                        </p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<x-site-menu/>
