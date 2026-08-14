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

    /*
     * Literal class strings, never "lg:col-span-" . $n. Tailwind finds classes
     * by scanning the source for them, so an interpolated name is never
     * generated and the column silently falls back to full width.
     */
    $span = [
        5 => 'lg:col-span-5',
        6 => 'lg:col-span-6',
        7 => 'lg:col-span-7',
    ];
@endphp

{{--
    The projects, group by group.

    COMPOSITION. Each group is a stack of rows, each row a set of columns on a
    twelve-track grid, each column one tile or two stacked. The tiles in a row
    share its height, which is what gives the design its rhythm — a tall
    picture beside a pair, then two equal ones. A flat grid with row-spans
    cannot express that: it leaves a hole wherever a group runs out of items,
    which is exactly what the first attempt did under Commercial & Corporate.

    The height comes from the tallest column and the pictures fill it, so the
    two halves of a row always align at top and bottom however differently
    their photographs are proportioned.

    BOTH VIEWS ARE RENDERED and [data-project-view] decides which is shown, the
    same arrangement the reference uses — its list items sit in the DOM beside
    its gallery items. Switching is then a repaint rather than a fetch, and the
    covers are the same files either way so the browser holds one copy.

    MOTION. Per-tile, not per-group: each picture fades and settles on its own
    short delay so a row assembles rather than appearing whole, which is the
    reference's `js-t-fade-in-project` behaviour. Delays restart per row so a
    long group never builds up a laggard tail.
--}}
<div data-project-view="gallery">
    @foreach ($page['groups'] as $group)
        <section class="bg-white pt-[clamp(3rem,5.79vw,100px)] pb-[clamp(1.5rem,2.31vw,40px)]">
            <div class="shell">
                <h2 class="reveal text-fluid-label font-medium text-ink">{{ $group['name'] }}</h2>

                {{-- Gallery --}}
                <div class="project-gallery mt-[clamp(1.5rem,2.31vw,40px)] flex flex-col gap-[clamp(1rem,1.39vw,24px)]">
                    @foreach ($group['rows'] as $row)
                        @php($hasStack = collect($row['columns'])->contains(fn ($c) => count($c['tiles']) > 1))
                        <div class="grid gap-[clamp(1rem,1.39vw,24px)] lg:grid-cols-12 lg:items-stretch">
                            @foreach ($row['columns'] as $column)
                                <div class="flex flex-col gap-[clamp(1rem,1.39vw,24px)] {{ $span[$column['cols']] }}">
                                    @foreach ($column['tiles'] as $item)
                                        @php($delay = $tile++ * 40)
                                        <figure class="reveal-rise group flex flex-1 flex-col"
                                                style="transition-delay:{{ $delay }}ms">
                                            {{--
                                                One height for every single tile on the page, so a
                                                category never sits at a different scale from the one
                                                above it. It is a height and not an aspect ratio on
                                                purpose: the columns are 5, 6 and 7 tracks wide, so a
                                                shared ratio would give three different heights, and a
                                                shared height is what reads as one grid.

                                                A tile that stands alone in its column takes the unit.
                                                A tile that stands beside a stacked pair fills the
                                                column instead — two units, their gap, and the caption
                                                between them — which is what lands its foot on the same
                                                line as the pair's lower picture, as the frame draws it.

                                                THE UNIT IS 440 AT 1728, not 300. At 300 a six-track
                                                tile was 772x300 — 2.57:1 — and the photographs are
                                                1.78:1 and flatter, so every picture was cover-cropped
                                                to a band and the Fitness gym and spa read as slices of
                                                a room rather than the room. 440 puts a six-track tile
                                                at 1.75:1, which is the gym photograph's own proportion,
                                                so the widest pictures on the page are now shown whole
                                                and the squarer ones lose far less.

                                                It also closes the gap against the frame: the page was
                                                measured at 1728:5596 (3.24) against the frame's 3.95,
                                                i.e. ~1230px short, and the seven picture units on the
                                                page carry almost exactly that between them.
                                            --}}
                                            <div class="relative w-full overflow-hidden {{ count($column['tiles']) > 1 ? 'aspect-[16/10] lg:aspect-auto' : 'aspect-[4/3] lg:aspect-auto' }} {{ count($row['columns']) > 1 && count($column['tiles']) === 1 && $hasStack ? 'flex-1' : '' }} lg:h-[clamp(260px,25.46vw,440px)] {{ $hasStack && count($column['tiles']) === 1 ? 'lg:h-auto' : '' }}">
                                                {{-- The hover push sits on this wrapper, not on the
                                                     picture. .reveal-media already owns the picture's
                                                     transform and transition, and a `transition-transform`
                                                     utility beside it would replace that shorthand and
                                                     take the settle-in with it. Two elements, one
                                                     transform each. --}}
                                                {{--
                                                    Three layers, one transform each, because two of them
                                                    would otherwise fight over the same property:

                                                    · the drift layer is 108% of the frame and hung at
                                                      -4%. Because the slack is in the markup the drift
                                                      is a plain translate and nothing is enlarged —
                                                      hand the picture the frame's own size instead and
                                                      it has to buy the headroom by scaling, which
                                                      re-crops every photograph on the page. The slack
                                                      is 8% and not the reference's 20% because slack is
                                                      crop: the picture is composed for a box that much
                                                      taller than the frame and the frame only ever
                                                      shows the middle of it. At 20% a fifth of every
                                                      photograph was spent on a ±40px drift; 8% still
                                                      reads as movement and gives the other 12% back to
                                                      the picture;
                                                    · the hover layer takes the push, so the pointer and
                                                      the scroll never write to the same element;
                                                    · the picture just fills.
                                                --}}
                                                <div data-drift="0.04" class="absolute inset-x-0 -top-[4%] h-[108%]">
                                                    <div class="h-full w-full transition-transform duration-700 ease-out group-hover:scale-[1.03]">
                                                        <img src="{{ asset('images/projects/covers/' . $item['image'] . '.webp') }}"
                                                             alt="{{ $item['title'] }} — {{ $item['location'] }}"
                                                             loading="lazy" decoding="async"
                                                             class="h-full w-full object-cover">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Title left, category right, on one baseline. --}}
                                            <figcaption class="mt-[clamp(0.5rem,0.7vw,12px)] flex items-baseline justify-between gap-4">
                                                <span class="text-fluid-sm font-medium text-ink">{{ $item['title'] }}</span>
                                                <span class="shrink-0 text-fluid-sm text-ink-muted">{{ $item['category'] }}</span>
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
                <ul class="project-list mt-[clamp(1rem,1.39vw,24px)]">
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
