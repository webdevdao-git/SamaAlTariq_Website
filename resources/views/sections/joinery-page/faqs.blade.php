@php($band = config('site.joinery_page.faqs'))

{{--
    The dark band that closes the frame: label and heading on the left, the
    questions stacked on the right, each a row with a rule under it and a mark
    at the end of the line.

    Written as <details>. The frame draws them closed with a plus at the right,
    which is a disclosure — the element is what makes it one without
    JavaScript, and what a screen reader announces as one. The marker is hidden
    because the design draws its own, and it turns from + to − on open.
--}}
<section class="bg-night py-[clamp(3.5rem,5.79vw,100px)] text-white">
    <div class="shell">
        <div class="grid gap-[clamp(2rem,3.7vw,64px)] lg:grid-cols-2 lg:gap-[clamp(2.5rem,4.63vw,80px)]">

            <div class="reveal flex flex-col gap-[clamp(0.75rem,1.16vw,20px)]">
                <p class="text-fluid-sm font-medium text-teal">{{ $band['label'] }}</p>
                <h2 class="display max-w-[12em] text-fluid-h2 leading-[1.3]">
                    @foreach ($band['heading'] as $line)
                        <span class="block">{{ $line }}</span>
                    @endforeach
                </h2>
            </div>

            <div class="flex flex-col">
                @foreach ($band['items'] as $faq)
                    <details class="reveal group border-b border-white/15" style="transition-delay:{{ $loop->index * 80 }}ms">
                        <summary class="flex cursor-pointer list-none items-start justify-between gap-6 py-[clamp(1rem,1.62vw,28px)] text-fluid-body font-medium transition-colors hover:text-teal [&::-webkit-details-marker]:hidden">
                            {{ $faq['q'] }}
                            {{-- Drawn rather than a glyph: a + built from two
                                 rules, the upright of which is scaled away when
                                 the row opens, so it becomes a − without
                                 swapping any characters. --}}
                            <span aria-hidden="true" class="relative mt-[0.45em] block size-3 shrink-0">
                                <span class="absolute top-1/2 left-0 h-px w-full -translate-y-1/2 bg-current"></span>
                                <span class="absolute top-0 left-1/2 h-full w-px -translate-x-1/2 bg-current transition-transform duration-300 group-open:scale-y-0"></span>
                            </span>
                        </summary>

                        <p class="max-w-[52ch] pb-[clamp(1rem,1.62vw,28px)] text-fluid-sm font-medium text-white/70">{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</section>
