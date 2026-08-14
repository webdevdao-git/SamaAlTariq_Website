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
--}}
<section class="bg-white py-[clamp(2.5rem,4.63vw,80px)]">
    <div class="shell">
        <div class="grid gap-[clamp(2rem,4.63vw,80px)] lg:grid-cols-[549fr_939fr]">

            {{-- 24/33 Manrope Bold, the frame's own measure. --}}
            <p class="reveal text-[clamp(1.125rem,1.389vw,24px)] font-bold leading-[1.375] text-ink">
                {{ $page['lead'] }}
            </p>

            <div class="grid grid-cols-2 gap-[clamp(1.5rem,3.7vw,64px)] sm:grid-cols-3">
                @for ($i = 1; $i <= $page['tiles']; $i++)
                    <div class="reveal-rise relative aspect-[270/240] w-full overflow-hidden"
                         style="transition-delay:{{ ($i - 1) * 40 }}ms">
                        <img src="{{ asset('images/projects/' . $slug . '/g' . $i . '.webp') }}"
                             alt="" loading="lazy" decoding="async"
                             class="h-full w-full object-cover">
                    </div>
                @endfor
            </div>
        </div>
    </div>
</section>
