@php($band = config('site.joinery_page.ecosystem'))

{{--
    Figma 1803:2, y2516–3000. Two rows on the same two tracks: the teal label
    against the serif statement, then a hairline, then the note against the
    body copy.

    The tracks are the frame's own — 23333 to 23714 is 381 of label column, and
    the copy runs from there to 24705, which is 991. Written as those two
    fractions so they hold at every width rather than only at 1728.
--}}
<section class="bg-white pb-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">

        <div class="grid gap-[clamp(1rem,2vw,34px)] lg:grid-cols-[381fr_991fr] lg:gap-[clamp(1.5rem,2.31vw,40px)]">
            <p class="reveal text-fluid-sm font-medium leading-[1.4] text-teal">
                @foreach ($band['label'] as $line)
                    <span class="block">{{ $line }}</span>
                @endforeach
            </p>

            <p class="reveal display max-w-[18em] text-fluid-h2 leading-[1.3] text-ink" style="transition-delay:80ms">{{ $band['statement'] }}</p>
        </div>

        {{-- The rule runs the full measure under both columns, and the row
             below picks the same two tracks up again. --}}
        <span aria-hidden="true" class="reveal-line mt-[clamp(1.5rem,2.8vw,48px)] block h-px w-full bg-black/15"></span>

        <div class="mt-[clamp(1.5rem,2.8vw,48px)] grid gap-[clamp(1rem,2vw,34px)] lg:grid-cols-[381fr_991fr] lg:gap-[clamp(1.5rem,2.31vw,40px)]">
            <p class="reveal text-fluid-sm font-medium leading-[1.4] text-ink">
                @foreach ($band['note'] as $line)
                    <span class="block">{{ $line }}</span>
                @endforeach
            </p>

            <p class="reveal text-fluid-body font-medium leading-[1.5] text-ink-muted" style="transition-delay:80ms">{{ $band['body'] }}</p>
        </div>
    </div>
</section>
