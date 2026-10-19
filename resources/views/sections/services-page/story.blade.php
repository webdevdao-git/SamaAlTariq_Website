@php
    $page = config('site.services_page');
    $img = fn (string $path) => \App\Support\Asset::versioned($path);
    // Gypsum first: it is the company's core trade.
    $order = [4, 0, 1, 2, 3, 5, 6, 7];
@endphp

{{--
    The Services page in the landing page's held-photograph layout: the eight
    services, the two ways to engage, works in occupied spaces, and common
    questions, each a card over its own photograph.
--}}
<x-story :bgs="[
    'services' => 'images/projects/dubai-hills-business-park/hero-2.webp',
    'models' => 'images/projects/villa-b200-tilal-al-ghaf/hero-2.webp',
    'live' => 'images/projects/pullman/hero-4.webp',
]">
    <x-story-win key="services" :eyebrow="$page['intro']['label']" :title="['Every Interior Trade,', 'In House']"/>

    <section class="story__card">
        <div class="story__head">
            <h3 class="story__big display">{{ $page['intro']['statement'] }}</h3>
            <div class="story__head-side">
                <p class="story__body reveal">{{ $page['intro']['body'] }}</p>
            </div>
        </div>
        <ol class="story__svc story__svc--full">
            @foreach ($order as $n => $index)
                @php($service = $page['services'][$index])
                <li class="reveal">
                    <span class="story__svc-num">{{ str_pad($n + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <div class="story__svc-name">
                        <h4 class="display">{{ implode(' ', $service['title']) }}</h4>
                        <p>{{ implode(' · ', $service['points']) }}</p>
                    </div>
                    <img src="{{ $img($service['image']) }}" alt="" loading="lazy" decoding="async" class="story__svc-pic">
                </li>
            @endforeach
        </ol>
    </section>

    <x-story-win key="models" :eyebrow="$page['delivery']['label']" :title="['Design & Build,', 'Or Build Only']"/>

    <section class="story__card">
        <div class="story__models !mt-0">
            @foreach ($page['delivery']['models'] as $i => $model)
                <article class="story__model reveal {{ $i === 0 ? 'is-teal' : '' }}">
                    <p class="story__model-tag">{{ $model['for'] }}</p>
                    <h4 class="display">{{ $model['title'] }}</h4>
                    <p class="story__model-body">{{ $model['body'] }}</p>
                    <ul>
                        @foreach ($model['points'] as $point)
                            <li>{{ $point }}</li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </section>

    <x-story-win key="live" :eyebrow="$page['live']['label']" :title="$page['live']['heading']"/>

    <section class="story__card">
        <div class="story__head">
            <h3 class="story__big display">Guests, staff and families carry on around us.</h3>
            <div class="story__head-side">
                <p class="story__body reveal">{{ $page['live']['body'] }}</p>
            </div>
        </div>
        <ol class="story__steps">
            @foreach ($page['live']['steps'] as $i => $step)
                <li class="reveal">
                    <span>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h4>{{ $step['title'] }}</h4>
                    <p>{{ $step['body'] }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    @include('sections.story-faq', ['items' => $page['faq']['items'], 'heading' => ['Questions', 'We Get Asked']])
</x-story>
