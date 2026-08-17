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
        <div class="flex items-center justify-end">
            <button type="button" data-menu-close
                    class="text-fluid-body font-semibold uppercase text-white transition-opacity hover:opacity-70">Close</button>
        </div>

        {{--
            The way in for a client sits across from the last of the links.

            It used to be in the header beside ENQUIRE, which no frame in the
            file draws — that bar is MENU, the lock-up and ENQUIRE — and then
            at the head of this panel, which put it above the navigation rather
            than beside it. On the gutter opposite CONTACT it reads as the one
            thing here that is not a page.

            Absolute against the block of links rather than a row of its own:
            the block hugs them, so its foot is CONTACT's foot whatever the
            list holds, and the button sits on that line without the nav having
            to know about it. Hidden below lg, where the links run the width of
            a phone and there is no gutter to sit on — the head of the panel
            carries it there instead.

            Signed-in visitors get the portal rather than a sign-in page: a
            client who is already authenticated has no use for one, and it
            saves a redirect.
        --}}
        <div class="flex flex-1 flex-col justify-center">
            <div class="relative flex flex-col gap-2">
                <a href="{{ auth()->check() ? route('portal.dashboard') : route('login') }}" data-menu-link
                   class="w-fit text-fluid-body font-semibold uppercase text-white transition-opacity hover:opacity-70 lg:hidden">
                    {{ auth()->check() ? 'Portal' : 'Login' }}
                </a>

                <nav class="flex flex-col gap-2">
                    @foreach ($nav as $item)
                        <a href="{{ $item['href'] }}" data-menu-link
                           class="display group flex w-fit items-center gap-6 text-[clamp(2rem,5vw,86px)] uppercase text-white transition-colors hover:text-teal">
                            {{ $item['label'] }}
                            <x-icon name="arrow-right" class="w-7 opacity-0 transition-opacity duration-300 group-hover:opacity-100"/>
                        </a>
                    @endforeach
                </nav>

                <a href="{{ auth()->check() ? route('portal.dashboard') : route('login') }}" data-menu-link
                   class="absolute bottom-0 right-0 hidden items-center border border-white/40 px-[clamp(1.5rem,2.31vw,40px)] py-[clamp(0.75rem,1.16vw,20px)] text-fluid-body font-semibold uppercase text-white transition-colors duration-300 hover:border-white hover:bg-white hover:text-night lg:flex">
                    {{ auth()->check() ? 'Portal' : 'Login' }}
                </a>
            </div>
        </div>

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
