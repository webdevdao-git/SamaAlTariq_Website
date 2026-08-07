@php($about = config('site.about'))

{{--
    Figma: frame 1226:693, 1728×1102, 79px inline / 100px block padding.
    Label + 48px heading, a 389×272 image at ~54% of the content width, a
    full-width hairline, then subheading + stats beside two body paragraphs.
--}}
<section id="about" class="bg-white py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">
        <div class="reveal flex flex-col gap-[clamp(1rem,3.7vw,64px)] md:flex-row md:items-start">
            <p class="shrink-0 text-fluid-label font-medium text-teal">{{ $about['label'] }}</p>

            {{-- The designed line breaks only hold at the width they were set
                 for, so below md the spans go inline and the heading wraps
                 naturally instead of breaking twice. --}}
            <h2 class="display max-w-[1042px] text-fluid-h2 leading-[1.3] text-ink">
                @foreach ($about['heading'] as $line)
                    <span class="inline md:block">{{ $line }} </span>
                @endforeach
            </h2>
        </div>

        <div class="reveal mt-[clamp(2.5rem,5.79vw,100px)] flex justify-center md:justify-start md:pl-[54%]">
            <div class="relative aspect-[389/272] w-full max-w-[389px] overflow-hidden">
                <img src="{{ asset($about['image']) }}" alt="{{ $about['alt'] }}" loading="lazy" decoding="async"
                     class="absolute inset-0 h-full w-full object-cover">
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
