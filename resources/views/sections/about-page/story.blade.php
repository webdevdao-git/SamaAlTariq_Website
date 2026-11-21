@php
    $page = config('site.about_page');
    $numbers = config('site.numbers');
    $img = fn (string $path) => \App\Support\Asset::versioned($path);
@endphp

{{--
    The About page in the landing page's held-photograph layout: who we are
    and the figures, the vision, the approach, and the closing invitation,
    each a card over its own photograph.
--}}
<x-story :bgs="[
    'intro' => 'images/projects/jumeirah-golf-estate-villas/hero-2.webp',
    'vision' => 'images/projects/jumeirah-golf-estate-villas/l9.webp',
    'group' => 'images/projects/dubai-hills-business-park/hero-4.webp',
    'approach' => 'images/projects/boulevard-plaza-office/hero.webp',
]">
    <x-story-win key="intro" eyebrow="Who We Are" :title="['One Contractor,', 'Every Interior Trade']"/>

    <section class="story__card">
        <div class="story__split">
            <div>
                <p class="story__label reveal">{{ $page['intro']['lead'] }}</p>
                <h3 class="story__heading display reveal mt-2">An interior fit-out company, based in Dubai.</h3>
                <p class="story__body reveal">{{ $page['intro']['body'] }}</p>
                <p class="story__body reveal">{{ $page['intro']['result'] }}</p>
            </div>
            <figure class="story__pic">
                <img src="{{ $img($page['intro']['image']) }}" alt="{{ $page['intro']['alt'] }}" loading="lazy" decoding="async">
            </figure>
        </div>
        <dl class="story__stats story__stats--6">
            @foreach ($numbers['items'] as $item)
                <div class="reveal">
                    <dd>{{ $item['value'] }}</dd>
                    <dt>{{ $item['label'] }}</dt>
                </div>
            @endforeach
        </dl>
    </section>

    <x-story-win key="vision" eyebrow="Our Vision" :title="$page['vision']['heading']"/>

    <section class="story__card">
        <div class="story__split">
            <figure class="story__pic">
                <img src="{{ $img($page['vision']['image']) }}" alt="{{ $page['vision']['alt'] }}" loading="lazy" decoding="async">
            </figure>
            <div>
                <h3 class="story__heading display reveal">One programme, one project lead, one contract.</h3>
                <p class="story__body reveal">{{ $page['vision']['body'] }}</p>
                <a href="{{ url($page['vision']['cta']['href']) }}" class="pill group reveal mt-[clamp(1rem,1.8vw,26px)] self-start">
                    Our Process <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                </a>
            </div>
        </div>
        <ol class="story__steps story__steps--3">
            @foreach ($page['vision']['principles'] as $n => $item)
                <li class="reveal" style="transition-delay:{{ ($n % 3) * 80 }}ms">
                    <span>{{ str_pad($n + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <h4>{{ $item['title'] }}</h4>
                    <p>{{ $item['body'] }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    <x-story-win key="group" :eyebrow="$page['group']['label']" :title="$page['group']['heading']"/>

    <section class="story__card">
        <div class="story__head">
            <h3 class="story__big display">One group, so the fit-out, the joinery and the MEP answer to one programme.</h3>
        </div>
        <ol class="story__steps story__steps--3">
            @foreach ($page['group']['companies'] as $n => $company)
                <li class="reveal" style="transition-delay:{{ $n * 80 }}ms">
                    <span>{{ $company['sub'] }}</span>
                    <h4>{{ $company['name'] }}</h4>
                    <p>{{ $company['body'] }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    <x-story-win key="approach" :eyebrow="$page['approach']['label']" :title="['Planned Before', 'The First Ceiling']"/>

    <section class="story__card">
        <div class="story__head">
            <h3 class="story__big display">{{ $page['approach']['heading'] }}</h3>
            <div class="story__head-side">
                <p class="story__body reveal">{{ $page['approach']['body'] }}</p>
                <a href="{{ url($page['approach']['cta']['href']) }}" class="pill group reveal self-start">
                    {{ $page['approach']['cta']['label'] }} <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                </a>
            </div>
        </div>
        <ul class="story__trio">
            @foreach (['main', 'left', 'right'] as $k)
                <li class="reveal" style="transition-delay:{{ $loop->index * 90 }}ms">
                    <img src="{{ $img($page['approach']['images'][$k]['src']) }}" alt="{{ $page['approach']['images'][$k]['alt'] }}" loading="lazy" decoding="async">
                </li>
            @endforeach
        </ul>
    </section>

    <section class="story__card story__card--cta">
        <h3 class="story__big display">
            @foreach ($page['purpose']['words'] as $word)
                <span class="{{ $loop->last ? 'text-teal' : '' }}">{{ $word }}</span>
            @endforeach
        </h3>
        <div class="story__head-side">
            <p class="story__body reveal">{{ $page['purpose']['body'] }}</p>
            <a href="{{ route('contact') }}" class="pill group reveal self-start">
                {{ $page['purpose']['cta']['label'] }} <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
            </a>
        </div>
    </section>
</x-story>
