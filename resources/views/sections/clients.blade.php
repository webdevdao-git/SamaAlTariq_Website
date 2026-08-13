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

            {{-- min-w-0 lets the strip shrink inside the flex row; without it
                 the track's max-content width would push the label off. --}}
            <div class="marquee w-full min-w-0" style="--marquee-duration:{{ count($logos) * 2.2 }}s">
                <div class="marquee__track">
                    @foreach ([false, true] as $isClone)
                        <ul class="flex shrink-0 items-center gap-x-[clamp(0.75rem,2vw,34px)]"
                            @if ($isClone) aria-hidden="true" @endif>
                            @foreach ($logos as $logo)
                                <li class="flex w-[clamp(88px,7.9vw,137px)] shrink-0 items-center justify-center">
                                    <img src="{{ asset($logo['src']) }}" alt="{{ $isClone ? '' : $logo['name'] }}"
                                         loading="lazy" decoding="async"
                                         class="h-auto max-h-[clamp(30px,2.9vw,50px)] w-auto max-w-full object-contain grayscale transition duration-300 hover:grayscale-0">
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
