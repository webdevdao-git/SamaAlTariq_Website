@php
    $page = config('site.projects_page');

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
        <section class="bg-white pt-[clamp(2.5rem,4.63vw,80px)] pb-[clamp(1.5rem,2.31vw,40px)]">
            <div class="shell">
                <h2 class="reveal text-fluid-label font-medium text-ink">{{ $group['name'] }}</h2>

                {{-- Gallery --}}
                <div class="project-gallery mt-[clamp(1.5rem,2.31vw,40px)] flex flex-col gap-[clamp(1rem,1.39vw,24px)]">
                    @foreach ($group['rows'] as $row)
                        <div class="grid gap-[clamp(1rem,1.39vw,24px)] lg:grid-cols-12 lg:items-stretch">
                            @foreach ($row['columns'] as $column)
                                <div class="flex flex-col gap-[clamp(1rem,1.39vw,24px)] {{ $span[$column['cols']] }}">
                                    @foreach ($column['tiles'] as $tile)
                                        @php($delay = ($loop->parent->index * 2 + $loop->index) * 90)
                                        <figure class="group flex flex-1 flex-col">
                                            <div class="relative w-full flex-1 overflow-hidden {{ count($column['tiles']) > 1 ? 'aspect-[16/10] lg:aspect-auto' : 'aspect-[4/3] lg:aspect-auto' }} lg:min-h-[clamp(220px,20vw,340px)]">
                                                {{-- The hover push sits on this wrapper, not on the
                                                     picture. .reveal-media already owns the picture's
                                                     transform and transition, and a `transition-transform`
                                                     utility beside it would replace that shorthand and
                                                     take the settle-in with it. Two elements, one
                                                     transform each. --}}
                                                <div class="h-full w-full transition-transform duration-700 ease-out group-hover:scale-[1.03]">
                                                    <img src="{{ asset('images/projects/covers/' . $tile['image'] . '.webp') }}"
                                                         alt="{{ $tile['title'] }} — {{ $tile['location'] }}"
                                                         loading="lazy" decoding="async"
                                                         class="reveal-media h-full w-full object-cover"
                                                         style="transition-delay:{{ $delay }}ms">
                                                </div>
                                            </div>

                                            {{-- Title left, category right, on one baseline. --}}
                                            <figcaption class="reveal mt-[clamp(0.5rem,0.7vw,12px)] flex items-baseline justify-between gap-4"
                                                        style="transition-delay:{{ $delay + 60 }}ms">
                                                <span class="text-fluid-sm font-medium text-ink">{{ $tile['title'] }}</span>
                                                <span class="shrink-0 text-fluid-sm text-ink-muted">{{ $tile['category'] }}</span>
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
                            @foreach ($column['tiles'] as $tile)
                                <li class="reveal border-t border-black/10 transition-colors duration-300 last:border-b hover:bg-black/[0.03]">
                                    <div class="flex flex-col gap-1 py-[clamp(1rem,1.39vw,24px)] md:grid md:grid-cols-[1fr_auto_auto_auto] md:items-baseline md:gap-[clamp(1.5rem,2.31vw,40px)]">
                                        <p class="text-fluid-body font-medium text-ink">{{ $tile['title'] }}</p>
                                        <dl class="contents">
                                            <dt class="sr-only">Category</dt>
                                            <dd class="text-fluid-sm text-ink-muted md:w-[clamp(120px,11vw,190px)]">{{ $tile['category'] }}</dd>
                                            <dt class="sr-only">Area</dt>
                                            <dd class="text-fluid-sm text-ink-muted md:w-[clamp(90px,8vw,140px)]">{{ $tile['size'] }}</dd>
                                            <dt class="sr-only">Duration</dt>
                                            <dd class="text-fluid-sm text-ink-muted md:w-[clamp(70px,6vw,100px)] md:text-right">{{ $tile['duration'] }}</dd>
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
