@php($band = config('site.joinery_page.wordmark'))
@php($partner = config('site.joinery_page.partner'))
@php($logo = $partner['logo'] && file_exists(public_path($partner['logo'])) ? $partner['logo'] : null)

{{--
    Figma 1803:2, y1536–2300: a centred label, the two words either side of a
    522x362 picture, and a second centred label under it.

    The words are the SANS at 64, not the display serif — the frame sets them
    in Manrope, which is what keeps this band reading as a caption around a
    photograph rather than as another slab.

    The band carries the scroll animation the client asked for, from
    havenconstructions.com.au, whose arrangement this is: their markup writes
    the picture out at scale(0.2) with the words offset 50px from their places
    and drives all three back to rest. motion/scroll-gallery.js does that here.

    Progressive enhancement: the extra height and the stickiness are applied by
    a group-data variant keyed to the flag the module sets, so without
    JavaScript — or under prefers-reduced-motion — this is a normal-height band
    with the picture at full size and the words in place, which is the frame.
--}}
<section data-scroll-gallery
         class="group/gallery bg-white py-[clamp(3.5rem,5.79vw,100px)] group-data-[gallery-ready]/gallery:py-0"
         style="--travel:{{ $band['travel'] }}svh">

    <div class="group-data-[gallery-ready]/gallery:h-[var(--travel)]" data-gallery-track>
        <div data-gallery-pin class="flex flex-col items-center justify-center gap-[clamp(1.5rem,2.8vw,48px)] group-data-[gallery-ready]/gallery:sticky group-data-[gallery-ready]/gallery:top-0 group-data-[gallery-ready]/gallery:h-[100svh]">

            {{-- THE MARK, WHICH THE FRAME DOES NOT DRAW. It was asked for on
                 this page twice, and rebuilding the band from the file took it
                 off; the frame's own label line is the line that names the
                 company, so the mark sits on it with the label beneath. Small,
                 because the frame gives this line 20px of type and a logo the
                 size of the words would out-shout them. Falls back to the
                 label alone when there is no file. --}}
            <div class="reveal flex flex-col items-center gap-[clamp(0.5rem,0.8vw,14px)]">
                @if ($logo)
                    <img src="{{ \App\Support\Asset::versioned($logo) }}"
                         alt="{{ $partner['mark_alt'] ?? $partner['name'] }}"
                         loading="lazy" decoding="async"
                         class="w-full max-w-[clamp(7.5rem,11vw,180px)] object-contain">
                @endif

                <p class="text-fluid-sm font-medium text-ink">{{ $band['label'] }}</p>
            </div>

            {{-- The words out toward the gutters with the picture centred
                 between them: justify-between on three items puts the two
                 words on the edges and leaves the middle one where it is. --}}
            <div class="shell flex w-full flex-col items-center justify-center gap-[clamp(1.25rem,2.5vw,44px)] sm:flex-row sm:justify-between">
                <p data-gallery-word="0" class="shrink-0 text-[clamp(1.75rem,3.7vw,64px)] leading-[1.1] font-medium text-ink will-change-transform">{{ $band['words'][0] }}</p>

                {{-- The box is fixed and the transform is what moves: scaling
                     the box itself would reflow the row every frame and drag
                     the words with it. --}}
                <div data-gallery-frame
                     class="relative aspect-[522/362] w-full max-w-[clamp(11rem,30vw,522px)] shrink-0 origin-center overflow-hidden bg-mist will-change-transform">
                    @foreach ($band['images'] as $image)
                        <figure data-gallery-slide
                                class="absolute inset-0 transition-opacity duration-700 {{ $loop->first ? 'opacity-100' : 'opacity-0' }}">
                            <img src="{{ \App\Support\Asset::versioned($image['src']) }}" alt="{{ $image['alt'] }}"
                                 loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async"
                                 class="absolute inset-0 h-full w-full object-cover">
                        </figure>
                    @endforeach
                </div>

                <p data-gallery-word="1" class="shrink-0 text-[clamp(1.75rem,3.7vw,64px)] leading-[1.1] font-medium text-ink will-change-transform">{{ $band['words'][1] }}</p>
            </div>

            {{-- Small caps under the picture, as the frame sets it: the label
                 above names the work and this one names how it is made. --}}
            <p class="reveal text-fluid-sm font-medium uppercase tracking-[0.08em] text-ink">{{ $band['footnote'] }}</p>
        </div>
    </div>
</section>
