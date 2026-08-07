@php($services = config('site.services'))

{{--
    Figma: frame 1224:548, 1728×2510.
    The file stacks two flattened 1728×980 panels that differ only in which tab
    pill is active and which headline shows — two states of one tabbed
    switcher, not two blocks. Built here as the switcher, with all six tab
    labels from the design.
--}}
<section id="services" class="bg-white pt-[clamp(3rem,4.63vw,80px)]">
    <div class="shell">
        <div class="reveal flex flex-col gap-[clamp(1rem,3vw,52px)] pb-[clamp(2.5rem,5.79vw,100px)] lg:flex-row lg:items-start lg:justify-between">
            <p class="shrink-0 text-fluid-label font-medium text-teal">{{ $services['label'] }}</p>
            <h2 class="display max-w-[922px] text-fluid-h2 leading-[1.3] text-ink lg:w-[59%]">{{ $services['heading'] }}</h2>
        </div>
    </div>

    <div class="reveal relative isolate w-full overflow-hidden" data-services>
        <div class="relative min-h-[clamp(420px,56.7vw,980px)] w-full">
            @foreach ($services['items'] as $i => $item)
                <img src="{{ asset($item['image']) }}" alt="" loading="lazy" decoding="async"
                     data-service-image="{{ $i }}"
                     @if ($i !== 0) aria-hidden="true" @endif
                     class="absolute inset-0 h-full w-full object-cover transition-opacity duration-[900ms] ease-out {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}">
            @endforeach

            <div aria-hidden="true" class="absolute inset-0"
                 style="background:linear-gradient(180deg,rgba(0,0,0,0.55) 0%,rgba(0,0,0,0.15) 45%,rgba(0,0,0,0.35) 100%)"></div>

            <div class="relative flex min-h-[clamp(420px,56.7vw,980px)] flex-col gap-[clamp(1.5rem,4.7vw,80px)] px-[var(--spacing-gutter)] py-[clamp(2rem,5.5vw,96px)]">
                <div role="tablist" aria-label="Our areas of expertise"
                     class="-mx-[var(--spacing-gutter)] flex snap-x gap-1 overflow-x-auto px-[var(--spacing-gutter)] pb-1 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    @foreach ($services['items'] as $i => $item)
                        <button type="button" role="tab" id="service-tab-{{ $i }}"
                                data-service-tab="{{ $i }}"
                                aria-selected="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="service-panel"
                                class="shrink-0 snap-start rounded-full px-[clamp(0.9rem,1.3vw,22px)] py-[clamp(0.45rem,0.7vw,12px)] text-[clamp(0.75rem,0.93vw,16px)] font-medium whitespace-nowrap transition-colors duration-300 {{ $i === 0 ? 'bg-white text-ink' : 'text-white/85 hover:bg-white/15 hover:text-white' }}">
                            {{ $item['tab'] }}
                        </button>
                    @endforeach
                </div>

                <h3 id="service-panel" role="tabpanel" aria-labelledby="service-tab-0"
                    class="display text-[clamp(1.5rem,2.55vw,44px)] uppercase text-white">
                    <span class="block">
                        <span data-service-title-1>{{ $services['items'][0]['title'][0] }}</span>
                        <sup data-service-number class="ml-2 align-super text-[0.42em] tracking-wide">(01)</sup>
                    </span>
                    <span data-service-title-2 class="block">{{ $services['items'][0]['title'][1] }}</span>
                </h3>
            </div>
        </div>
    </div>

    <div class="flex justify-center py-[clamp(2.5rem,4.63vw,80px)]">
        <a href="{{ $services['cta']['href'] }}" class="pill group">
            {{ $services['cta']['label'] }}
            <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
        </a>
    </div>
</section>

@push('data')
    <script type="application/json" id="services-data">@json(array_map(fn ($i) => $i['title'], $services['items']))</script>
@endpush
