@php
    $stats = config('site.story.about.stats');
    $logos = config('site.clients.logos');
@endphp

{{--
    The first thing under the hero: who has trusted the company and the few
    figures that matter, before any of the story. Premium contractors' sites
    put the names a visitor recognises up front; this is that band, kept thin.
--}}
<section class="trust">
    <div class="shell trust__grid">
        <dl class="trust__stats">
            @foreach ($stats as $stat)
                <div class="reveal" style="transition-delay:{{ $loop->index * 70 }}ms">
                    <dd>{{ $stat['value'] }}</dd>
                    <dt>{{ $stat['label'] }}</dt>
                </div>
            @endforeach
        </dl>
        <p class="trust__label">Trusted by developers, hotels and government across the UAE</p>
    </div>
    <div class="marquee trust__logos" style="--marquee-duration:{{ count($logos) * 3.2 }}s">
        <div class="marquee__track">
            @foreach ([false, true] as $isClone)
                <ul class="flex shrink-0 items-center gap-x-[clamp(2rem,4vw,72px)] pr-[clamp(2rem,4vw,72px)]" @if ($isClone) aria-hidden="true" @endif>
                    @foreach ($logos as $logo)
                        <li class="flex h-[clamp(34px,3vw,52px)] w-[clamp(96px,8vw,140px)] shrink-0 items-center justify-center">
                            <img src="{{ asset($logo['src']) }}" alt="{{ $isClone ? '' : $logo['name'] }}" @if ($isClone) loading="lazy" @endif decoding="async"
                                 class="max-h-full max-w-full object-contain opacity-75 grayscale transition duration-300 hover:opacity-100 hover:grayscale-0">
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </div>
</section>
