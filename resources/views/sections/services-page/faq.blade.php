@php($faq = config('site.services_page.faq'))

{{-- Native <details>, so it opens without script; the answer's height is
     animated in CSS where the browser supports interpolate-size. --}}
<section class="bg-white py-[clamp(3rem,6vw,104px)]">
    <div class="shell grid gap-[clamp(1.5rem,4vw,72px)] lg:grid-cols-[0.6fr_1.4fr]">
        <div>
            <p class="reveal text-fluid-sm font-semibold uppercase tracking-[0.14em] text-teal">{{ $faq['label'] }}</p>
            <h2 class="mt-3 display text-fluid-section leading-[1.05] text-ink"><span data-split class="block">Asked</span><span data-split data-split-delay="110" class="block">Often</span></h2>
        </div>

        <div class="faq">
            @foreach ($faq['items'] as $item)
                <details class="faq__item reveal" @if ($loop->first) open @endif>
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
