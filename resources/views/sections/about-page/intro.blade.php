@php($intro = config('site.about_page.intro'))

{{--
    Figma: frame 1377:26, 1728×912, 80px padding all round.

    A 968×752 image on the left gutter and a 561px paragraph on the right one,
    40px apart — 968 + 40 + 561 is the 1569px content column exactly, so the two
    tracks are declared in those proportions rather than as halves.

    The paragraph's last line sits on the foot of the image, not its top: the
    design bottom-aligns the two (text y 643 + 189 = 832 = image y 80 + 752).
    That is what `items-end` is doing, and it is the whole alignment of this
    block — get it wrong and the copy floats in the middle of the photo.

    Type is the 20px body tier at 1.35 leading, measured off the Figma box:
    189px over the seven lines that 337 characters make in a 561px measure.
--}}
<section class="bg-white py-[clamp(3rem,4.63vw,80px)]">
    <div class="shell">
        <div class="grid items-end gap-[clamp(2rem,2.31vw,40px)] md:grid-cols-[968fr_561fr]">
            {{-- The frame carries no reveal of its own: it has to hold its
                 place in the row so the paragraph beside it stays bottom-aligned
                 to the photo's foot. Only the picture inside it moves. --}}
            <div>
                <div class="relative aspect-[968/752] w-full overflow-hidden">
                    <img src="{{ asset($intro['image']) }}" alt="{{ $intro['alt'] }}"
                         loading="lazy" decoding="async"
                         class="reveal-media absolute inset-0 h-full w-full object-cover">
                </div>
            </div>

            {{-- The company's own name opens the paragraph in the semibold cut
                 and the sentence continues in the regular one, as in the
                 design — one paragraph, two weights, not two paragraphs. --}}
            <p class="reveal text-fluid-body leading-[1.35] text-ink" style="transition-delay:160ms">
                <strong class="font-semibold">{{ $intro['lead'] }}</strong> {{ $intro['body'] }}
            </p>
        </div>
    </div>
</section>
