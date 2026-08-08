@php($hero = config('site.hero'))
@php($nav = config('site.nav'))
@php($social = config('site.social'))

{{--
    Figma: frame 1195:3, 1728×1117.
    Photo fills the frame under two stacked gradients, the header sits at the
    top, a hairline rule separates a three-column intro row, and the display
    type occupies the lower third.

    Motion: the section sinks at a quarter of scroll speed while the photo
    pushes in to 1.1×, and the display words rise out of line masks.
--}}
<section id="top" class="relative isolate min-h-[100svh] overflow-hidden bg-night">
    <div data-hero class="relative flex min-h-[100svh] flex-col" style="will-change:transform">

        <div data-hero-media class="absolute inset-0 -z-10" style="will-change:transform;transform-origin:center">
            <img src="{{ asset($hero['image']) }}" alt="{{ $hero['alt'] }}"
                 fetchpriority="high" decoding="async"
                 class="absolute inset-0 h-full w-full object-cover">
            <div aria-hidden="true" class="absolute inset-0"
                 style="background-image:linear-gradient(0deg,rgba(0,0,0,0.47) 0%,rgba(102,102,102,0) 116.97%),linear-gradient(90deg,rgba(0,0,0,0.25) 0%,rgba(0,0,0,0.25) 100%)"></div>
        </div>

        {{-- Header: MENU / lockup / ENQUIRE --}}
        <header class="absolute inset-x-0 top-0 z-40 pt-[clamp(1.25rem,2.55vw,44px)]">
            <div class="shell flex items-center justify-between gap-4">
                <button type="button" data-menu-open aria-expanded="false" aria-controls="site-menu"
                        aria-label="Open navigation menu"
                        class="flex shrink-0 items-center gap-1 text-white transition-opacity hover:opacity-70">
                    <x-icon name="menu" class="h-[clamp(20px,1.62vw,28px)] w-[clamp(20px,1.62vw,28px)]"/>
                    <span class="text-fluid-body font-semibold uppercase">Menu</span>
                </button>

                <a href="#top" aria-label="{{ config('site.name') }} — home" class="shrink-0">
                    <span class="flex flex-col items-center leading-none text-white">
                        <img src="{{ asset('images/logo-mark.png') }}" alt=""
                             width="540" height="462" class="h-auto w-[clamp(38px,3.94vw,68px)]">
                        <span class="mt-[0.55em] font-wordmark text-[clamp(13px,1.37vw,23.6px)] font-semibold whitespace-nowrap">
                            {{ Str::upper(config('site.name')) }}
                        </span>
                        <span class="mt-[0.25em] font-wordmark text-[clamp(7px,0.73vw,12.5px)] font-bold tracking-[0.02em] whitespace-nowrap">
                            {{ Str::upper(config('site.tagline')) }}
                        </span>
                    </span>
                </a>

                <a href="#contact"
                   class="shrink-0 text-fluid-body font-semibold uppercase text-white underline underline-offset-4 transition-opacity hover:opacity-70">
                    Enquire
                </a>
            </div>
        </header>

        <div class="relative z-10 mt-auto flex flex-col gap-[clamp(2.5rem,17vh,17rem)] pt-[36vh] pb-[clamp(2.5rem,6vh,4.5rem)]">
            {{-- Intro row --}}
            <div class="shell">
                <div class="border-t border-white/25 pt-[clamp(1.25rem,1.5vw,26px)]">
                    <div class="grid gap-6 text-white md:grid-cols-12 md:items-start">
                        <p data-split data-split-delay="520"
                           class="text-fluid-body font-semibold md:col-span-3 md:max-w-[170px]">{{ $hero['eyebrow'] }}</p>

                        <p data-split data-split-delay="600"
                           class="text-fluid-lead font-medium md:col-span-6 md:max-w-[670px]">{{ $hero['intro'] }}</p>

                        <a href="{{ $hero['cta']['href'] }}"
                           class="group inline-flex items-center gap-1 text-fluid-sm font-medium md:col-span-3 md:justify-end">
                            {{ $hero['cta']['label'] }}
                            <x-icon name="arrow-right" class="w-[clamp(20px,1.62vw,28px)] transition-transform duration-300 group-hover:translate-x-1"/>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Display type --}}
            <h1 class="shell display text-fluid-hero uppercase text-white">
                <span class="flex flex-wrap items-baseline justify-between gap-x-6">
                    <span data-split data-split-delay="120" class="block">{{ $hero['words']['first'] }}</span>
                    <span data-split data-split-delay="220" class="block">{{ $hero['words']['second'] }}</span>
                </span>
                <span data-split data-split-delay="320"
                      class="mt-[0.06em] block pl-[max(0px,calc(27%-var(--spacing-gutter)))]">{{ $hero['words']['third'] }}</span>
            </h1>
        </div>
    </div>
</section>

{{-- Full-screen navigation overlay --}}
<div id="site-menu" data-menu role="dialog" aria-modal="true" aria-label="Site navigation"
     class="pointer-events-none fixed inset-0 z-50 bg-night/95 opacity-0 backdrop-blur-sm transition-opacity duration-500">
    <div class="shell flex h-full flex-col py-[clamp(1.25rem,2.55vw,44px)]">
        {{--
            justify-end, not justify-between: the "Navigation" label that used to
            sit opposite Close is gone, and justify-between would drop the button
            to the left edge. The dialog still carries aria-label="Site
            navigation", so nothing is lost for assistive tech.
        --}}
        <div class="flex items-center justify-end">
            <button type="button" data-menu-close
                    class="text-fluid-body font-semibold uppercase text-white transition-opacity hover:opacity-70">Close</button>
        </div>

        <nav class="flex flex-1 flex-col justify-center gap-2">
            @foreach ($nav as $item)
                <a href="{{ $item['href'] }}" data-menu-link
                   class="display group flex w-fit items-center gap-6 text-[clamp(2rem,5vw,86px)] uppercase text-white transition-colors hover:text-teal">
                    {{ $item['label'] }}
                    <x-icon name="arrow-right" class="w-7 opacity-0 transition-opacity duration-300 group-hover:opacity-100"/>
                </a>
            @endforeach
        </nav>

        <div class="flex flex-wrap gap-x-10 gap-y-3">
            @foreach ($social as $s)
                <a href="{{ $s['href'] }}" target="_blank" rel="noreferrer noopener"
                   class="text-fluid-sm text-white/60 transition-colors hover:text-white">{{ $s['label'] }}</a>
            @endforeach
        </div>
    </div>
</div>
