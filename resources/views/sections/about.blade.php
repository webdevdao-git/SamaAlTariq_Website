@php($about = config('site.about'))

{{--
    Figma: frame 1226:693, 1728×1102, 79px inline / 100px block padding.
    Label + 48px heading, a 389×272 image at ~54% of the content width, a
    full-width hairline, then subheading + stats beside two body paragraphs.
--}}
<section id="about" class="bg-white py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">
        {{--
            The frame's own two tracks rather than a label with the heading
            hung off it: 222 from the gutter to the heading — the label's 158
            and 64 beside it — then the heading's 1042, which is where its box
            ends at 1343, well short of the right gutter. 1264 of the 1568
            together, so they go in as those fractions.

            The heading breaks where the file breaks it. Three lines, typed as
            three, in the frame's own 1042 box: left to wrap it fell 2-2-1 with
            "trust." near-orphaned, and balanced it evened itself at widths the
            file says nothing about. The design draws these three, so these
            three are what it sets.
        --}}
        <div class="reveal flex flex-col gap-[clamp(1rem,3vw,52px)] md:grid md:grid-cols-[222fr_1042fr_304fr] md:items-start md:gap-0">
            <p class="shrink-0 text-fluid-label font-medium text-teal">{{ $about['label'] }}</p>

            {{-- A block per line from md, which is where the frame's two tracks
                 start and the box is wide enough to hold each of the three.
                 Below it the type is on its floor while the column keeps
                 narrowing, so the typed lines would break again and set 1-2-2;
                 inline there, the sentence wraps evenly to the phone's width
                 instead. Same pattern as the closing heading. --}}
            <h2 class="display text-fluid-h2 leading-[1.3] text-ink">
                @foreach ($about['heading'] as $line)
                    <span class="inline md:block">{{ $line }} </span>
                @endforeach
            </h2>
        </div>

        {{--
            The image sits on the same right-column grid line as the body copy
            below it, matching the Figma guides.
        --}}
        <div class="reveal mt-[clamp(2.5rem,5.79vw,100px)] grid gap-[clamp(2rem,3vw,52px)] md:grid-cols-2">
            <div class="hidden md:block"></div>
            <div>
                <div class="relative aspect-[389/272] w-full max-w-[389px] overflow-hidden">
                    <img src="{{ asset($about['image']) }}" alt="{{ $about['alt'] }}" loading="lazy" decoding="async"
                         class="absolute inset-0 h-full w-full object-cover">
                </div>
            </div>
        </div>

        <div class="mt-[clamp(2rem,2.31vw,40px)] border-t border-black/10 pt-[clamp(2rem,2.31vw,40px)]">
            <div class="grid gap-[clamp(2rem,3vw,52px)] md:grid-cols-2">
                <div class="reveal flex flex-col justify-between gap-[clamp(2rem,4.63vw,80px)]">
                    <h3 class="text-fluid-body font-semibold text-ink">
                        @foreach ($about['subheading'] as $line)
                            <span class="block">{{ $line }}</span>
                        @endforeach
                    </h3>

                    <dl class="flex flex-wrap items-center gap-[clamp(1.25rem,2.31vw,40px)]">
                        @foreach ($about['stats'] as $i => $stat)
                            <div class="flex items-center gap-[clamp(1.25rem,2.31vw,40px)]">
                                @if ($i > 0)
                                    <span aria-hidden="true" class="h-[52px] w-px bg-black/15"></span>
                                @endif
                                <div>
                                    <dt class="sr-only">{{ $stat['label'] }}</dt>
                                    <dd>
                                        <span class="block text-fluid-stat font-medium tracking-[-0.06em] text-teal">{{ $stat['value'] }}</span>
                                        <span class="mt-1 block text-fluid-body font-medium text-ink">{{ $stat['label'] }}</span>
                                    </dd>
                                </div>
                            </div>
                        @endforeach
                    </dl>
                </div>

                <div class="reveal flex flex-col gap-[1.4em]" style="transition-delay:120ms">
                    @foreach ($about['body'] as $paragraph)
                        <p class="max-w-[561px] text-fluid-lead font-medium text-ink">{{ $paragraph }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
