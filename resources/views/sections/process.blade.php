@php($process = config('site.process'))

{{--
    Our Process.

    Layout follows the Figma frame (1226:731): two columns, "OUR PROCESS" at the
    top-left with the numbered step pinned to the bottom of that column, and a
    full-height image on the right.

    The interaction is unchanged — the text column sticks while the step images
    scroll past it, and the copy cross-fades as each image reaches the text.
    Measured off concept-interiors-pearl.vercel.app: the blocks blend
    continuously rather than snapping, so opacity is a function of each image's
    distance from the text, not of an active index.

    Progressive enhancement: this markup is a plain, readable list of four steps
    beside four images. `initProcessScroll()` adds `is-stacked`, which is what
    collapses the steps for the cross-fade. Without JavaScript, under
    prefers-reduced-motion, or below `lg` where the column is not sticky,
    nothing stacks and all four steps simply read in order.
--}}
<section id="process" class="bg-white py-[clamp(3.5rem,5.79vw,100px)]" data-process>
    <div class="shell">
        <div class="grid gap-[clamp(2rem,3.7vw,64px)] lg:grid-cols-2">

            {{--
                Sticky text column. `justify-between` is what puts the heading
                against the top and the step against the bottom, so it needs a
                height to work against — hence the min-height, matched to the
                image cap below.
            --}}
            <div class="flex flex-col justify-between gap-[clamp(2.5rem,5vw,80px)] self-start lg:sticky lg:top-[12vh] lg:min-h-[72vh]">
                {{-- Upper, with the steps below it left in the case their copy
                     is written in. The two were taken out of capitals together
                     — a step set upper under a heading that is not reads as the
                     louder of the two — but only the heading was asked back,
                     and a slab over a title-case step is the order they belong
                     in anyway. It is also what the other section headings on
                     the page do. --}}
                <h2 class="reveal editorial-heading text-fluid-section uppercase text-ink">
                    @foreach ($process['heading'] as $line)
                        <span class="block">{{ $line }}</span>
                    @endforeach
                </h2>

                <div data-process-stack class="relative">
                    @foreach ($process['steps'] as $i => $step)
                        <div data-process-step="{{ $i }}"
                             class="process-step flex flex-col gap-[clamp(0.5rem,0.7vw,12px)]">
                            <p class="display text-[clamp(1.35rem,1.85vw,32px)] text-teal">{{ $step['number'] }}</p>
                            {{-- Title case, with the section heading above it:
                                 a step set upper under a heading that is not
                                 reads as the louder of the two, which inverts
                                 the order they belong in. The process page
                                 still sets these four phase names upper. --}}
                            <h3 class="display text-[clamp(1.5rem,2.2vw,38px)] text-ink">{{ $step['title'] }}</h3>
                            <p class="max-w-[46ch] text-fluid-body font-medium text-ink-muted">{{ $step['body'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Image column --}}
            <div class="flex flex-col gap-[6vh]">
                @foreach ($process['steps'] as $i => $step)
                    {{-- The picture's own proportions, not a frame of the
                         section's: cut to 752/819 these lost the half of each
                         photograph that says where the work is.

                         The width is capped as well as the height, and to the
                         same 72vh through the ratio. A height cap alone does
                         not hold a ratio — the box keeps its full width and
                         clips instead — so on a window shorter than about 780
                         the crop came back. Capped both ways the box shrinks
                         whole. --}}
                    <figure data-process-image="{{ $i }}"
                            class="relative mx-auto max-h-[72vh] w-full overflow-hidden bg-mist"
                            style="aspect-ratio:{{ $step['ratio'] }};max-width:calc(72vh * ({{ $step['ratio'] }}))">
                        {{-- Versioned: these four were replaced with a fresh
                             set under the same names, and this host answers an
                             image with a week of cache-control — without the
                             stamp anyone who had been here would keep the old
                             ones. --}}
                        <img src="{{ \App\Support\Asset::versioned($step['image']) }}"
                             alt="{{ $step['number'] }} — {{ $step['title'] }}"
                             loading="{{ $i === 0 ? 'eager' : 'lazy' }}" decoding="async"
                             class="absolute inset-0 h-full w-full object-cover">
                    </figure>
                @endforeach
            </div>
        </div>

        {{-- Centred under both columns, which is where the frame puts it — the
             same pill the projects and services bands close with. --}}
        <div class="reveal mt-[clamp(2.5rem,4.63vw,80px)] flex justify-center">
            <a href="{{ $process['cta']['href'] }}" class="pill group">
                {{ $process['cta']['label'] }}
                <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
            </a>
        </div>
    </div>
</section>
