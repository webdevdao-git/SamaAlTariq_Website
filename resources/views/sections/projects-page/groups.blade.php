@php
    $page = config('site.projects_page');

    /*
     * One counter across every tile on the page, not one per row. The
     * reference staggers its whole project collection by a flat 40ms, so the
     * cascade runs continuously down the page instead of restarting — that
     * unbroken ripple is the effect, and a per-row counter would break it into
     * separate little arrivals.
     */
    $tile = 0;
@endphp

{{--
    The projects, group by group, from Figma frame 1402:2.

    COMPOSITION. Each group is a stack of rows, each row a set of columns, each
    column one tile or two stacked. The column widths come from the frame as
    fractions — 992fr 552fr, 772fr 772fr, 620fr 924fr — and go straight into
    grid-template-columns, so a row keeps the frame's split at every width. The
    twelve-track spans this replaces could not express them: 992 of 1568 is
    7.59 tracks, so 7/5 was always going to miss by 87px.

    HEIGHTS come from each picture's own box in the frame and nothing else.
    There is no shared height and no shared ratio: 992x727 beside 552x332
    stacked, then 772x635, then 620x332 beside 924x727, then 772x727 twice. The
    columns of a row still land level because the frame's numbers do —
    727+12+27 = 766 = 371+24+371 — and because everything but the caption is
    proportional to the column, that stays true as the page narrows.

    Each group opens with its own hairline, as the frame draws it: LINE 7 at
    the top of 1443:534, 1443:536, 1443:1099 and 1443:1130. 0.5px of #171717,
    rendered as 1px at half strength so it survives a 1x screen.

    NO DRIFT ON THESE PICTURES. The parallax needs an oversized image to slide
    within its frame, and an oversized image is a re-crop: the covers are the
    frame's own exports and already carry the designer's crop exactly, so any
    slack would throw part of it away. The reveal and the hover push stay —
    neither changes the resting crop.

    BOTH VIEWS ARE RENDERED and [data-project-view] decides which is shown, the
    same arrangement the reference uses. Switching is then a repaint rather
    than a fetch, and the covers are the same files either way so the browser
    holds one copy.
--}}
<div data-project-view="gallery">
    @foreach ($page['groups'] as $group)
        {{-- 80 to the next group's line, which is the frame's outer stack. --}}
        <section class="bg-white pb-[clamp(2.5rem,4.63vw,80px)]">
            {{-- 79 left and 81 right, which is what the frame actually draws: its
             padding is 79 either side but its content children are fixed at
             1568, so the two spare pixels fall on the right. Matching the
             padding alone would make every column and every picture a pixel
             larger than the frame's. Scoped to this page — the About frame
             gutters at 80, which is what the global shell carries. --}}
        <div class="shell pl-[clamp(1.25rem,4.572vw,79px)] pr-[clamp(1.25rem,4.688vw,81px)]">
                {{-- Draws left to right rather than fading — the reference sets
                     its rules to scaleX(0) and runs them out on an expo.

                     -mb-px because a LINE in Figma has no height: the frame
                     puts 40 between the line and the heading, and a 1px box
                     would spend one of them, which is the drift that used to
                     accumulate down the page. The line still paints. --}}
                <span aria-hidden="true" class="reveal-line -mb-px block h-px w-full bg-ink/50"></span>

                {{-- 40 from the line, 32/44 Manrope Medium, then 40 to the grid.
                     1.375 rather than the frame's 1.366: it is the leading that
                     lands the box on the frame's own 44. --}}
                <h2 class="reveal mt-[clamp(1.25rem,2.315vw,40px)] text-[clamp(1.25rem,1.852vw,32px)] font-medium leading-[1.375] text-ink">
                    {{ $group['name'] }}
                </h2>

                <div class="project-gallery mt-[clamp(1.25rem,2.315vw,40px)] flex flex-col gap-[clamp(1rem,1.389vw,24px)]">
                    @foreach ($group['rows'] as $row)
                        {{-- The fractions are the frame's own column widths. One
                             column below lg: at phone width a 620/924 split
                             leaves the narrow picture too small to read. --}}
                        <div class="grid gap-[clamp(1rem,1.389vw,24px)] lg:grid-cols-[var(--cols)]"
                             style="--cols:{{ collect($row['columns'])->map(fn ($c) => $c['fr'] . 'fr')->implode(' ') }}">
                            @foreach ($row['columns'] as $column)
                                <div class="flex flex-col gap-[clamp(1rem,1.389vw,24px)]">
                                    @foreach ($column['tiles'] as $item)
                                        @php($delay = $tile++ * 40)
                                        <figure class="reveal-rise group flex flex-col"
                                                style="transition-delay:{{ $delay }}ms">
                                            {{-- The picture's box in the frame. The
                                                 export is that box at 2x, so cover
                                                 has nothing to crop and there is no
                                                 object-position to set. --}}
                                            <div class="relative w-full overflow-hidden" style="aspect-ratio:{{ $item['ratio'] }}">
                                                <img src="{{ asset('images/projects/covers/' . $item['image'] . '.webp') }}"
                                                     alt="{{ $item['title'] }} — {{ $item['location'] }}"
                                                     loading="lazy" decoding="async"
                                                     class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.03]">
                                            </div>

                                            {{-- 12 below the picture; title left, category
                                                 right on one baseline, both 20/27
                                                 Manrope SemiBold, the category at 60%.
                                                 1.35 is the leading that lands the
                                                 caption box on the frame's 27. --}}
                                            <figcaption class="mt-[clamp(0.5rem,0.694vw,12px)] flex items-baseline justify-between gap-[clamp(0.5rem,0.347vw,6px)]">
                                                <span class="text-[clamp(0.875rem,1.157vw,20px)] font-semibold leading-[1.35] text-ink">{{ $item['title'] }}</span>
                                                <span class="shrink-0 text-[clamp(0.875rem,1.157vw,20px)] font-semibold leading-[1.35] text-ink-muted">{{ $item['category'] }}</span>
                                            </figcaption>
                                        </figure>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                {{-- List. Not a <table>: nothing here is compared cell against
                     cell, it is a list of projects that each carry four facts,
                     so the facts sit in a definition list per row. --}}
                <ul class="project-list mt-[clamp(1rem,1.389vw,24px)]">
                    @foreach ($group['rows'] as $row)
                        @foreach ($row['columns'] as $column)
                            @foreach ($column['tiles'] as $item)
                                <li class="reveal border-t border-black/10 transition-colors duration-300 last:border-b hover:bg-black/[0.03]">
                                    <div class="flex flex-col gap-1 py-[clamp(1rem,1.39vw,24px)] md:grid md:grid-cols-[1fr_auto_auto_auto] md:items-baseline md:gap-[clamp(1.5rem,2.31vw,40px)]">
                                        <p class="text-fluid-body font-medium text-ink">{{ $item['title'] }}</p>
                                        <dl class="contents">
                                            <dt class="sr-only">Category</dt>
                                            <dd class="text-fluid-sm text-ink-muted md:w-[clamp(120px,11vw,190px)]">{{ $item['category'] }}</dd>
                                            <dt class="sr-only">Area</dt>
                                            <dd class="text-fluid-sm text-ink-muted md:w-[clamp(90px,8vw,140px)]">{{ $item['size'] }}</dd>
                                            <dt class="sr-only">Duration</dt>
                                            <dd class="text-fluid-sm text-ink-muted md:w-[clamp(70px,6vw,100px)] md:text-right">{{ $item['duration'] }}</dd>
                                        </dl>
                                    </div>
                                </li>
                            @endforeach
                        @endforeach
                    @endforeach
                </ul>
            </div>
        </section>
    @endforeach
</div>
