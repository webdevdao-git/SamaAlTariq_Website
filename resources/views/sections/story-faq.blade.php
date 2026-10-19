{{-- A questions card for <x-story>. Takes `items` ({q, a}) and `heading` (lines). --}}
<section class="story__card story__card--last">
    <div class="grid gap-[clamp(1.5rem,4vw,72px)] lg:grid-cols-[0.6fr_1.4fr]">
        <h3 class="story__big display">
            @foreach ($heading as $line)
                <span class="block">{{ $line }}</span>
            @endforeach
        </h3>
        <div class="faq">
            @foreach ($items as $item)
                <details class="faq__item" @if ($loop->first) open @endif>
                    <summary class="faq__q">
                        <span>{{ $item['q'] }}</span>
                        <span aria-hidden="true" class="faq__sign"></span>
                    </summary>
                    <p class="faq__a">{{ $item['a'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>
