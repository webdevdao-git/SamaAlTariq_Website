@php($approach = config('site.about_page.approach'))
@php($images = $approach['images'])

{{--
    Figma: frame 1386:621, 1728×1484.

    Three bands, all hung off the 79px gutter:

    1. Label + 48px heading. The heading starts where the label ends plus 64px
       (Figma x 181 → 245), not on a column, which is the same construction the
       landing page's "Who We Are" row uses — so this is a flex row, not a grid.

    2. The collage, 1570×778. Its geometry is the reason this block is absolute
       rather than a grid: a 1394-wide image centred in the column with two
       smaller ones overhanging it, one at each side, and neither aligned to
       anything the other sits on. Every offset below is that Figma box divided
       by 1570 or 778, so the composition holds at any width instead of only at
       1728. The mark is centred on the page (Figma x 864 = 1728/2), which is
       also dead centre of the main image, so it hangs off that.

       The composition holds on a phone too — it is the reference's own
       arrangement and the thing worth keeping — but the frame turns portrait
       there, 3/4 against the file's 1570/778. Every offset is a percentage of
       that frame, so the same three boxes come out tall rather than wide: the
       main picture takes a portrait crop of itself and the two overhanging
       pieces stand up beside it instead of sitting as 90px slivers. Their
       proportions are the frame's throughout.

    3. Copy in the right 910px, starting 660px into the column — not half, which
       is why the tracks are declared in those proportions.
--}}
<section class="bg-white pt-[clamp(3rem,4.63vw,80px)] pb-[clamp(3.5rem,5.6vw,97px)]">
    <div class="shell">

        {{-- 1. Label + heading --}}
        <div class="reveal flex flex-col gap-[clamp(1rem,3.7vw,64px)] md:flex-row md:items-start">
            <p class="shrink-0 text-fluid-label font-medium text-teal">{{ $approach['label'] }}</p>
            <h2 data-split data-split-by="word"
                class="display max-w-[700px] text-fluid-h2 leading-[1.3] text-ink">{{ $approach['heading'] }}</h2>
        </div>

        {{-- 2. Collage --}}
        {{-- No reveal on the collage frame: its three boxes are positioned
             against each other to the percent, so the frame must not move. The
             pictures settle inside, back to front, which is also the order the
             composition reads in. --}}
        <div class="relative mt-[clamp(2.5rem,4.63vw,80px)] aspect-[3/4] sm:aspect-[4/3] md:aspect-[1570/778]">

            {{-- Main image: 1394 wide, centred in the 1570 column. --}}
            <div class="absolute inset-y-0 left-[5.605%] h-full w-[88.79%] overflow-hidden">
                {{-- The collage takes the reference's own parallax device,
                     to its numbers: each picture is laid out 140% of its frame's
                     height and hung at -20%, so it carries 20% of slack above
                     and below inside a frame that clips it and never moves.
                     Scrolling slides the picture through that slack.

                     The frames are untouched — same box, same ratio, same place
                     on the grid. What changes is how much of each photograph
                     the frame shows, which is the trade the technique makes.

                     They keep .reveal-media for its fade; the drift owns the
                     transform from the first frame, so the scale half of that
                     reveal never runs on these three. --}}
                <img data-drift src="{{ asset($images['main']['src']) }}" alt="{{ $images['main']['alt'] }}"
                     loading="lazy" decoding="async"
                     class="reveal-media absolute inset-x-0 -top-[20%] h-[140%] w-full object-cover">

                {{-- 46×42 over the top of the photo, on the page's centre line. --}}
                {{-- No reveal class here: the mark is positioned with its own
                     -translate-x-1/2, which .reveal-media's transform would
                     overwrite and knock it off centre. It arrives with the
                     photograph it sits on, which is the right moment anyway. --}}
                <img src="{{ asset('images/logo-mark.png') }}" alt="" width="540" height="462" aria-hidden="true"
                     class="absolute top-[5.5%] left-1/2 h-auto w-[3.3%] min-w-[28px] -translate-x-1/2">
            </div>

            {{-- Overhangs the main image at the left gutter... --}}
            <div class="absolute top-[37.92%] left-0 h-[36.89%] w-[24.39%] overflow-hidden">
                <img data-drift src="{{ asset($images['left']['src']) }}" alt="{{ $images['left']['alt'] }}"
                     loading="lazy" decoding="async"
                     class="reveal-media absolute inset-x-0 -top-[20%] h-[140%] w-full object-cover"
                     style="transition-delay:130ms">
            </div>

            {{-- ...and at the right one, lower down. --}}
            <div class="absolute top-1/2 left-[76.94%] h-[28.41%] w-[23.06%] overflow-hidden">
                <img data-drift src="{{ asset($images['right']['src']) }}" alt="{{ $images['right']['alt'] }}"
                     loading="lazy" decoding="async"
                     class="reveal-media absolute inset-x-0 -top-[20%] h-[140%] w-full object-cover"
                     style="transition-delay:260ms">
            </div>
        </div>

        {{-- 3. Copy --}}
        {{-- No column gap: in the file the copy starts 660px into the column
             and runs 910 to the gutter, and 660 + 910 is the column itself. A
             gap here would be subtracted from both tracks and narrow the
             measure, which costs the paragraph an extra line. --}}
        <div class="mt-[clamp(2.5rem,4.63vw,80px)] grid gap-y-[clamp(1.5rem,2.31vw,40px)] md:grid-cols-[660fr_910fr]">
            <div class="hidden md:block"></div>

            <div>
                <p class="reveal text-fluid-lead font-medium leading-[1.375] text-ink">{{ $approach['body'] }}</p>

                <a href="{{ \App\Support\Nav::href($approach['cta']['href']) }}"
                   class="reveal pill group mt-[clamp(1.5rem,2.31vw,40px)] w-fit" style="transition-delay:140ms">
                    {{ $approach['cta']['label'] }}
                    <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                </a>
            </div>
        </div>
    </div>
</section>
