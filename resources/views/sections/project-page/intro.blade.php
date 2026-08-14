{{--
    Figma 1472:1371, 1728x1008 with 80 all round.

    The lead paragraph on the left gutter and a 3x3 grid of the project's
    photographs beside it: 549 + 80 + 939 is the 1568 content column exactly,
    so the two tracks are declared in those proportions rather than as a third
    and two thirds.

    The grid's own numbers are 270x240 tiles with 64 between them, in both
    directions — 270*3 + 64*2 = 938 and the same arithmetic down. Tiles are
    exported at that box's 2x and cropped to it, so nothing is left to cover.

    A project with fewer photographs draws fewer tiles rather than repeating
    one: two of the shoots came in with four pictures rather than ten.

    EACH TILE OPENS FULL SCREEN, which is the reference's gallery behaviour and
    all that is taken from it — the grid itself is untouched, and the overlay
    only exists once something is clicked. The tiles are anchors to the large
    file, so the behaviour degrades to opening the photograph on its own rather
    than to nothing; motion/lightbox.js intercepts the click and keeps the set
    navigable from where the visitor is.
--}}
<section class="bg-white py-[clamp(2.5rem,4.63vw,80px)]">
    <div class="shell">
        <div class="grid gap-[clamp(2rem,4.63vw,80px)] lg:grid-cols-[549fr_939fr]">

            {{-- 24/33 Manrope Bold, the frame's own measure. --}}
            <p class="reveal text-[clamp(1.125rem,1.389vw,24px)] font-bold leading-[1.375] text-ink">
                {{ $page['lead'] }}
            </p>

            <div data-lightbox class="grid grid-cols-2 gap-[clamp(1.5rem,3.7vw,64px)] sm:grid-cols-3">
                @for ($i = 1; $i <= $page['tiles']; $i++)
                    <a data-lightbox-item
                       href="{{ asset('images/projects/' . $slug . '/l' . $i . '.webp') }}"
                       data-lightbox-alt="{{ $project['title'] }} — photograph {{ $i }}"
                       aria-label="Open photograph {{ $i }} of {{ $page['tiles'] }}"
                       class="reveal-rise relative aspect-[270/240] w-full overflow-hidden focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink"
                       style="transition-delay:{{ ($i - 1) * 40 }}ms">
                        <img src="{{ asset('images/projects/' . $slug . '/g' . $i . '.webp') }}"
                             alt="" loading="lazy" decoding="async"
                             class="h-full w-full object-cover">
                    </a>
                @endfor

                {{-- The photographs past the frame's nine. Hidden, so the grid
                     is exactly what the frame draws, but present, so the
                     lightbox steps through the whole shoot rather than
                     stopping at the ninth. --}}
                @for ($i = $page['tiles'] + 1; $i <= $photographs; $i++)
                    <a data-lightbox-item class="hidden"
                       href="{{ asset('images/projects/' . $slug . '/l' . $i . '.webp') }}"
                       data-lightbox-alt="{{ $project['title'] }} — photograph {{ $i }}"
                       tabindex="-1" aria-hidden="true">Photograph {{ $i }}</a>
                @endfor
            </div>
        </div>
    </div>
</section>
