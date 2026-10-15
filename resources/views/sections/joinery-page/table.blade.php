@php
    $band = config('site.joinery_page.wordmark');
    $pictures = $band['images'];
@endphp

{{--
    A piece made to measure, in 3D: the walnut table from motion/joinery-table.js,
    assembled part by part as the band scrolls in and turning under the
    pointer. The copy is the joinery page's own wordmark band — label, the two
    words and the footnote — so nothing new is said here. The workshop
    photographs show until (or unless) the model can be drawn.
--}}
<section class="bg-mist py-[clamp(3rem,5vw,80px)]">
    <div class="shell grid items-center gap-[clamp(2rem,4vw,64px)] lg:grid-cols-[4fr_8fr]">
        <div class="flex flex-col gap-[clamp(0.75rem,1.2vw,20px)]">
            <p class="reveal text-fluid-sm font-semibold uppercase tracking-[0.14em] text-teal">{{ $band['label'] }}</p>
            <h2 class="editorial-heading text-fluid-h2 uppercase text-ink">
                @foreach ($band['words'] as $i => $word)
                    <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $word }}</span>
                @endforeach
            </h2>
            <p class="reveal text-fluid-body font-medium text-ink-muted">{{ $band['footnote'] }}</p>
        </div>

        <div class="jtable" data-jtable>
            <canvas class="jtable__canvas" aria-hidden="true"></canvas>
            <ul class="jtable__fallback grid grid-cols-2 gap-[clamp(0.5rem,1vw,16px)]">
                @foreach ($pictures as $picture)
                    <li class="relative aspect-[4/3] overflow-hidden rounded-[clamp(6px,0.6vw,10px)]">
                        <img src="{{ asset($picture['src']) }}" alt="{{ $picture['alt'] }}" loading="lazy" decoding="async" class="h-full w-full object-cover">
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
