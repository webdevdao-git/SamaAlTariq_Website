@php
    $phases = config('site.process_page.phases');
    $img = fn (string $path) => \App\Support\Asset::versioned($path);
    // A photograph per phase, in page order.
    $bgs = [
        'phase-1' => 'images/projects/wasl-properties-hq/hero-4.webp',
        'phase-2' => 'images/projects/i-rise-tower-office/hero-2.webp',
        'phase-3' => 'images/workers/work-19.webp',
        'phase-4' => 'images/projects/emirates-hills-villa/hero-3.webp',
    ];
@endphp

{{--
    The Process page in the landing page's held-photograph layout: the four
    phases, each a window over its own photograph and a card with its steps.
--}}
<x-story :bgs="$bgs">
    @foreach ($phases as $i => $phase)
        @php($title = str_replace(' AND ', ' & ', implode(' ', $phase['title'])))
        <x-story-win :key="'phase-'.($i + 1)" :eyebrow="$phase['label']" :title="[$title]"/>

        <section class="story__card">
            <div class="story__head">
                <h3 class="story__big display">{{ $phase['lead'] }}</h3>
                <figure class="story__pic story__pic--thumb">
                    <img src="{{ $img($phase['image']) }}" alt="" loading="lazy" decoding="async">
                </figure>
            </div>
            <ol class="story__steps">
                @foreach ($phase['items'] as $n => $item)
                    <li class="reveal" style="transition-delay:{{ $n * 80 }}ms">
                        <span>{{ str_pad($n + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h4>{{ $item['title'] }}</h4>
                        <p>{{ $item['body'] }}</p>
                    </li>
                @endforeach
            </ol>
            @if ($loop->last)
                <a href="{{ route('contact') }}" class="pill group reveal mt-[clamp(1rem,1.8vw,26px)]">
                    Start Your Project <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                </a>
            @endif
        </section>
    @endforeach
</x-story>
