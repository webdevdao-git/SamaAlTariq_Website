@php($footer = config('site.footer'))
@php($nav = config('site.nav'))
@php($social = config('site.social'))
@php($contact = config('site.contact'))

{{--
    Figma: frame 1226:1038, 1728×774, background #3FA7B3.

    Three columns above the wordmark lock-up: SAMA AL TARIQ over BUILDING
    CONTRACTING LLC. with 0.72em tracking, both sized by measurement rather than
    a fixed vw (see motion/fit-text.js).

    The columns are laid out on the same 12-column grid as the rest of the page
    and share a top edge, so they read as one band instead of three floating
    blocks. A contact column occupies the space the design leaves empty between
    the navigation and the project card.
--}}
<footer class="overflow-hidden bg-teal pt-[clamp(2.5rem,4.63vw,80px)] text-white">
    <div class="shell">
        <div class="grid gap-x-[clamp(1.5rem,2.3vw,40px)] gap-y-[clamp(2.5rem,4vw,68px)] lg:grid-cols-12">

            {{-- Brand + navigation --}}
            <div class="reveal flex flex-col gap-[clamp(1.25rem,2.14vw,37px)] lg:col-span-3">
                <img src="{{ asset('images/logo-mark.png') }}" alt="" width="540" height="462"
                     class="h-auto w-[clamp(44px,3.65vw,63px)]">

                <nav aria-label="Footer">
                    <ul class="flex flex-col gap-[clamp(0.15rem,0.35vw,6px)]">
                        @foreach ($nav as $item)
                            <li>
                                <a href="{{ $item['href'] }}"
                                   class="inline-block text-fluid-sm font-medium transition-opacity hover:opacity-70">{{ $item['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>

            {{--
                Contact. Only the email is known; phone and address render solely
                when set in config, so the footer never shows an invented number.
            --}}
            <div class="reveal flex flex-col gap-[clamp(0.75rem,1.16vw,20px)] lg:col-span-3" style="transition-delay:60ms">
                <p class="text-[clamp(10px,0.73vw,12.5px)] font-semibold tracking-[0.12em] uppercase text-white/70">
                    {{ $contact['heading'] }}
                </p>

                <ul class="flex flex-col gap-[clamp(0.35rem,0.7vw,12px)] text-fluid-sm font-medium">
                    <li>
                        <a href="mailto:{{ $contact['email'] }}"
                           class="inline-block break-all transition-opacity hover:opacity-70">{{ $contact['email'] }}</a>
                    </li>

                    @if ($contact['phone'])
                        <li>
                            <a href="tel:{{ preg_replace('/[^+\d]/', '', $contact['phone']) }}"
                               class="inline-block transition-opacity hover:opacity-70">{{ $contact['phone'] }}</a>
                        </li>
                    @endif

                    @if ($contact['address'])
                        <li class="max-w-[24ch] text-white/80">{{ $contact['address'] }}</li>
                    @endif
                </ul>

                {{--
                    Deliberately not pushed to the bottom of the grid cell. With
                    only an email above it, aligning this to the taller card
                    column left it stranded in the middle of empty teal; sitting
                    with its own group reads as one block.
                --}}
                <a href="#contact" class="group mt-[clamp(0.5rem,0.9vw,16px)] inline-flex w-fit items-center gap-1.5 text-fluid-sm font-semibold underline underline-offset-4 transition-opacity hover:opacity-70">
                    Start a project
                    <x-icon name="arrow-right" class="w-[clamp(16px,1.16vw,20px)] transition-transform duration-300 group-hover:translate-x-1"/>
                </a>
            </div>

            {{-- Recently completed --}}
            <div class="reveal lg:col-span-4" style="transition-delay:120ms">
                <a href="{{ $footer['recent']['href'] }}" class="group block w-full max-w-[417px]">
                    <span class="mb-[clamp(0.35rem,0.44vw,7.5px)] flex items-center gap-1.5">
                        <x-icon name="dot" class="text-white"/>
                        <span class="text-[clamp(10px,0.73vw,12.5px)] font-semibold">{{ $footer['recent']['label'] }}</span>
                    </span>

                    <span class="relative block aspect-[417/259] w-full overflow-hidden bg-white/10">
                        <img src="{{ asset($footer['recent']['image']) }}" alt="{{ $footer['recent']['alt'] }}"
                             loading="lazy" decoding="async"
                             class="h-full w-full object-cover transition-transform duration-[900ms] ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-[1.05]">

                        {{--
                            Sits inside the frame on a teal disc. Previously it
                            straddled the bottom edge, which read as a stray
                            element rather than an affordance, and it vanished
                            against pale photography.
                        --}}
                        <span class="absolute right-3 bottom-3 grid size-[clamp(34px,2.8vw,48px)] place-items-center rounded-full bg-teal/90 backdrop-blur-sm transition-transform duration-500 group-hover:-translate-y-1 group-hover:translate-x-1">
                            <x-icon name="diagonal-arrow" class="w-[clamp(14px,1.16vw,20px)] text-white"/>
                        </span>
                    </span>
                </a>
            </div>

            {{-- Social --}}
            <div class="reveal lg:col-span-2 lg:justify-self-end" style="transition-delay:180ms">
                <p class="mb-[clamp(0.75rem,1.16vw,20px)] text-[clamp(10px,0.73vw,12.5px)] font-semibold tracking-[0.12em] uppercase text-white/70">
                    Follow
                </p>
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

        {{-- Wordmark lock-up --}}
        <div class="mt-[clamp(2.5rem,4.63vw,80px)] border-t border-white/20 pt-[clamp(1.5rem,2.55vw,44px)]">
            {{--
                Sized by measurement (see motion/fit-text.js). The vw values are
                only the no-JavaScript fallback, set low enough not to overflow
                with a wider fallback face.

                `pb` on the first line gives the Q descender somewhere to go —
                at leading 0.78 it otherwise crosses into the tracked line below.
            --}}
            <p data-fit-text class="font-wordmark text-[10.5vw] leading-[0.78] font-semibold whitespace-nowrap pb-[0.06em]">
                {{ Str::upper($footer['wordmark']) }}
            </p>
            <p data-fit-text class="mt-[clamp(0.15rem,0.35vw,6px)] font-wordmark text-[2.5vw] leading-none tracking-[0.72em] whitespace-nowrap">
                {{ Str::upper($footer['wordmark_sub']) }}
            </p>
        </div>

        {{-- Legal bar --}}
        <div class="mt-[clamp(1.25rem,2.31vw,40px)] flex flex-wrap items-center justify-between gap-4 border-t border-white/20 py-[clamp(1rem,1.5vw,26px)]">
            <p class="text-fluid-sm text-white/80">{{ config('site.copyright') }}</p>
            <a href="#top" class="group inline-flex items-center gap-1.5 text-fluid-sm font-medium transition-opacity hover:opacity-70">
                Back to top
                <span class="inline-block rotate-[-90deg] transition-transform duration-300 group-hover:-translate-y-0.5">
                    <x-icon name="arrow-right" class="w-[clamp(16px,1.16vw,20px)]"/>
                </span>
            </a>
        </div>
    </div>
</footer>
