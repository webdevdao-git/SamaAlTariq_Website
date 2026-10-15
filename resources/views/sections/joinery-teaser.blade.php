@php
    $joinery = config('site.joinery_page');
    $partner = $joinery['partner'];
    $pictures = $joinery['wordmark']['images'];
@endphp

{{--
    A short pointer to the joinery side of the business: Alwan Interiors, the
    workshop behind the fit-outs. Copy and pictures are the Joinery page's own
    (config/site.php joinery_page), so the two never say different things;
    the section only sends the visitor there.
--}}
<section id="joinery" class="bg-mist">
    <div class="py-[clamp(3rem,5vw,80px)]">
    <div class="shell grid items-center gap-[clamp(2rem,4vw,64px)] lg:grid-cols-[5fr_7fr]">
        <div class="flex flex-col gap-[clamp(1rem,1.6vw,24px)]">
            <img src="{{ asset($partner['logo']) }}" alt="{{ $partner['mark_alt'] }}"
                 loading="lazy" decoding="async" class="reveal h-[clamp(36px,3.2vw,52px)] w-auto self-start">
            <p class="reveal text-fluid-sm font-semibold uppercase tracking-[0.14em] text-teal">{{ $joinery['wordmark']['label'] }}</p>
            <h2 class="reveal display text-fluid-h2 leading-[1.15] text-ink">{{ implode(' ', $joinery['hero']['words']) }}</h2>
            <p class="reveal max-w-[44ch] text-fluid-body text-ink-muted">{{ $joinery['hero']['summary'] }}</p>
            <a href="{{ route('joinery') }}" class="reveal pill group mt-2 self-start">
                Explore Joinery
                <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
            </a>
        </div>

        {{--
            A piece of joinery, made in front of the visitor: a walnut dining
            table modelled in Three.js (motion/joinery-table.js), its legs
            rising and its top coming down onto them as the section scrolls
            in, then turning under the pointer. The workshop photographs are
            what shows until — or unless — it can be drawn.
        --}}
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
    </div>
</section>
