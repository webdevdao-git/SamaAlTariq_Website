@php($footer = config('site.footer'))
@php($nav = config('site.nav'))
@php($social = config('site.social'))

{{--
    Figma: frame 1226:1038, 1728×774, background #3FA7B3.
    Three top columns, then the wordmark lock-up across the bottom: SAMA AL
    TARIQ at 208px over BUILDING CONTRACTING LLC. at 49px with 0.72em tracking.
    Both are sized in vw so the lock-up always spans the page.
--}}
<footer class="overflow-hidden bg-teal pt-[clamp(2.5rem,4.63vw,80px)] text-white">
    <div class="shell">
        <div class="grid items-start gap-[clamp(2.5rem,4vw,68px)] lg:grid-cols-12">
            <div class="reveal flex flex-col gap-[clamp(1.25rem,2.14vw,37px)] lg:col-span-6">
                <img src="{{ asset('images/logo-mark.png') }}" alt="" width="540" height="462"
                     class="h-auto w-[clamp(44px,3.65vw,63px)]">
                <nav aria-label="Footer">
                    <ul class="flex flex-col">
                        @foreach ($nav as $item)
                            <li>
                                <a href="{{ $item['href'] }}"
                                   class="inline-block text-fluid-sm font-medium transition-opacity hover:opacity-70">{{ $item['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>

            <div class="reveal relative w-full max-w-[417px] lg:col-span-3 lg:col-start-8" style="transition-delay:80ms">
                <a href="{{ $footer['recent']['href'] }}" class="group block">
                    <span class="mb-[clamp(0.35rem,0.44vw,7.5px)] flex items-center gap-1.5">
                        <x-icon name="dot" class="text-white"/>
                        <span class="text-[clamp(10px,0.73vw,12.5px)] font-semibold">{{ $footer['recent']['label'] }}</span>
                    </span>
                    <span class="relative block aspect-[417/259] w-full overflow-hidden bg-white">
                        <img src="{{ asset($footer['recent']['image']) }}" alt="{{ $footer['recent']['alt'] }}"
                             loading="lazy" decoding="async"
                             class="h-full w-full object-cover transition-transform duration-[900ms] ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-[1.05]">
                    </span>
                    <x-icon name="diagonal-arrow"
                            class="absolute right-[6%] bottom-[-6%] w-[clamp(28px,2.95vw,51px)] text-white transition-transform duration-500 group-hover:-translate-y-1 group-hover:translate-x-1"/>
                </a>
            </div>

            <div class="reveal lg:col-span-2 lg:col-start-11 lg:justify-self-end" style="transition-delay:160ms">
                <ul class="flex flex-col gap-[clamp(0.5rem,0.86vw,15px)] lg:w-[clamp(120px,10.4vw,180px)]">
                    @foreach ($social as $s)
                        <li>
                            <a href="{{ $s['href'] }}" target="_blank" rel="noreferrer noopener"
                               class="group flex items-center justify-between gap-2 text-fluid-body transition-opacity hover:opacity-75">
                                {{ $s['label'] }}
                                <x-icon name="arrow-outward"
                                        class="w-[clamp(16px,1.39vw,24px)] transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5"/>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="mt-[clamp(2.5rem,7vw,120px)] pb-[clamp(1rem,1.5vw,26px)]">
            {{-- Sized by measurement (see motion/fit-text.js). The vw values are
                 only the no-JavaScript fallback, set low enough not to overflow
                 with a wider fallback face. --}}
            <p data-fit-text class="font-wordmark text-[10.5vw] leading-[0.78] font-semibold whitespace-nowrap">{{ Str::upper($footer['wordmark']) }}</p>
            <p data-fit-text class="mt-[clamp(0.15rem,0.35vw,6px)] font-wordmark text-[2.5vw] leading-none tracking-[0.72em] whitespace-nowrap">{{ Str::upper($footer['wordmark_sub']) }}</p>
        </div>
    </div>
</footer>
