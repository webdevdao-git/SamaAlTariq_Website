@php($delivery = config('site.services_page.delivery'))

{{-- Two ways in: the first card on teal, the second on mist. --}}
<section class="bg-white py-[clamp(3rem,6vw,104px)]">
    <div class="shell">
        <p class="reveal text-fluid-sm font-semibold uppercase tracking-[0.14em] text-teal">{{ $delivery['label'] }}</p>

        <div class="mt-[clamp(1.25rem,2.5vw,40px)] grid gap-[clamp(1rem,1.6vw,28px)] md:grid-cols-2">
            @foreach ($delivery['models'] as $i => $model)
                <article class="dmodel reveal {{ $i === 0 ? 'dmodel--teal' : '' }}" style="transition-delay:{{ $i * 120 }}ms">
                    <span class="dmodel__num">0{{ $i + 1 }}</span>
                    <h2 class="display text-fluid-section leading-[1.05]">{{ $model['title'] }}</h2>
                    <p class="dmodel__for">{{ $model['for'] }}</p>
                    <p class="dmodel__body">{{ $model['body'] }}</p>
                    <ul class="dmodel__points">
                        @foreach ($model['points'] as $point)
                            <li>{{ $point }}</li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </div>
</section>
