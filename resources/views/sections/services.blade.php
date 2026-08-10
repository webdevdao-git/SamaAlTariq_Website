@php($services = config('site.services'))

{{--
    Our Expertise.

    Figma stacks two flattened 1728×980 panels that differ only in which tab
    pill is active — two states of one component. The interaction model here is
    modelled on mino.works: instead of clicking to swap content in place, each
    service is its own full-viewport panel and scrolling moves between them.
    Every panel carries the same tab row with its own pill active, so the row
    doubles as a position indicator and as navigation.

    That makes the whole thing work without JavaScript: the "active" state is
    just markup, and the tabs are anchor links. Lenis eases the jump when it is
    running; native anchor scrolling handles it when it is not.
--}}
<section id="services" class="bg-white pt-[clamp(3rem,4.63vw,80px)]">
    <div class="shell">
        <div class="reveal flex flex-col gap-[clamp(1rem,3vw,52px)] pb-[clamp(2.5rem,5.79vw,100px)] lg:flex-row lg:items-start lg:justify-between">
            <p class="shrink-0 text-fluid-label font-medium text-teal">{{ $services['label'] }}</p>
            <h2 class="display max-w-[922px] text-fluid-h2 leading-[1.3] text-ink lg:w-[59%]">{{ $services['heading'] }}</h2>
        </div>
    </div>

    @foreach ($services['items'] as $i => $item)
        @php($number = str_pad($i + 1, 2, '0', STR_PAD_LEFT))

        <article id="service-{{ $i + 1 }}"
                 class="relative isolate flex min-h-[100svh] w-full flex-col overflow-hidden
                        px-[var(--spacing-gutter)] py-[clamp(2rem,5vw,88px)]"
                 aria-labelledby="service-heading-{{ $i + 1 }}">

            <img src="{{ asset($item['image']) }}" alt=""
                 loading="{{ $i === 0 ? 'eager' : 'lazy' }}" decoding="async"
                 class="absolute inset-0 -z-20 h-full w-full object-cover">

            <div aria-hidden="true" class="absolute inset-0 -z-10"
                 style="background:linear-gradient(180deg,rgba(0,0,0,0.55) 0%,rgba(0,0,0,0.15) 45%,rgba(0,0,0,0.5) 100%)"></div>

            {{--
                The row repeats in every panel as a visual device, but there is
                only one navigation here. The first copy is the real one; the
                rest are hidden from assistive tech and taken out of the tab
                order, so a screen reader or keyboard user gets one clean list
                instead of the same six links six times over.
            --}}
            <nav class="-mx-[var(--spacing-gutter)] flex snap-x items-center gap-[clamp(1.25rem,4vw,72px)] overflow-x-auto px-[var(--spacing-gutter)] pb-1
                        [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
                 @if ($i === 0) aria-label="Our areas of expertise" @else aria-hidden="true" @endif>
                @foreach ($services['items'] as $j => $tab)
                    <a href="#service-{{ $j + 1 }}"
                       @if ($i !== 0) tabindex="-1" @endif
                       @if ($i === $j) aria-current="true" @endif
                       class="shrink-0 snap-start rounded-full border px-[clamp(1.25rem,2.2vw,38px)] py-[clamp(0.55rem,0.82vw,14px)]
                              text-[clamp(0.875rem,1.16vw,20px)] font-medium whitespace-nowrap backdrop-blur-md transition duration-300
                              {{ $i === $j ? 'border-white/25 bg-white/15 text-white shadow-[inset_0_1px_0_rgba(255,255,255,0.32),0_18px_36px_-26px_rgba(0,0,0,0.85)]' : 'border-transparent text-white/85 hover:border-white/20 hover:bg-white/10 hover:text-white' }}">
                        {{ $tab['tab'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto flex flex-col gap-[clamp(0.75rem,1.4vw,24px)]">
                <h3 id="service-heading-{{ $i + 1 }}"
                    class="display text-[clamp(1.5rem,2.55vw,44px)] uppercase text-white">
                    <span class="block">
                        {{ $item['title'][0] }}<sup class="ml-2 align-super text-[0.42em] tracking-wide">({{ $number }})</sup>
                    </span>
                    <span class="block">{{ $item['title'][1] }}</span>
                </h3>

                <p class="max-w-[46ch] text-fluid-body font-medium text-white/80">{{ $item['description'] }}</p>
            </div>
        </article>
    @endforeach

    <div class="flex justify-center py-[clamp(2.5rem,4.63vw,80px)]">
        <a href="{{ $services['cta']['href'] }}" class="pill group">
            {{ $services['cta']['label'] }}
            <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
        </a>
    </div>
</section>
