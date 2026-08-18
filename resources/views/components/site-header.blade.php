@props(['tone' => 'light'])

@php
    /*
     * 'light' is white type for a photographic hero; 'dark' is for a page that
     * opens on white. Only the colours differ — same bar, same box.
     *
     * On the dark tone the lock-up is teal throughout, mark and wordmark
     * together, so it reads as the logo rather than as two pieces in two
     * colours. The nav words either side stay ink: they are controls, not
     * branding, and teal would give them a weight they should not carry.
     */
    $isDark = $tone === 'dark';
    $text = $isDark ? 'text-ink' : 'text-white';
    $wordmark = $isDark ? 'text-teal' : 'text-white';
    $mark = $isDark ? 'images/logo-mark-teal.png' : 'images/logo-mark.png';
@endphp

{{--
    The header bar that sits over the hero photo: MENU / lock-up / ENQUIRE.

    No sign-in here. Every frame in the file draws this bar as MENU, the
    lock-up and ENQUIRE — the process frame's own vectors carry those three and
    nothing else — and the way in for a client now lives in the menu the MENU
    button opens, opposite Close.

    Extracted from sections/hero.blade.php so the About page's hero can carry
    the same bar rather than a copy of it. It is absolutely positioned, so it
    expects a positioned hero around it — both callers give it one.

    Links go through Nav::href so the fragments still land on the landing page
    when this renders on /about.
--}}
<header class="absolute inset-x-0 top-0 z-40 pt-[clamp(1.25rem,2.947vw,50.9px)]">
    {{-- gap-2 on a phone. The three parts are all shrink-0 — a wordmark and
         two words that must not wrap — so the gap is the only give in the row,
         and at 320 the lock-up's sub-line leaves the pair of gaps 5px short of
         what ENQUIRE needs. --}}
    <div class="shell grid grid-cols-[1fr_auto_1fr] items-center gap-2 sm:gap-4">
        <button type="button" data-menu-open aria-expanded="false" aria-controls="site-menu"
                aria-label="Open navigation menu"
                class="flex shrink-0 items-center gap-1 justify-self-start py-2.5 -my-2.5 {{ $text }} transition-opacity hover:opacity-70">
            <x-icon name="menu" class="h-[clamp(20px,1.62vw,28px)] w-[clamp(20px,1.62vw,28px)]"/>
            <span class="text-fluid-body font-semibold uppercase">Menu</span>
        </button>

        <a href="{{ \App\Support\Nav::href('#top') }}" aria-label="{{ config('site.name') }} — home"
           class="shrink-0 justify-self-center">
            <span class="flex flex-col items-center leading-none {{ $wordmark }}">
                <img src="{{ asset($mark) }}" alt=""
                     width="540" height="462" class="w-[clamp(38px,3.941vw,68.1px)] h-[clamp(35px,3.644vw,63px)]">
                <span class="mt-[0.77em] font-wordmark text-[clamp(11px,1.185vw,20.5px)] font-semibold whitespace-nowrap">
                    {{ Str::upper(config('site.name')) }}
                </span>
                <span class="mt-[0.25em] font-wordmark text-[clamp(8px,0.609vw,10.5px)] font-bold tracking-[0.02em] whitespace-nowrap">
                    {{ Str::upper(config('site.tagline')) }}
                </span>
            </span>
        </a>

        <div class="flex shrink-0 items-center justify-end justify-self-end">
            <a href="{{ \App\Support\Nav::href('#contact') }}"
               class="py-2.5 -my-2.5 text-fluid-body font-semibold uppercase {{ $text }} underline underline-offset-4 transition-opacity hover:opacity-70">
                Enquire
            </a>
        </div>
    </div>
</header>
