@props(['login' => true])

{{--
    The header bar that sits over the hero photo: MENU / lock-up / ENQUIRE.

    `login` is off on the About page, whose Figma frame shows ENQUIRE alone on
    the right. The link is still reachable from every other page and from /login
    directly, so nothing is lost but the one entry point on that frame.

    Extracted from sections/hero.blade.php so the About page's hero can carry
    the same bar rather than a copy of it. It is absolutely positioned, so it
    expects a positioned hero around it — both callers give it one.

    Links go through Nav::href so the fragments still land on the landing page
    when this renders on /about.
--}}
<header class="absolute inset-x-0 top-0 z-40 pt-[clamp(1.25rem,2.55vw,44px)]">
    <div class="shell grid grid-cols-[1fr_auto_1fr] items-center gap-4">
        <button type="button" data-menu-open aria-expanded="false" aria-controls="site-menu"
                aria-label="Open navigation menu"
                class="flex shrink-0 items-center gap-1 justify-self-start text-white transition-opacity hover:opacity-70">
            <x-icon name="menu" class="h-[clamp(20px,1.62vw,28px)] w-[clamp(20px,1.62vw,28px)]"/>
            <span class="text-fluid-body font-semibold uppercase">Menu</span>
        </button>

        <a href="{{ \App\Support\Nav::href('#top') }}" aria-label="{{ config('site.name') }} — home"
           class="shrink-0 justify-self-center">
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
                Signed-in visitors get the portal instead of a login link — a
                client who is already authenticated has no use for a sign-in
                page, and it saves a redirect.
            --}}
            @if ($login)
                <a href="{{ auth()->check() ? route('portal.dashboard') : route('login') }}"
                   class="text-fluid-body font-semibold uppercase text-white transition-opacity hover:opacity-70">
                    {{ auth()->check() ? 'Portal' : 'Login' }}
                </a>
            @endif

            <a href="{{ \App\Support\Nav::href('#contact') }}"
               class="text-fluid-body font-semibold uppercase text-white underline underline-offset-4 transition-opacity hover:opacity-70">
                Enquire
            </a>
        </div>
    </div>
</header>
