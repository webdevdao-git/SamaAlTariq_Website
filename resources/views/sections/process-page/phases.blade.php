@php($phases = config('site.process_page.phases'))

{{--
    Figma 1510:445 and the three blocks that repeat it, each a pair of bands.

    THE PICTURE BAND, 1728x670 with 80 all round: a 653 column against an
    835x510 picture, 80 apart — 653 + 80 + 835 is the 1568 content column, so
    the two go in as those fractions. The column is the picture's height with
    the phase label at its top and the title and lead at its foot, which is
    what puts the lead's last line on the foot of the photograph.

    THE POINTS BAND, 1728x319 on #F9F9F9: four 332 columns with a teal rule
    standing between them, 40 either side of it — 4x332 + 3x80 is the same
    1568. Each point is a teal dot, its title, then its copy.
--}}
@foreach ($phases as $i => $phase)
    <section class="bg-white py-[clamp(2.5rem,4.63vw,80px)]">
        <div class="shell">
            <div class="grid gap-[clamp(2rem,4.63vw,80px)] lg:grid-cols-[653fr_835fr]">

                {{-- Label at the top, title and lead at the foot. --}}
                <div class="flex flex-col justify-between gap-[clamp(1.5rem,2.31vw,40px)]">
                    <p class="reveal text-[clamp(1.25rem,1.62vw,28px)] font-medium leading-[1.357] text-teal">{{ $phase['label'] }}</p>

                    <div class="flex flex-col gap-[clamp(1.5rem,2.31vw,40px)]">
                        {{-- 64/60 Juana Alt Medium, so the leading is tighter
                             than the type: two lines close up into a block.

                             Upper, like every other display heading the site
                             sets — the word on this page's own hero, the one on
                             its consultation band, and the ten service titles.
                             These phase titles were the only ones left in
                             sentence case. --}}
                        <h2 class="font-display text-[clamp(1.75rem,3.7vw,64px)] font-medium uppercase leading-[0.9375] tracking-normal text-ink">
                            @foreach ($phase['title'] as $line)
                                <span data-split data-split-delay="{{ $loop->index * 110 }}" class="block">{{ $line }}</span>
                            @endforeach
                        </h2>

                        <p class="reveal max-w-[588px] text-[clamp(1.125rem,1.389vw,24px)] font-medium leading-[1.375] text-ink"
                           style="transition-delay:140ms">{{ $phase['lead'] }}</p>
                    </div>
                </div>

                <div class="reveal-rise relative w-full overflow-hidden" style="aspect-ratio:835/510">
                    <img src="{{ \App\Support\Asset::versioned($phase['image']) }}" alt="" loading="lazy" decoding="async"
                         class="h-full w-full object-cover">
                </div>
            </div>
        </div>
    </section>

    {{-- No rule between the last phase and its own points. One was drawn here,
         read off the frame as belonging above the last of these bands — but it
         lands inside the phase, cutting its picture band off from the four
         points that belong to it, which is not a division the page has. --}}
    <section class="bg-[#F9F9F9] py-[clamp(2.5rem,4.63vw,80px)]">
        <div class="shell">
            {{-- 80 between columns, not the 40 the frame's auto-layout reports:
                 that 40 is the space either side of the rule standing between
                 them, and 4x332 + 3x80 is the 1568 column. --}}
            <div class="grid gap-[clamp(1.5rem,4.63vw,80px)] sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($phase['items'] as $j => $item)
                    <div class="reveal relative flex flex-col gap-[clamp(0.75rem,0.926vw,16px)]"
                         style="transition-delay:{{ $j * 90 }}ms">
                        {{-- The rule belongs to the point it precedes, so a row
                             that wraps on a narrow screen never leaves one
                             hanging at the end of a line. 75 tall, teal, set
                             42 down to sit against the title rather than the
                             dot, which is where the frame draws it. --}}
                        @if ($j > 0)
                            <span aria-hidden="true"
                                  class="absolute -left-[clamp(0.75rem,2.315vw,40px)] top-[clamp(1.5rem,2.43vw,42px)] hidden h-[clamp(48px,4.34vw,75px)] w-px bg-teal lg:block"></span>
                        @endif

                        {{-- Smaller than the title it heads rather than the
                             same size as it: the dot was set at 1.273vw, which
                             is the title's own 22, and read as a bullet with
                             the weight of a heading. 0.81vw puts it at 14. --}}
                        <span aria-hidden="true"
                              class="block h-[clamp(9px,0.81vw,14px)] w-[clamp(9px,0.81vw,14px)] rounded-full bg-teal"></span>

                        <p class="text-[clamp(1rem,1.273vw,22px)] font-bold leading-[1.364] text-ink">{{ $item['title'] }}</p>
                        <p class="text-[clamp(0.875rem,1.042vw,18px)] font-medium leading-[1.389] text-ink">{{ $item['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endforeach
