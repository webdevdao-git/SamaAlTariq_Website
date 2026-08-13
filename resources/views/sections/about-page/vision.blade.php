@php($vision = config('site.about_page.vision'))

{{--
    Figma: frame 1386:608, 1728×912, 80px padding all round, on #E0F1F3.

    The mirror of the block above — 561px of copy on the left gutter, a 968×752
    image on the right one — so the two read as a pair rather than as two
    unrelated rows. Same 561 + 40 + 968 column split, reversed.

    The pale ground is the only place on the page that is not white, and it is
    what separates this block from the intro above it; there is no rule between
    them.

    The heading is set in the sans, not the display serif — 32px semibold over
    two lines at 44px, which is the 561×88 box in the file. It is the one
    heading on the page that is not Juana Alt, so it is written out here rather
    than reaching for .display.

    The copy column is exactly as tall as the image and its contents are pinned
    to the two ends: heading and paragraph at the top, the button on the
    baseline of the photo (Figma y 708 + 44 = 752). `justify-between` on a
    full-height column is what holds that.
--}}
<section class="bg-haze py-[clamp(3rem,4.63vw,80px)]">
    <div class="shell">
        <div class="grid gap-[clamp(2rem,2.31vw,40px)] md:grid-cols-[561fr_968fr]">

            {{-- The reveals sit on the children rather than on the column, so
                 the heading, the paragraph and the button arrive in that order
                 instead of the three fading in as one slab. --}}
            <div class="flex flex-col justify-between gap-[clamp(2rem,4.63vw,80px)]">
                <div>
                    <h2 class="text-[clamp(1.375rem,1.85vw,32px)] font-semibold leading-[1.375] text-ink">
                        @foreach ($vision['heading'] as $i => $line)
                            <span data-split data-split-by="word" data-split-delay="{{ $i * 220 }}"
                                  class="block">{{ $line }}</span>
                        @endforeach
                    </h2>

                    <p class="reveal mt-[clamp(1rem,1.39vw,24px)] text-fluid-body font-medium leading-[1.35] text-ink"
                       style="transition-delay:140ms">
                        {{ $vision['body'] }}
                    </p>
                </div>

                <a href="{{ \App\Support\Nav::href($vision['cta']['href']) }}" class="reveal pill group w-fit" style="transition-delay:280ms">
                    {{ $vision['cta']['label'] }}
                    <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                </a>
            </div>

            <div>
                <div class="relative aspect-[968/752] w-full overflow-hidden">
                    <img src="{{ asset($vision['image']) }}" alt="{{ $vision['alt'] }}"
                         loading="lazy" decoding="async"
                         class="reveal-media absolute inset-0 h-full w-full object-cover"
                         style="transition-delay:100ms">
                </div>
            </div>
        </div>
    </div>
</section>
