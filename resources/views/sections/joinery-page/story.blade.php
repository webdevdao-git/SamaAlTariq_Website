@php
    $page = config('site.joinery_page');
    $img = fn (string $path) => \App\Support\Asset::versioned($path);
    // The workshop and site photographs from the client's own folder.
    $workshop = [
        ['src' => 'images/workers/work-12.webp', 'caption' => 'Sanding in the workshop'],
        ['src' => 'images/workers/work-8.webp', 'caption' => 'A curved panel, formed'],
        ['src' => 'images/workers/work-14.webp', 'caption' => 'Cutting to size'],
        ['src' => 'images/workers/work-16.webp', 'caption' => 'Setting out'],
        ['src' => 'images/workers/work-13.webp', 'caption' => 'Frame assembly'],
        ['src' => 'images/workers/work-10.webp', 'caption' => 'Fitted on site'],
    ];
@endphp

{{--
    The Joinery page in the landing page's held-photograph layout: Alwan
    within Sama, what it makes, the workshop, how a piece is made, the work
    in context, and common questions.
--}}
<x-story :bgs="[
    'alwan' => 'images/projects/emirates-hills-villa/hero-4.webp',
    'workshop' => 'images/projects/jp-villa/hero-4.webp',
    'process' => 'images/projects/dubai-hills-business-park/hero-3.webp',
    'context' => 'images/projects/jp-villa/hero-2.webp',
]">
    <x-story-win key="alwan" :eyebrow="implode(' ', $page['ecosystem']['label'])" :title="['Joinery, Made', 'By Our Own Hands']"/>

    <section class="story__card">
        <div class="story__split">
            <div>
                <img src="{{ asset($page['partner']['logo']) }}" alt="{{ $page['partner']['mark_alt'] }}" loading="lazy" decoding="async" class="story__logo reveal">
                <h3 class="story__heading display reveal">{{ $page['ecosystem']['statement'] }}</h3>
                <p class="story__body reveal">{{ $page['ecosystem']['body'] }}</p>
            </div>
            <figure class="story__pic">
                <img src="{{ $img('images/about-interior.webp') }}" alt="Curved ribbed wall and ceiling made by Alwan Interiors" loading="lazy" decoding="async">
            </figure>
        </div>
        <ol class="story__steps story__steps--3">
            @foreach ($page['capabilities']['items'] as $n => $item)
                <li class="reveal" style="transition-delay:{{ $n * 80 }}ms">
                    <span>{{ $item['number'] }}</span>
                    <h4>{{ $item['title'] }}</h4>
                    <p>{{ $item['body'] }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    <x-story-win key="workshop" eyebrow="Our Workshop" :title="['Made In Our', 'Workshop, Fitted On Site']"/>

    <section class="story__card">
        <div class="story__head">
            <h3 class="story__big display">Every piece is drawn, cut, finished and fitted by one team.</h3>
            <div class="story__head-side">
                <p class="story__body reveal">Wardrobes, kitchens, panelling and curved feature walls come out of our own workshop and are installed by the same people who made them.</p>
            </div>
        </div>
        <ul class="story__crew story__crew--6">
            @foreach ($workshop as $photo)
                <li class="reveal" style="transition-delay:{{ ($loop->index % 6) * 70 }}ms">
                    <img src="{{ $img($photo['src']) }}" alt="Alwan Interiors: {{ strtolower($photo['caption']) }}" loading="lazy" decoding="async">
                    <span>{{ $photo['caption'] }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    <x-story-win key="process" :eyebrow="$page['process']['label']" :title="['Five Steps,', 'Every Piece']"/>

    <section class="story__card">
        <div class="story__head">
            <h3 class="story__big display">{{ $page['process']['heading'] }}</h3>
        </div>
        <ol class="story__steps story__steps--5">
            @foreach ($page['process']['steps'] as $n => $step)
                <li class="reveal" style="transition-delay:{{ $n * 80 }}ms">
                    <span>{{ str_pad($n + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h4>{{ $step['title'] }}</h4>
                    <p>{{ $step['body'] }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    <x-story-win key="context" eyebrow="Selected Work" :title="$page['context']['heading']"/>

    <section class="story__card">
        <div class="story__head">
            <h3 class="story__big display">Joinery in finished projects.</h3>
            <div class="story__head-side">
                <p class="story__body reveal">{{ $page['context']['body'] }}</p>
                <a href="{{ url($page['context']['cta']['href']) }}" class="pill group reveal self-start">
                    {{ $page['context']['cta']['label'] }} <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                </a>
            </div>
        </div>
        <div class="story__pair">
            @foreach ($page['context']['tiles'] as $tile)
                <a href="{{ route('projects.show', $tile['project']) }}" class="story__pair-item group reveal">
                    <img src="{{ $img($tile['image']) }}" alt="{{ $tile['title'] }}" loading="lazy" decoding="async">
                    <span><b>{{ $tile['title'] }}</b>{{ $tile['category'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    @include('sections.story-faq', ['items' => $page['faqs']['items'], 'heading' => ['Questions', 'About Alwan']])
</x-story>
