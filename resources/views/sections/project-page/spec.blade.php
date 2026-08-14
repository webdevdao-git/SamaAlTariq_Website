@php
    /*
     * The frame's rows, in its order and under its labels. A row with nothing
     * to say is not drawn: Villa B200 has no published area or duration, and
     * only the project the frame names carries a year.
     */
    $rows = array_filter([
        'About' => $page['about'] ?? null,
        'Location' => $project['location'] ?? null,
        'Project Duration' => $project['duration'] ?? null,
        'Year' => $page['year'] ?? null,
        'Area' => $project['size'] ?? null,
    ]);
@endphp

{{--
    Figma 1472:1374, 1728x940 on #F9F9F9, 100 top and bottom.

    Label against value: 561 + 24 + 983 is the 1568 column. Each row is 33 of
    content, 24 down to its hairline, then 40 to the next — and the frame draws
    two hairlines, a full-width one at 24% and a second at full strength under
    the value column alone. Both are here; one without the other reads as a
    different table.

    The closing row is the overview, which the frame labels "AREA" a second
    time over two paragraphs of prose. That is a copy-paste in the file rather
    than an instruction, so the label reads Overview and the row keeps the
    frame's shape: no hairline under it, 24 between the paragraphs.
--}}
<section class="bg-[#F9F9F9] py-[clamp(3rem,5.79vw,100px)]">
    <div class="shell">
        <dl class="flex flex-col gap-[clamp(1.5rem,2.31vw,40px)]">
            @foreach ($rows as $label => $value)
                <div class="reveal">
                    <div class="grid gap-[clamp(0.5rem,1.389vw,24px)] lg:grid-cols-[561fr_983fr]">
                        <dt class="text-[clamp(1rem,1.157vw,20px)] font-bold leading-[1.35] text-ink">{{ $label }}</dt>
                        <dd class="text-[clamp(1.125rem,1.389vw,24px)] font-medium leading-[1.375] text-ink">{{ $value }}</dd>
                    </div>

                    {{-- The pair of hairlines: 24 under the row, the darker one
                         laid over the value column's width. -mb-px for the same
                         reason the projects page carries it — a LINE in Figma
                         has no height, and a 1px box would push every row below
                         it down by one.

                         Both draw in from the left as the row arrives, which is
                         the reference's behaviour for this table: it holds its
                         two lines translated fully off and runs them in on
                         scroll. Same lines, same weights, same colours — they
                         arrive rather than being there. --}}
                    <div class="relative -mb-px mt-[clamp(1rem,1.389vw,24px)] h-px w-full">
                        <span aria-hidden="true"
                              class="reveal-line absolute inset-0 block bg-black/[0.24]"
                              style="transition-delay:{{ $loop->index * 90 }}ms"></span>
                        <span aria-hidden="true"
                              class="reveal-line absolute inset-y-0 right-0 hidden bg-black lg:block lg:w-[62.6%]"
                              style="transition-delay:{{ 120 + $loop->index * 90 }}ms"></span>
                    </div>
                </div>
            @endforeach

            <div class="reveal grid gap-[clamp(0.5rem,1.389vw,24px)] lg:grid-cols-[561fr_983fr]">
                <dt class="text-[clamp(1rem,1.157vw,20px)] font-bold leading-[1.35] text-ink">Overview</dt>
                <dd class="flex flex-col gap-[clamp(1rem,1.389vw,24px)]">
                    @foreach ($page['body'] as $paragraph)
                        <p class="text-[clamp(1.125rem,1.389vw,24px)] font-medium leading-[1.375] text-ink">{{ $paragraph }}</p>
                    @endforeach
                </dd>
            </div>
        </dl>
    </div>
</section>
