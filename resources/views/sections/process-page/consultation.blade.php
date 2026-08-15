@php($consultation = config('site.process_page.consultation'))

{{--
    Figma 1510:630, 1728x980 on teal: a 958x522 photograph centred in the band
    with the word across it, 128 Juana Alt SemiBold in white and wider than the
    picture, so it runs past both edges.

    THE PICTURE OPENS OUT, which is the reference's closing section — there a
    720x360 window grows to the whole screen while the page holds it, and the
    photograph inside runs a tenth larger than its frame and settles to 1:1 as
    the frame catches up.

    The band is therefore two screens tall with one screen of it pinned: what
    scrolls is the progress of the opening rather than the picture itself. At
    rest it is exactly what the frame draws, which is also what a visitor sees
    who has asked for reduced motion — motion/grow-scene.js leaves it closed.

    The frame is drawn at its full size and scaled down to the design's 958x522,
    never up, so the photograph is never resampled larger than it was cut.
--}}
<section data-grow-scene class="relative h-[200svh] bg-teal">
    <div class="sticky top-0 flex h-[100svh] items-center justify-center overflow-hidden">

        {{-- Over the photograph, not behind it: the frame paints the word last.
             1179 of its 1728 — wider than the 958 picture it crosses, which is
             the overhang the frame draws. It goes as the picture opens. --}}
        <h2 data-grow-word
            class="pointer-events-none absolute z-30 w-[68.2%] text-center font-display text-[clamp(2.5rem,7.41vw,128px)] font-semibold uppercase leading-[1.367] tracking-normal text-white">
            <span data-split data-split-by="letter">{{ $consultation['word'] }}</span>
        </h2>

        <div data-grow-frame class="absolute inset-0 z-20 origin-center overflow-hidden will-change-transform">
            <img data-grow-media src="{{ \App\Support\Asset::versioned($consultation['image']) }}" alt=""
                 loading="lazy" decoding="async"
                 class="h-full w-full origin-center object-cover will-change-transform">
        </div>

        {{-- The invitation, which belongs to the opened state: it arrives as
             the photograph reaches the edges of the screen and is not there
             before, so the word and the copy never share the picture.

             On the gutter and a ninth of the way down, over the picture, which
             is where the frame puts it. --}}
        <div data-grow-copy
             class="pointer-events-none absolute inset-x-0 top-[11%] z-40 opacity-0">
            <div class="shell">
                <p class="text-[clamp(1.25rem,1.85vw,32px)] font-medium leading-[1.3] text-white">
                    @foreach ($consultation['cta']['lines'] as $line)
                        <span class="block">{{ $line }}</span>
                    @endforeach
                </p>

                <a href="{{ \App\Support\Nav::href($consultation['cta']['href']) }}"
                   class="pill group pointer-events-auto mt-[clamp(1rem,1.85vw,32px)]">
                    {{ $consultation['cta']['label'] }}
                    <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                </a>
            </div>
        </div>
    </div>
</section>
