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
            <div class="shell grid grid-cols-[1fr_auto_1fr] items-center gap-4">
                <button type="button" data-menu-open aria-expanded="false" aria-controls="site-menu"
                        aria-label="Open navigation menu"
                        class="flex shrink-0 items-center gap-1 justify-self-start text-white transition-opacity hover:opacity-70">
                    <x-icon name="menu" class="h-[clamp(20px,1.62vw,28px)] w-[clamp(20px,1.62vw,28px)]"/>
                    <span class="text-fluid-body font-semibold uppercase">Menu</span>
                </button>

                <a href="#top" aria-label="{{ config('site.name') }} — home" class="shrink-0 justify-self-center">
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

                <div class="flex shrink-0 items-center justify-end gap-[clamp(1rem,1.85vw,32px)] justify-self-end">
                    {{--
                        Signed-in visitors get the portal instead of a login
                        link — a client who is already authenticated has no use
                        for a sign-in page, and it saves a redirect.
                    --}}
                    <a href="{{ auth()->check() ? route('portal.dashboard') : route('login') }}"
                       class="text-fluid-body font-semibold uppercase text-white transition-opacity hover:opacity-70">
                        {{ auth()->check() ? 'Portal' : 'Login' }}
                    </a>

                    <a href="#contact"
                       class="text-fluid-body font-semibold uppercase text-white underline underline-offset-4 transition-opacity hover:opacity-70">
                        Enquire
                    </a>
                </div>
            </div>
        </header>

        <div class="relative z-10 mt-auto flex flex-col gap-[clamp(2.75rem,8vh,6.5rem)] pt-[clamp(12rem,36vh,24rem)] pb-[clamp(2rem,5.5vh,4.5rem)]">
            {{-- Intro row --}}
            <div class="shell">
                <div class="border-t border-white/25 pt-[clamp(1.25rem,1.5vw,26px)]">
                    <div class="grid gap-x-6 gap-y-5 text-white md:grid-cols-12 md:items-start">
                        <p data-split data-split-delay="520"
                           class="text-fluid-body font-semibold md:col-span-3 md:max-w-[170px]">{{ $hero['eyebrow'] }}</p>

                        <p data-split data-split-delay="600"
                           class="text-fluid-lead font-medium md:col-span-6 md:justify-self-center md:text-center lg:max-w-[670px]">{{ $hero['intro'] }}</p>

                        <a href="{{ $hero['cta']['href'] }}"
                           class="group inline-flex items-center gap-1 text-fluid-sm font-medium md:col-span-3 md:justify-self-end md:text-right">
                            {{ $hero['cta']['label'] }}
                            <x-icon name="arrow-right" class="w-[clamp(20px,1.62vw,28px)] transition-transform duration-300 group-hover:translate-x-1"/>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Display type --}}
            <h1 class="shell display text-fluid-hero uppercase text-white">
                <span class="grid gap-x-[clamp(1.5rem,4vw,4.5rem)] gap-y-[0.08em] md:grid-cols-12 md:items-baseline">
                    <span data-split data-split-delay="120"
                          class="block md:col-span-5">{{ $hero['words']['first'] }}</span>
                    <span data-split data-split-delay="220"
                          class="block md:col-span-7 md:text-right">{{ $hero['words']['second'] }}</span>
                    <span data-split data-split-delay="320"
                          class="block md:col-span-12 md:text-center">{{ $hero['words']['third'] }}</span>
                </span>
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

        {{--
            Icons carry no text, so each link needs an accessible name of its
            own — aria-label supplies it and the mark itself stays aria-hidden.
            The 44px box is the tap target: the glyph is ~24px, which is below
            the size a thumb can reliably hit.
        --}}
        <ul class="flex flex-wrap items-center gap-x-2 gap-y-1">
            @foreach ($social as $s)
                <li>
                    <a href="{{ $s['href'] }}" target="_blank" rel="noreferrer noopener"
                       aria-label="{{ $s['label'] }} — opens in a new tab"
                       class="grid size-11 place-items-center rounded-full text-white/60 transition-colors duration-300 hover:bg-white/10 hover:text-white">
                        <x-icon :name="$s['icon']" class="w-[clamp(20px,1.5vw,24px)]"/>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
