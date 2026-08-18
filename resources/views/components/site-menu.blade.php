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
                    class="py-2.5 -my-2.5 pl-3 text-fluid-body font-semibold uppercase text-white transition-opacity hover:opacity-70">Close</button>
        </div>

        <nav class="flex flex-1 flex-col justify-center gap-2">
            @foreach ($nav as $item)
                <a href="{{ $item['href'] }}" data-menu-link
                   class="display group flex w-fit items-center gap-6 py-[7px] -my-[7px] text-[clamp(2rem,5vw,86px)] uppercase text-white transition-colors hover:text-teal">
                    {{ $item['label'] }}
                    <x-icon name="arrow-right" class="w-7 opacity-0 transition-opacity duration-300 group-hover:opacity-100"/>
                </a>
            @endforeach

            {{--
                The way in for a client is the last line in the list, under
                CONTACT. It has been in four places now — the header beside
                ENQUIRE, which no frame in the file draws; the head of this
                panel; the gutter opposite CONTACT; and a line above it — and
                this is where it was asked for.

                Set like the pages above it, because in a list of six that is
                what it now is. The one thing that marks it out is that it is
                not part of the nav config: it is a route, not a page of the
                site, and it takes the panel's own arrow like the rest.

                Signed-in visitors get the portal rather than a sign-in page: a
                client who is already authenticated has no use for one, and it
                saves a redirect.
            --}}
            <a href="{{ auth()->check() ? route('portal.dashboard') : route('login') }}" data-menu-link
               class="display group flex w-fit items-center gap-6 py-[7px] -my-[7px] text-[clamp(2rem,5vw,86px)] uppercase text-white transition-colors hover:text-teal">
                {{ auth()->check() ? 'Portal' : 'Login' }}
                <x-icon name="arrow-right" class="w-7 opacity-0 transition-opacity duration-300 group-hover:opacity-100"/>
            </a>
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
