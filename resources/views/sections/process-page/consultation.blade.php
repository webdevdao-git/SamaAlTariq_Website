@php($consultation = config('site.process_page.consultation'))

{{--
    Figma 1510:630, 1728x980 on teal. A 958x522 photograph centred in the band
    with the word across it — 128 Juana Alt SemiBold in white, itself centred,
    so it reads as one mark over the picture rather than as a caption under it.

    The word is a heading, not decoration: it is what the section is, and the
    page has nothing else to name this last step by.
--}}
<section class="relative overflow-hidden bg-teal py-[clamp(3rem,13.19vw,228px)]">
    <div class="shell flex justify-center">
        <div class="reveal-rise relative w-full max-w-[958px]">
            <div class="relative w-full overflow-hidden" style="aspect-ratio:958/522">
                <img src="{{ \App\Support\Asset::versioned($consultation['image']) }}" alt="" loading="lazy" decoding="async"
                     class="h-full w-full object-cover">
            </div>

            {{-- Over the picture, centred on it, and wider than it — the frame
                 sets the word 1179 across a 958 photograph, so it runs past
                 both edges. --}}
            <h2 class="pointer-events-none absolute left-1/2 top-[28.5%] w-[123%] -translate-x-1/2 text-center font-display text-[clamp(2.5rem,7.41vw,128px)] font-semibold uppercase leading-[1.367] tracking-normal text-white">
                <span data-split data-split-by="letter">{{ $consultation['word'] }}</span>
            </h2>
        </div>
    </div>
</section>
