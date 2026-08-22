@php($band = config('site.joinery_page.wordmark'))
@php($partner = config('site.joinery_page.partner'))
@php($logo = $partner['logo'] && file_exists(public_path($partner['logo'])) ? $partner['logo'] : null)

{{--
    The frame's second band — a small centred label, then the two words either
    side of a picture — carrying the scroll animation from
    havenconstructions.com.au that the client asked for.

    Their markup says what it does, so this is built from the mechanism rather
    than a guess: the picture is written out at scale(0.2) with the words
    offset 50px from their places, and scroll drives all three back to rest.
    motion/scroll-gallery.js does the driving.

    THE LABEL IS THE ALWAN MARK RATHER THAN THE WORDS "ALWAN INTERIORS". The
    frame sets that line as type, but the mark was asked for on this page and
    the frame's layout has no other slot for it — this is the line that names
    the company. Without the file it falls back to the label as type.

    Progressive enhancement: the section is only tall enough to scroll through
    once `data-gallery-ready` is set, which the module sets and only if it is
    running. Without JavaScript the band is a normal-height section, the
    picture is at full size, the words are in place and the first picture
    shows — the arrangement the frame draws, which is what has to survive.
--}}
{{-- `group` because the module marks the SECTION and the classes that react
     to it sit on its children: a bare data-[…] variant reads the element's own
     attribute, so the track was checking itself, finding nothing, and staying
     its natural height — which left no travel and the picture frozen at a
     fifth of its size. --}}
<section data-scroll-gallery
         class="group/gallery bg-white py-[clamp(3.5rem,5.79vw,100px)] group-data-[gallery-ready]/gallery:py-0"
         style="--travel:{{ $band['travel'] }}svh">

    <div class="group-data-[gallery-ready]/gallery:h-[var(--travel)]" data-gallery-track>
        <div data-gallery-pin class="flex flex-col items-center justify-center gap-[clamp(2rem,3.7vw,64px)] group-data-[gallery-ready]/gallery:sticky group-data-[gallery-ready]/gallery:top-0 group-data-[gallery-ready]/gallery:h-[100svh]">

            <div class="reveal flex flex-col items-center">
                @if ($logo)
                    <img src="{{ \App\Support\Asset::versioned($logo) }}"
                         alt="{{ $partner['mark_alt'] ?? $partner['name'] }}"
                         loading="lazy" decoding="async"
                         class="w-full max-w-[clamp(9rem,14vw,220px)] object-contain">
                @else
                    <p class="text-fluid-sm font-semibold uppercase tracking-[0.12em] text-ink-muted">{{ $band['label'] }}</p>
                @endif
            </div>

            {{-- One row from sm: word, picture, word. Below it the three stack,
                 which is the only arrangement that leaves the picture a size
                 worth growing to on a phone. --}}
            {{-- The words out toward the gutters with the picture centred
                 between them, as the reference sets that row: justify-between
                 on three items puts the two words on the edges and leaves the
                 middle one where it is. Centred instead, the pair sat close in
                 around a picture that starts at a fifth of its size, and the
                 growth had nothing to grow into. --}}
            <div class="shell flex w-full flex-col items-center justify-center gap-[clamp(1.25rem,2.5vw,44px)] sm:flex-row sm:justify-between">
                <p data-gallery-word="0" class="display shrink-0 text-[clamp(1.75rem,3.2vw,56px)] leading-[1.1] text-ink will-change-transform">{{ $band['words'][0] }}</p>

                {{-- The frame the module scales. Its box is fixed and the
                     transform is what moves — scaling the box itself would
                     reflow the row on every frame and drag the words with it.
                     transform-origin is the centre, so it grows from where it
                     sits rather than out of one corner. --}}
                <div data-gallery-frame
                     class="relative aspect-[4/3] w-full max-w-[clamp(11rem,26vw,460px)] shrink-0 origin-center overflow-hidden bg-mist will-change-transform">
                    @foreach ($band['images'] as $image)
                        <figure data-gallery-slide
                                class="absolute inset-0 transition-opacity duration-700 {{ $loop->first ? 'opacity-100' : 'opacity-0' }}">
                            <img src="{{ \App\Support\Asset::versioned($image['src']) }}" alt="{{ $image['alt'] }}"
                                 loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async"
                                 class="absolute inset-0 h-full w-full object-cover">
                        </figure>
                    @endforeach
                </div>

                <p data-gallery-word="1" class="display shrink-0 text-[clamp(1.75rem,3.2vw,56px)] leading-[1.1] text-ink will-change-transform">{{ $band['words'][1] }}</p>
            </div>
        </div>
    </div>
</section>
