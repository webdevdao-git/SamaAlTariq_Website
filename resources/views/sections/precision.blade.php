@php($precision = config('site.precision'))

{{--
    Figma: frame 1226:1150, 1728×922, background #161616.
    A faint hairline grid, a tower photo centred at 64% opacity, PRECISION at
    128px pinned bottom-left, and a copy block in the right column.
--}}
<section class="relative isolate overflow-hidden bg-night py-[clamp(3rem,5.79vw,100px)]">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0">
        <span class="absolute inset-y-0 left-[30.3%] w-px bg-white/10"></span>
        <span class="absolute inset-y-0 left-[71.5%] w-px bg-white/10"></span>
        <span class="absolute inset-x-0 top-[85.3%] h-px bg-white/10"></span>
    </div>

    <div aria-hidden="true"
         class="pointer-events-none absolute top-[13.3%] left-1/2 h-[72%] w-[clamp(180px,26.4vw,456px)] -translate-x-1/2 opacity-64">
        <img src="{{ asset($precision['image']) }}" alt="" loading="lazy" decoding="async"
             class="h-full w-full object-cover">
    </div>

    <div class="shell relative">
        <div class="grid items-end gap-[clamp(2.5rem,4vw,68px)] lg:min-h-[clamp(360px,42vw,722px)] lg:grid-cols-[1fr_498px]">
            <h2 class="reveal display text-fluid-mega leading-[0.7] uppercase text-white">{{ $precision['word'] }}</h2>

            <div class="reveal flex flex-col gap-[clamp(1rem,1.39vw,24px)] lg:mb-[clamp(2rem,10vw,172px)]" style="transition-delay:120ms">
                <p class="max-w-[423px] text-fluid-lead font-bold text-white">{{ $precision['heading'] }}</p>
                <p class="max-w-[423px] text-fluid-lead text-white/60">{{ $precision['body'] }}</p>
                <a href="{{ $precision['cta']['href'] }}" class="pill group w-fit">
                    {{ $precision['cta']['label'] }}
                    <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                </a>
            </div>
        </div>
    </div>
</section>
