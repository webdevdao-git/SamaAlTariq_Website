@php($clients = config('site.clients'))
@php($logos = $clients['logos'])

{{--
    Figma: frame 1195:119, 1728×294, background #F9F9F9.
    The render shows the marks desaturated, so they are greyscaled in CSS and
    lift to full colour on hover.

    The row runs right to left rather than sitting still, full width under its
    label. The roster outgrew a six-across grid — there are thirty-five of them
    — and a marquee shows the whole list without either shrinking the marks or
    stacking them into rows that push the section taller.

    The track holds the roster twice and slides exactly -50%, which lands the
    second copy where the first began, so the loop closes with no jump. The two
    copies must stay identical for that to hold. Only the first is a real list:
    the clone is aria-hidden, so a screen reader hears each client once.

    Speed is set here rather than in the stylesheet, from the number of marks,
    so the row travels at the same pace whatever the roster length — see
    --marquee-duration in app.css.
--}}
<section class="bg-mist py-[clamp(2.25rem,3.5vw,56px)]">
    <div class="shell">
        <p class="reveal text-fluid-label font-medium leading-snug text-teal">
            @foreach ($clients['label'] as $line)
                <span class="block">{{ $line }}</span>
            @endforeach
        </p>
        @isset($clients['caption'])
            <p class="reveal mt-3 max-w-[40ch] text-fluid-body text-ink-muted">{{ $clients['caption'] }}</p>
        @endisset
    </div>

    {{--
        The strip runs the full width of the screen, edge to edge under the
        label, with the marks set large: at the old size beside the label they
        read as a row of specks.

        Two things here are about the strip never collapsing rather than about
        how it looks:

        · the row carries its own height. Every mark is an image inside a
          clipped, max-content track, and until one loads its `h-auto` is zero
          — so an unloaded row would be a row of no height, an empty band.
        · the first copy is not lazy. Those are the marks on screen at rest;
          deferring them is what leaves the band empty on arrival. The clone
          stays lazy — it is the whole roster again, and the browser has them
          cached by the time it scrolls in.
    --}}
    <div class="reveal marquee mt-[clamp(1.25rem,2.4vw,40px)] w-full" style="--marquee-duration:{{ count($logos) * 3.2 }}s;transition-delay:120ms">
        <div class="marquee__track">
            @foreach ([false, true] as $isClone)
                <ul class="flex shrink-0 items-center gap-x-[clamp(2rem,4.5vw,80px)] pr-[clamp(2rem,4.5vw,80px)]"
                    @if ($isClone) aria-hidden="true" @endif>
                    @foreach ($logos as $logo)
                        <li class="flex h-[clamp(40px,3.6vw,60px)] w-[clamp(110px,9vw,160px)] shrink-0 items-center justify-center">
                            <img src="{{ asset($logo['src']) }}" alt="{{ $isClone ? '' : $logo['name'] }}"
                                 @if ($isClone) loading="lazy" @endif decoding="async"
                                 class="max-h-full max-w-full object-contain opacity-75 grayscale transition duration-300 hover:opacity-100 hover:grayscale-0">
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </div>
</section>
