@php($nav = \App\Support\Nav::items())
@php($social = config('site.social'))

{{--
    Full-screen navigation overlay. Extracted from sections/hero.blade.php so
    the About page opens the same menu rather than a second copy of it —
    motion/menu.js binds to the first [data-menu] on the page, so there must
    only ever be one.
--}}
<div id="site-menu" data-menu role="dialog" aria-modal="true" aria-label="Site navigation"
     class="pointer-events-none fixed inset-0 z-50 bg-night/95 opacity-0 backdrop-blur-sm transition-opacity duration-500">
    <div class="shell flex h-full flex-col py-[clamp(1.25rem,2.55vw,44px)]">
        {{--
            The way in for a client, opposite Close. It used to sit in the
            header beside ENQUIRE, which no frame in the file draws — the bar
            there is MENU, the lock-up and ENQUIRE — so it lives here instead,
            where it is one press further away and in no one's way.

            Signed-in visitors get the portal rather than a sign-in page: a
            client who is already authenticated has no use for one, and it
            saves a redirect.

            justify-between now that there are two, which is the arrangement
            this row was written for before the label opposite Close was
            dropped.
        --}}
        <div class="flex items-center justify-between">
            <a href="{{ auth()->check() ? route('portal.dashboard') : route('login') }}" data-menu-link
               class="text-fluid-body font-semibold uppercase text-white transition-opacity hover:opacity-70">
                {{ auth()->check() ? 'Portal' : 'Login' }}
            </a>

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
