@php($footer = config('site.footer'))
@php($nav = \App\Support\Nav::items())
@php($social = config('site.social'))
@php($contact = config('site.contact'))

{{--
    Figma 1803:2, y9415–10030, on #3FA7B3.

    Redrawn. It was a lock-up and nav at the gutter, a "Recently Completed"
    card two thirds across and the marks in a strip beside it; the file
    replaces all of that with one row — the mark, a line of copy and a pill on
    the left, then four labelled columns, with the marks in a ROW under the
    last of them — and the wordmark below, unchanged.

    The tracks are the frame's own, measured from its content edges at 74 and
    1654: 373 of left block, then 103, 182, 291 and 280, with 88 between each.
    Written as those fractions so they hold their proportions at any width
    rather than only at 1728.

    No legal bar. The file draws no copyright down here, because the enquiry
    card above already sets one — it has since that card was built, so nothing
    is lost by following the file.
--}}
<footer class="overflow-hidden bg-teal pt-[clamp(2.5rem,4.63vw,80px)] text-white">
    <div class="shell">

        {{-- One row from lg. Below that the left block stacks over the four
             columns, and the columns themselves go two-up: at 390 the four
             abreast leave "Office 804, Sapphire Tower Dubai" 60px to wrap in. --}}
        {{-- The frame's five tracks from XL, not lg. At 1024 they are the
             right proportions and the wrong sizes: the socials track comes out
             at 130 and its five marks at 25 square, which is half a tap
             target. Two columns carry it from sm to xl instead. --}}
        <div class="grid gap-x-[clamp(1.5rem,5.1vw,88px)] gap-y-[clamp(2rem,3.7vw,64px)] sm:grid-cols-2 xl:grid-cols-[373fr_103fr_182fr_291fr_280fr]">

            <div class="reveal flex flex-col gap-[clamp(1.25rem,2vw,34px)] sm:col-span-2 xl:col-span-1">
                <img src="{{ asset('images/logo-mark.png') }}" alt="" width="540" height="462"
                     class="h-auto w-[clamp(44px,3.65vw,63px)]">

                <p class="max-w-[373px] text-fluid-body font-medium leading-[1.35]">{{ $footer['lead'] }}</p>

                {{-- White pill against the teal, which is the one place on the
                     site the pill inverts — everywhere else it sits on white. --}}
                <a href="{{ $footer['cta']['href'] }}"
                   class="group inline-flex w-fit items-center gap-2 rounded-full bg-white px-[clamp(1.25rem,1.62vw,28px)] py-[clamp(0.625rem,0.75vw,13px)] text-fluid-sm font-medium text-teal transition-opacity hover:opacity-90">
                    {{ $footer['cta']['label'] }}
                    <x-icon name="arrow-right" class="w-[clamp(14px,0.93vw,16px)] transition-transform duration-300 group-hover:translate-x-0.5"/>
                </a>
            </div>

            {{-- Navigate. Leading, not a row gap, sets the rhythm: the file
                 stacks these at 29 on 18px type, which is 1.6. --}}
            <div class="reveal flex flex-col gap-[clamp(1rem,1.7vw,29px)]" style="transition-delay:80ms">
                <p class="text-fluid-sm font-medium text-white/60">{{ $footer['columns']['nav'] }}</p>

                <nav aria-label="Footer">
                    <ul class="flex flex-col text-fluid-sm leading-[1.6] font-medium">
                        @foreach ($nav as $item)
                            <li>
                                <a href="{{ $item['href'] }}"
                                   class="inline-block py-[7px] -my-[7px] transition-opacity hover:opacity-70">{{ $item['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>

            {{-- Contact. Each line draws only when it is set, so an unfilled
                 number leaves no empty row behind it. --}}
            <div class="reveal flex flex-col gap-[clamp(1rem,1.7vw,29px)]" style="transition-delay:140ms">
                <p class="text-fluid-sm font-medium text-white/60">{{ $footer['columns']['contact'] }}</p>

                <ul class="flex flex-col gap-[clamp(0.25rem,0.5vw,8px)] text-fluid-sm leading-[1.6] font-medium">
                    @if ($contact['phone'])
                        <li>
                            <a href="tel:{{ preg_replace('~[^0-9+]~', '', $contact['phone']) }}"
                               class="inline-block py-[7px] -my-[7px] transition-opacity hover:opacity-70">{{ $contact['phone'] }}</a>
                        </li>
                    @endif

                    @if ($contact['email'])
                        <li>
                            <a href="mailto:{{ $contact['email'] }}"
                               class="inline-block py-[7px] -my-[7px] transition-opacity hover:opacity-70">{{ $contact['email'] }}</a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="reveal flex flex-col gap-[clamp(1rem,1.7vw,29px)]" style="transition-delay:200ms">
                <p class="text-fluid-sm font-medium text-white/60">{{ $footer['columns']['address'] }}</p>

                @if ($contact['address'])
                    <p class="max-w-[291px] text-fluid-sm leading-[1.6] font-medium">{{ $contact['address'] }}</p>
                @endif
            </div>

            {{-- Socials, in a row. Icons carry no text, so each link needs an
                 accessible name of its own — aria-label supplies it and the
                 mark itself stays aria-hidden. The 48px box is the file's and
                 is also the tap target; the glyph inside it is 25. --}}
            <div class="reveal flex flex-col gap-[clamp(1rem,1.7vw,29px)]" style="transition-delay:260ms">
                <p class="text-fluid-sm font-medium text-white/60">{{ $footer['columns']['social'] }}</p>

                {{-- The five share the track rather than each holding 48. The
                     frame's column is 280 — exactly five 48s and four 10s —
                     and that only fits at 1728: at 1440 the track scales to
                     231 and the fifth mark wrapped to a line of its own.
                     Flexible, they fill whatever the track is and cap at the
                     frame's 48 on a wide screen. --}}
                <ul class="flex w-full max-w-[280px] items-center gap-[clamp(0.375rem,0.58vw,10px)]">
                    @foreach ($social as $s)
                        <li class="min-w-0 max-w-12 flex-1">
                            <a href="{{ $s['href'] }}" target="_blank" rel="noreferrer noopener"
                               aria-label="{{ $s['label'] }} — opens in a new tab"
                               class="grid aspect-square w-full place-items-center rounded-[10px] bg-white/15 text-white transition-colors duration-300 hover:bg-white/25">
                                <x-icon :name="$s['icon']" class="w-[min(52%,25px)]"/>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{--
            Wordmark lock-up. No rule above it: the design separates it from the
            band by distance alone, and a hairline turned the two into stacked
            sections rather than one field with a mark sitting in it.

            The bottom padding is the profile button's berth. That button is
            fixed to a bottom corner — right on a phone, left from sm — so
            whatever is last on the page is what it sits on, and since the legal
            bar went with the redraw, that is now this lock-up. 76px clears the
            pill's 48 and its gutter at either size.
        --}}
        <div class="mt-[clamp(2.75rem,7.06vw,122px)] pb-[76px]">
            {{--
                Face, weight, leading and the subtitle's tracking are in
                .logotype / .logotype-sub — they are one typographic unit and
                the tracking value in particular is load-bearing, so it is
                commented where it is set rather than buried in a class list.

                Sized by measurement (see motion/fit-text.js). The vw values are
                only the no-JavaScript fallback, set low enough not to overflow
                with a wider fallback face.

                Spacing is set in `em` so it scales with the measured font size
                rather than the viewport — the lock-up keeps the same optical
                proportions whatever width fit-text lands on.

                No bottom padding on the first line: in the reference the Q's
                tail sweeps down past the subtitle's cap line and finishes
                beside "LLC.", and padding that cleared the descender pushed the
                two lines apart into stacked lines rather than one lock-up.
            --}}
            <p data-fit-text class="logotype text-[10.5vw]">
                {{ Str::upper($footer['wordmark']) }}
            </p>
            <p data-fit-text class="logotype logotype-sub mt-[0.28em] text-[1.7vw]">
                {{ Str::upper($footer['wordmark_sub']) }}
            </p>
        </div>
    </div>
</footer>
