@php($clients = config('site.clients'))
@php($logos = $clients['logos'])

{{--
    Figma: frame 1195:119, 1728×294, background #F9F9F9.
    The render shows the marks desaturated, so they are greyscaled in CSS and
    lift to full colour on hover.

    The row runs right to left rather than sitting still. The roster outgrew a
    six-across grid — there are 29 of them — and a marquee shows the whole list
    without either shrinking the marks or stacking them into rows that push the
    section taller.

    The track holds the roster twice and slides exactly -50%, which lands the
    second copy where the first began, so the loop closes with no jump. The two
    copies must stay identical for that to hold. Only the first is a real list:
    the clone is aria-hidden, so a screen reader hears each client once.

    Speed is set here rather than in the stylesheet, from the number of marks,
    so the row travels at the same pace whatever the roster length — see
    --marquee-duration in app.css.
--}}
<section class="bg-mist py-[clamp(2.5rem,4vw,68px)]">
    <div class="shell">
        <div class="reveal flex flex-col items-start gap-[clamp(1.5rem,3vw,52px)] lg:flex-row lg:items-center">
            <p class="shrink-0 text-fluid-label font-medium leading-snug text-teal">
                @foreach ($clients['label'] as $line)
                    <span class="block">{{ $line }}</span>
                @endforeach
            </p>

            {{--
                Three things here are about the strip never collapsing rather
                than about how it looks:

                · flex-1 from lg, not w-full. A 100% basis in a flex row leaves
                  the item relying on shrink to fit beside the label; flex-1
                  asks for the space that is actually left, which is what this
                  wants and cannot resolve to nothing.
                · the row carries its own height. Every mark is a lazy image
                  inside a clipped, max-content track, and until one loads its
                  `h-auto` is zero — so an unloaded row is a row of no height,
                  and the strip renders as an empty band.
                · the first copy is not lazy. Those are the marks on screen at
                  rest; deferring them is what leaves the band empty on arrival.
                  The clone stays lazy — it is 29 more of the same files, and
                  the browser has them cached by the time it scrolls in.
            --}}
            <div class="marquee w-full min-w-0 lg:w-auto lg:flex-1" style="--marquee-duration:{{ count($logos) * 2.2 }}s">
                <div class="marquee__track">
                    @foreach ([false, true] as $isClone)
                        <ul class="flex shrink-0 items-center gap-x-[clamp(0.75rem,2vw,34px)]"
                            @if ($isClone) aria-hidden="true" @endif>
                            @foreach ($logos as $logo)
                                <li class="flex h-[clamp(30px,2.9vw,50px)] w-[clamp(88px,7.9vw,137px)] shrink-0 items-center justify-center">
                                    <img src="{{ asset($logo['src']) }}" alt="{{ $isClone ? '' : $logo['name'] }}"
                                         @if ($isClone) loading="lazy" @endif decoding="async"
                                         class="max-h-full max-w-full object-contain grayscale transition duration-300 hover:grayscale-0">
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
