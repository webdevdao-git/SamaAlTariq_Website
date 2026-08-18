@php($purpose = config('site.about_page.purpose'))

{{--
    Figma: frame 1386:1161, 1728×1117.

    One sentence set at 128px and broken into three lines that step down the
    frame — the first on the left gutter, the second on the right one, the third
    starting a quarter of the way in. The three are lines of one block, not
    three headings: Figma steps them 113px apart, which is 0.88 × 128, exactly
    the leading .editorial-heading already carries. So the block is a single h2
    and only the horizontal placement is set per line.

    The 24.78% on the last line is its Figma x (468) measured from the gutter
    and divided by the 1570px content column, so the staircase keeps its shape
    as the page narrows instead of collapsing at one width.

    Below md the lines go flush left. At phone width the type is 52px and the
    stagger would push the third line off the screen.

    The image strip is full-bleed, outside the shell: four 426px frames and
    three 8px gaps is 1728 — the frame width, gutters included.
--}}
<section class="bg-white pt-[clamp(2.5rem,5.03vw,87px)] pb-[clamp(2.5rem,4.98vw,86px)]">

    <div class="shell">
        {{-- 2.25rem at the floor rather than the token's 3.25. "Construction"
             is set as letters, each in its own mask, so the word cannot break
             and cannot be made to: at 52px it measures 387 against the 280 a
             320 screen has to give, and the page scrolled sideways by that
             difference. 36px brings it to 268. Above 486 the vw term is the
             larger of the two and the frame's own size is untouched. --}}
        <h2 class="editorial-heading text-[clamp(2.25rem,7.41vw,128px)] uppercase text-ink">
            {{-- Letter masks rather than lines. At 128px each glyph is a shape
                 in its own right, and the staircase arrives a letter at a time
                 instead of three slabs dropping in. --}}
            <span data-split data-split-by="letter" data-split-delay="0" class="block">{{ $purpose['words'][0] }}</span>
            <span data-split data-split-by="letter" data-split-delay="180" class="block md:text-right">{{ $purpose['words'][1] }}</span>
            <span data-split data-split-by="letter" data-split-delay="360" class="block md:ml-[24.78%]">{{ $purpose['words'][2] }}</span>
        </h2>

        {{-- Copy on the left, the button on the right gutter, tops level. --}}
        <div class="mt-[clamp(1.5rem,2.9vw,50px)] flex flex-col gap-[clamp(1.5rem,2.31vw,40px)] md:flex-row md:items-start md:justify-between">
            <p class="reveal max-w-[625px] text-fluid-lead font-medium leading-[1.375] text-ink">{{ $purpose['body'] }}</p>

            <a href="{{ \App\Support\Nav::href($purpose['cta']['href']) }}" class="reveal pill group w-fit shrink-0"
               style="transition-delay:140ms">
                {{ $purpose['cta']['label'] }}
                <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
            </a>
        </div>
    </div>

    {{--
        The strip runs right to left rather than sitting as a row of four.

        It uses the same loop as the client marquee: the roster twice on a
        max-content track, slid exactly -50% so the second copy lands where the
        first began and the loop closes with no jump. Only the first copy is
        read out — the clone is aria-hidden with empty alt, so a picture is
        announced once.

        Duration comes from the number of pictures, so the strip travels at one
        speed whatever the roster length. Hovering pauses it, and reduced motion
        stops it and hands the row back as one that scrolls.

        Each frame keeps the design's 426x379 and the 8px gap between them.
    --}}
    <div class="marquee mt-[clamp(2.5rem,4.51vw,78px)] w-full"
         style="--marquee-duration:{{ count($purpose['strip']) * 4 }}s">
        <div class="marquee__track">
            @foreach ([false, true] as $isClone)
                <ul class="flex shrink-0 items-stretch gap-2 pr-2" @if ($isClone) aria-hidden="true" @endif>
                    @foreach ($purpose['strip'] as $image)
                        <li class="relative aspect-[426/379] w-[clamp(220px,24.65vw,426px)] shrink-0 overflow-hidden">
                            <img src="{{ asset($image['src']) }}" alt="{{ $isClone ? '' : $image['alt'] }}"
                                 @if ($isClone) loading="lazy" @endif decoding="async"
                                 class="absolute inset-0 h-full w-full object-cover">
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    </div>
</section>
