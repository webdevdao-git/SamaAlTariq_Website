@php($precision = config('site.precision'))

{{--
    Figma: frame 1226:1150, 1728×922, background #161616.
    A faint hairline grid, a tower photo centred at 64% opacity, PRECISION at
    128px pinned bottom-left, and a copy block in the right column.
--}}
<section class="relative isolate overflow-hidden bg-night">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0">
        <span class="absolute inset-y-0 left-[30.3%] w-px bg-white/10"></span>
        <span class="absolute inset-y-0 left-[71.5%] w-px bg-white/10"></span>
        <span class="absolute inset-x-0 top-[85.3%] h-px bg-white/10"></span>
    </div>

    <div aria-hidden="true"
         class="pointer-events-none absolute top-0 left-1/2 h-[clamp(380px,42vw,720px)] w-[clamp(180px,26.4vw,456px)] -translate-x-1/2 opacity-[0.64]">
        <img src="{{ asset($precision['image']) }}" alt="" loading="lazy" decoding="async"
             class="h-full w-full object-cover">
    </div>

    <div class="shell relative">
        <div class="flex min-h-[clamp(520px,53.36vw,922px)] flex-col justify-between gap-[clamp(5rem,12vw,14rem)] py-[clamp(2.5rem,4.63vw,80px)]">
            <div class="grid gap-6 lg:grid-cols-12">
                <div class="reveal z-10 flex flex-col gap-[clamp(0.875rem,1.16vw,20px)] lg:col-span-4 lg:col-start-9" style="transition-delay:120ms">
                    <p class="max-w-[423px] text-fluid-lead font-bold text-white">{{ $precision['heading'] }}</p>
                    <p class="max-w-[423px] text-fluid-lead text-white/60">{{ $precision['body'] }}</p>
                    <a href="{{ $precision['cta']['href'] }}" class="pill group w-fit">
                        {{ $precision['cta']['label'] }}
                        <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                    </a>
                </div>
            </div>

            <h2 class="reveal display z-10 text-fluid-mega leading-[0.82] uppercase text-white">{{ $precision['word'] }}</h2>
        </div>
    </div>
</section>
