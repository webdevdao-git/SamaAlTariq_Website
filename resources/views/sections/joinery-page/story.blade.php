@php
    $page = config('site.joinery_page');
    $img = fn (string $path) => \App\Support\Asset::versioned($path);
    // The client's own workshop photographs, two rows of six columns: each
    // row has one landscape frame spanning two, so nothing wide is cropped
    // to a portrait.
    $workshop = [
        ['src' => 'images/workshop/edging.webp', 'caption' => 'Edging and assembly', 'wide' => true],
        ['src' => 'images/workshop/cabinetry.webp', 'caption' => 'Cabinetry in production'],
        ['src' => 'images/workshop/oak-stool.webp', 'caption' => 'A stool in solid oak'],
        ['src' => 'images/workshop/relief-panel.webp', 'caption' => 'A relief panel, formed'],
        ['src' => 'images/workshop/bed-frame.webp', 'caption' => 'A bed frame, assembled'],
        ['src' => 'images/workshop/sanding.webp', 'caption' => 'Sanding a curved form'],
        ['src' => 'images/workshop/table-saw.webp', 'caption' => 'Cutting to size'],
        ['src' => 'images/workshop/frames.webp', 'caption' => 'Frames on the bench', 'wide' => true],
        ['src' => 'images/workshop/setting-out.webp', 'caption' => 'Setting out'],
        ['src' => 'images/workshop/slatted-panel.webp', 'caption' => 'A slatted panel, fixed'],
    ];
@endphp

{{--
    The Joinery page in the landing page's held-photograph layout: Alwan
    within Sama, what it makes, the workshop, how a piece is made, the work
    in context, and common questions.
--}}
<x-story :bgs="[
    'alwan' => 'images/projects/jp-villa/hero-2.webp',
    'workshop' => 'images/projects/emirates-hills-villa/hero-4.webp',
    'process' => 'images/projects/wasl-properties-hq/hero-4.webp',
    'context' => 'images/projects/jumeirah-island-villa/hero-3.webp',
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
                <img src="{{ $img('images/workshop/oak-wall.webp') }}" alt="An oak-veneered wall with built-in shelving and bench, made by Alwan Interiors" loading="lazy" decoding="async">
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
                <li class="reveal {{ ($photo['wide'] ?? false) ? 'is-wide' : '' }}" style="transition-delay:{{ ($loop->index % 5) * 70 }}ms">
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
