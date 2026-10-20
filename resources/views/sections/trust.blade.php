@php
    $stats = config('site.story.about.stats');
    $logos = config('site.clients.logos');
@endphp

{{--
    The first thing under the hero, in two clear parts: the figures that
    matter, four equal columns on white; then the clients, their own row on
    the pale ground with a short label beside the running marks.
--}}
<section class="trust">
    <div class="shell">
        <dl class="trust__stats">
            @foreach ($stats as $stat)
                <div class="reveal" style="transition-delay:{{ $loop->index * 80 }}ms">
                    <dd>{{ $stat['value'] }}</dd>
                    <dt>{{ $stat['label'] }}</dt>
                </div>
            @endforeach
        </dl>
    </div>

    <div class="trust__clients">
        <div class="shell trust__clients-row">
            <p class="trust__label">Trusted By<span>Developers, hotels &amp; government across the UAE</span></p>
            <div class="marquee trust__logos" style="--marquee-duration:{{ count($logos) * 3.2 }}s">
                <div class="marquee__track">
                    @foreach ([false, true] as $isClone)
                        <ul class="flex shrink-0 items-center gap-x-[clamp(2rem,4vw,64px)] pr-[clamp(2rem,4vw,64px)]" @if ($isClone) aria-hidden="true" @endif>
                            @foreach ($logos as $logo)
                                <li class="flex h-[clamp(32px,2.8vw,48px)] w-[clamp(90px,7.5vw,128px)] shrink-0 items-center justify-center">
                                    <img src="{{ asset($logo['src']) }}" alt="{{ $isClone ? '' : $logo['name'] }}" @if ($isClone) loading="lazy" @endif decoding="async"
                                         class="max-h-full max-w-full object-contain opacity-70 grayscale transition duration-300 hover:opacity-100 hover:grayscale-0">
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
