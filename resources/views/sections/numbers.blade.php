@php($numbers = config('site.numbers'))

{{--
    By the Numbers — the figures from the 2026 business profile, on the night
    ground between the about section and the client strip.

    Each figure counts up from zero as it comes in (motion/count-up.js) and
    the hairline over it draws across; the markup carries the final figure,
    so without JavaScript the band simply reads as written.
--}}
<section class="numbers bg-night py-[clamp(3rem,5vw,80px)] text-white">
    <div class="shell">
        <div class="flex flex-col gap-[clamp(1.5rem,3vw,52px)] lg:flex-row lg:items-end lg:justify-between">
            <h2 class="editorial-heading text-fluid-h2 uppercase">
                <span data-split class="block">{{ $numbers['label'] }}</span>
            </h2>
            <p class="reveal max-w-[24ch] text-fluid-lead font-medium text-white/70">{{ $numbers['tagline'] }}</p>
        </div>

        <dl class="mt-[clamp(1.75rem,3.5vw,56px)] grid gap-x-[clamp(1.5rem,4vw,72px)] gap-y-[clamp(2rem,4vw,64px)] sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($numbers['items'] as $i => $item)
                <div class="numbers__cell">
                    <span aria-hidden="true" class="reveal-line block h-px w-full bg-white/20" style="transition-delay:{{ $i * 90 }}ms"></span>
                    <dt class="sr-only">{{ $item['label'] }}</dt>
                    <dd class="reveal pt-[clamp(1rem,1.6vw,28px)]" style="transition-delay:{{ 120 + $i * 90 }}ms">
                        <span data-count class="numbers__value block font-medium tracking-[-0.04em]">{{ $item['value'] }}</span>
                        <span class="mt-2 block max-w-[22ch] text-fluid-body text-white/70">{{ $item['label'] }}</span>
                    </dd>
                </div>
            @endforeach
        </dl>
    </div>
</section>
