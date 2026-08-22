@php($band = config('site.joinery_page.wordmark'))
@php($partner = config('site.joinery_page.partner'))
@php($logo = $partner['logo'] && file_exists(public_path($partner['logo'])) ? $partner['logo'] : null)

{{--
    Figma 1803:2, y1536–2300, plus the four frames storyboarded beside it
    (1846:1564–1566): the two words either side of a picture, a label above and
    another below — and the picture grows through four photographs until it
    fills the screen.

    THE BOX IS LAID OUT AT THE LAST STAGE AND SCALED DOWN TO THE FIRST. The
    file's stages are 522x362, 723x502, 1181x820 and 1728x980, so the frame is
    the full 1728x980 and starts at 522/1728 — 0.302. Growing it the other way,
    by animating width, would reflow the row on every frame and drag the words
    with it; a transform stays on the compositor and moves nothing.

    The picture is centred behind the type rather than between the words in a
    row: at 0.302 it sits exactly where the frame draws it, and at 1 it covers
    the band, which is what the last storyboard frame shows. The words and the
    two labels fade out as it passes under them — that frame carries no type.

    Progressive enhancement: without JavaScript, or under prefers-reduced-
    motion, the band is a normal-height section with the picture at its
    in-frame size, the type in place and the first photograph showing.
--}}
{{-- overflow-CLIP, not hidden, and this is load-bearing: `hidden` makes this
     section a scrolling box, which becomes the sticky child's scrollport and
     pins it to a container that never scrolls — the pin silently stops pinning
     and the picture scrolls off the top mid-growth. `clip` cuts the overflow
     without creating that box. The about-page hero carries the same note for
     the same reason. --}}
<section data-scroll-gallery="{{ $band['scale_from'] }}"
         class="group/gallery relative overflow-clip bg-white py-[clamp(3.5rem,5.79vw,100px)] group-data-[gallery-ready]/gallery:py-0"
         style="--travel:{{ $band['travel'] }}svh">

    <div class="group-data-[gallery-ready]/gallery:h-[var(--travel)]" data-gallery-track>
        <div data-gallery-pin
             class="relative flex flex-col items-center justify-center gap-[clamp(1.5rem,2.8vw,48px)] group-data-[gallery-ready]/gallery:sticky group-data-[gallery-ready]/gallery:top-0 group-data-[gallery-ready]/gallery:h-[100svh]">

            {{-- The picture. Absolutely centred so the type around it keeps its
                 own places while this grows through them; capped at the
                 frame's 1728 so it never outruns the design's own width. --}}
            <div data-gallery-frame
                 class="pointer-events-none absolute top-1/2 left-1/2 aspect-[1728/980] w-[min(100vw,1728px)] max-w-none origin-center -translate-x-1/2 -translate-y-1/2 overflow-hidden bg-mist will-change-transform group-data-[gallery-ready]/gallery:pointer-events-auto">
                @foreach ($band['images'] as $image)
                    <figure data-gallery-slide
                            class="absolute inset-0 transition-opacity duration-700 {{ $loop->first ? 'opacity-100' : 'opacity-0' }}">
                        <img src="{{ \App\Support\Asset::versioned($image['src']) }}" alt="{{ $image['alt'] }}"
                             loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async"
                             class="absolute inset-0 h-full w-full object-cover">
                    </figure>
                @endforeach
            </div>

            <div data-gallery-fade class="reveal relative z-10 flex flex-col items-center gap-[clamp(0.5rem,0.8vw,14px)]">
                {{-- THE MARK, WHICH THE FRAME DOES NOT DRAW. It was asked for
                     on this page twice, and the frame's own label line is the
                     line that names the company, so it sits there with the
                     label beneath. --}}
                @if ($logo)
                    <img src="{{ \App\Support\Asset::versioned($logo) }}"
                         alt="{{ $partner['mark_alt'] ?? $partner['name'] }}"
                         loading="lazy" decoding="async"
                         class="w-full max-w-[clamp(7.5rem,11vw,180px)] object-contain">
                @endif

                <p class="text-fluid-sm font-medium text-ink">{{ $band['label'] }}</p>
            </div>

            {{-- The words out at the gutters with the picture between them.
                 They are not in a row WITH it — the picture is behind — so the
                 row keeps a hole in the middle the width of the frame's own
                 522, and the growth passes through it. --}}
            {{-- The row reserves the picture's OWN height at its starting
                 size, which is what keeps the label above it and the footnote
                 below rather than on top of it: the picture is out of flow, so
                 without this the column collapses to three lines of type and
                 the photograph is centred straight through them. 0.302 of the
                 box's width, over the box's ratio. --}}
            <div data-gallery-fade
                 class="shell relative z-10 flex w-full flex-col items-center justify-center gap-[clamp(1.25rem,2.5vw,44px)] sm:h-[calc(min(100vw,1728px)*0.302/1.7633)] sm:flex-row sm:justify-between">
                <p data-gallery-word="0" class="shrink-0 text-[clamp(1.75rem,3.7vw,64px)] leading-[1.1] font-medium text-ink will-change-transform">{{ $band['words'][0] }}</p>

                <span aria-hidden="true" class="hidden shrink-0 sm:block sm:w-[30.2vw] sm:max-w-[522px]"></span>

                <p data-gallery-word="1" class="shrink-0 text-[clamp(1.75rem,3.7vw,64px)] leading-[1.1] font-medium text-ink will-change-transform">{{ $band['words'][1] }}</p>
            </div>

            <p data-gallery-fade class="reveal relative z-10 text-fluid-sm font-medium uppercase tracking-[0.08em] text-ink">{{ $band['footnote'] }}</p>
        </div>
    </div>
</section>
