@props(['title' => null, 'subtitle' => null])

<section {{ $attributes->merge(['class' => 'rounded-2xl border border-portal-ink/10 bg-white p-[clamp(1.25rem,2.2vw,32px)]']) }}>
    @if ($title)
        <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="font-wordmark text-[clamp(1.25rem,1.7vw,26px)] text-portal-ink">{{ $title }}</h2>
                @if ($subtitle)
                    <p class="mt-1 text-[15px] text-ink-muted">{{ $subtitle }}</p>
                @endif
            </div>
            {{ $action ?? '' }}
        </div>
    @endif

    {{ $slot }}
</section>
