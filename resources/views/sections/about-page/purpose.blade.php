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
        <h2 class="editorial-heading text-fluid-mega uppercase text-ink">
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

    {{-- The strip reveals picture by picture rather than as one band, left to
         right, which is the direction the eye is already travelling. --}}
    <ul class="mt-[clamp(2.5rem,4.51vw,78px)] grid w-full grid-cols-2 gap-2 md:grid-cols-4">
        @foreach ($purpose['strip'] as $image)
            <li class="relative aspect-[426/379] overflow-hidden">
                <img src="{{ asset($image['src']) }}" alt="{{ $image['alt'] }}"
                     loading="lazy" decoding="async"
                     class="reveal-media absolute inset-0 h-full w-full object-cover"
                     style="transition-delay:{{ $loop->index * 100 }}ms">
            </li>
        @endforeach
    </ul>
</section>
