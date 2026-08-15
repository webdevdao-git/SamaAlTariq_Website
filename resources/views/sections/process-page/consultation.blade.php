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
    </div>
</section>
