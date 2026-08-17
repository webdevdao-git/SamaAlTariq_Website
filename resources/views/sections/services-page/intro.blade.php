@php($intro = config('site.services_page.intro'))

{{--
    Figma 1592:1510, 1728x596 with 100 above and below.

    Two rows with a hairline between them. The first is the label against the
    statement — 179 and 818, 64 apart, the statement set at 48/62 rather than
    the page's display size, because it is a sentence rather than a title.

    Then 100 down to a 0.5px rule at 30% across the whole column, 40 more to
    the second row: a short note and the paragraph it introduces, 786 and 607
    with only 10 between them, which leaves 165 of the column empty on the
    right. Spread apart instead — the obvious reading — and the paragraph
    lands on the gutter, which is not where the frame has it.
--}}
<section class="bg-white py-[clamp(3rem,5.79vw,100px)]">
    <div class="shell">
        <div class="flex flex-col gap-[clamp(1.5rem,3.7vw,64px)] lg:flex-row lg:items-start">
            {{-- 179 in the frame, so the statement starts at 322 whatever the
                 label's own words measure — but held on one line: our Manrope
                 sets "Our Expertise" a little wider than 179, and left to wrap
                 it broke into two lines and took the row's alignment with it.
                 The overflow runs into the 64 gap, which is empty. --}}
            <p class="reveal shrink-0 whitespace-nowrap text-[clamp(1.25rem,1.62vw,28px)] font-medium leading-[1.357] text-teal lg:w-[11.42%]">{{ $intro['label'] }}</p>

            <h2 class="reveal font-display text-[clamp(1.5rem,2.78vw,48px)] font-medium leading-[1.292] tracking-normal text-ink lg:max-w-[52.17%]"
                style="transition-delay:120ms">{{ $intro['statement'] }}</h2>
        </div>

        {{-- -mb-px so the rule costs no height: the frame's LINE has none and
             the row below it sits 40 down, not 41. --}}
        <span aria-hidden="true"
              class="reveal-line -mb-px mt-[clamp(2.5rem,5.79vw,100px)] block h-px w-full bg-ink/30"></span>

        {{-- The frame's 786 and 607 as fractions of its 1568, not as pixels:
             they add up to 1403 and leave 165 of the column empty, and written
             in pixels that arithmetic only holds at 1728 — at 1440 the pair
             came to 1401 in a 1280 column and the paragraph ran off the screen.
             The gap is the frame's 10 by the same reckoning. --}}
        <div class="mt-[clamp(1.5rem,2.315vw,40px)] flex flex-col gap-[clamp(0.5rem,0.638%,10px)] lg:flex-row">
            {{-- Title case in the frame, so the note reads as a label rather
                 than as a sentence. --}}
            <p class="reveal text-[clamp(1rem,1.157vw,20px)] font-semibold capitalize leading-[1.35] text-ink lg:w-[50.13%] lg:shrink-0">
                @foreach ($intro['note'] as $line)
                    <span class="block">{{ $line }}</span>
                @endforeach
            </p>

            <p class="reveal text-[clamp(1.125rem,1.389vw,24px)] font-medium leading-[1.375] text-ink lg:w-[38.71%] lg:shrink-0"
               style="transition-delay:140ms">{{ $intro['body'] }}</p>
        </div>
    </div>
</section>
