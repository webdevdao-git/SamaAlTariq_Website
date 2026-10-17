@php($numbers = config('site.numbers'))

{{--
    The profile's figures, set the About page's way rather than the landing
    page's: a tall picture on the left and the six figures running down the
    right as an index, each figure counting up as its row arrives.
--}}
<section class="about-facts bg-mist py-[clamp(3rem,5vw,88px)]">
    <div class="shell grid items-start gap-[clamp(2rem,4vw,72px)] lg:grid-cols-[5fr_7fr]">
        <div class="about-facts__aside">
            <p class="reveal text-fluid-label font-medium text-teal">{{ $numbers['label'] }}</p>
            <p class="reveal mt-3 display text-fluid-h2 leading-[1.15] text-ink">{{ $numbers['tagline'] }}</p>
            <div class="about-facts__pic mt-[clamp(1.5rem,3vw,48px)]">
                {{-- WASL HQ, the project the profile opens its portfolio with. --}}
                <img src="{{ \App\Support\Asset::versioned('images/projects/wasl-properties-hq/hero.webp') }}" alt="WASL Properties HQ" loading="lazy" decoding="async">
            </div>
        </div>

        <dl class="about-facts__list">
            @foreach ($numbers['items'] as $item)
                <div class="about-facts__row">
                    <dt class="about-facts__label">{{ $item['label'] }}</dt>
                    <dd class="about-facts__value"><span data-count>{{ $item['value'] }}</span></dd>
                </div>
            @endforeach
        </dl>
    </div>
</section>
