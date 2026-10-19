@php
    $story = config('site.story');
    $services = config('site.services_page.services');
    $models = config('site.services_page.delivery.models');
    $faq = config('site.services_page.faq.items');
    $logos = config('site.clients.logos');
    $sectors = config('site.scope.sectors');
    $img = fn (string $path) => \App\Support\Asset::versioned($path);

    // Every chapter that has a photograph, in page order: the backdrop holds
    // one layer for each.
    $chapters = [
        'about' => $story['about']['bg'],
        'gypsum' => $story['gypsum']['bg'],
        'services' => $story['services']['bg'],
    ];
    // The second half, after the featured projects' stack.
    $later = [
        'work' => $story['work']['bg'],
        'crew' => $story['crew']['bg'],
        'alwan' => $story['alwan']['bg'],
    ];
@endphp

{{--
    The landing page after the hero, after kojinteriors.com's model.

    One photograph is held full-screen behind everything (.story__bd, sticky
    for the length of the story). The content is on white cards that scroll
    up over it; between the cards the photograph shows through, with the
    chapter's title across it. As each chapter's window arrives the backdrop
    changes to its photograph, which settles from a slight zoom while the
    chapter is on screen (motion/story-backdrop.js).

    Without the script the backdrop stays on the first photograph and the
    page reads exactly the same.
--}}
<x-story :bgs="$chapters">

        {{-- 1 · Who we are --}}
        @php($about = $story['about'])
        <section class="story__win" data-story-win="about">
            <div class="shell">
                <p class="story__eyebrow reveal">{{ $about['eyebrow'] }}</p>
                <h2 class="story__title display">
                    @foreach ($about['title'] as $i => $line)
                        <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
                    @endforeach
                </h2>
            </div>
        </section>

        <section class="story__card">
            <div class="story__split">
                <div>
                    <h3 class="story__heading display reveal">{{ $about['heading'] }}</h3>
                    <p class="story__body reveal">{{ $about['body'] }}</p>
                    <ul class="story__chips reveal">
                        @foreach ($about['trades'] as $trade)
                            <li>{{ $trade }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ url($about['cta']['href']) }}" class="pill group reveal mt-[clamp(1.25rem,2vw,32px)] self-start">
                        {{ $about['cta']['label'] }} <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                    </a>
                </div>
                <figure class="story__pic">
                    <img src="{{ $img($about['image']) }}" alt="{{ $about['image_alt'] }}" loading="lazy" decoding="async">
                </figure>
            </div>


            <div class="story__row">
                <p class="story__label">We work for</p>
                <p class="story__sectors">
                    @foreach ($sectors as $sector)
                        <span>{{ $sector }}</span>
                    @endforeach
                </p>
            </div>

        </section>

        {{-- 2 · The core trade: gypsum and ceilings --}}
        @php($gyp = $story['gypsum'])
        <section class="story__win" data-story-win="gypsum">
            <div class="shell">
                <p class="story__eyebrow reveal">{{ $gyp['eyebrow'] }}</p>
                <h2 class="story__title display">
                    @foreach ($gyp['title'] as $i => $line)
                        <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
                    @endforeach
                </h2>
            </div>
        </section>

        <section class="story__card gyp" data-gyp>
            <div class="gyp__grid">
                <div class="gyp__copy">
                    <h3 class="story__heading display reveal">{{ $gyp['heading'] }}</h3>
                    <p class="story__body reveal">{{ $gyp['body'] }}</p>
                    <ul class="gyp__list">
                        @foreach ($gyp['list'] as $item)
                            <li class="reveal" style="transition-delay:{{ $loop->index * 60 }}ms">{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                {{-- The same kind of ceiling, framed and then finished: the
                     finished photograph wipes across the framed one. --}}
                <figure class="gyp__wipe">
                    <img src="{{ $img($gyp['framed']['src']) }}" alt="Gypsum ceiling being framed by the Sama Al Tariq team" loading="lazy" decoding="async">
                    <div data-gyp-top class="gyp__top">
                        <img src="{{ $img($gyp['finished']['src']) }}" alt="A finished gypsum cove ceiling by Sama Al Tariq" loading="lazy" decoding="async">
                    </div>
                    <span data-gyp-edge class="gyp__edge" aria-hidden="true"></span>
                    <span class="gyp__tag gyp__tag--a">{{ $gyp['framed']['label'] }}</span>
                    <span class="gyp__tag gyp__tag--b">{{ $gyp['finished']['label'] }}</span>
                </figure>
            </div>
        </section>

        {{-- 3 · What we do --}}
        @php($svc = $story['services'])
        <section class="story__win" data-story-win="services">
            <div class="shell">
                <p class="story__eyebrow reveal">{{ $svc['eyebrow'] }}</p>
                <h2 class="story__title display">
                    @foreach ($svc['title'] as $i => $line)
                        <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
                    @endforeach
                </h2>
            </div>
        </section>

        <section class="story__card">
            <div class="story__head">
                <h3 class="story__big display">
                    @foreach ($svc['heading'] as $line)
                        <span class="block">{{ $line }}</span>
                    @endforeach
                </h3>
                <div class="story__head-side">
                    <p class="story__body reveal">{{ $svc['body'] }}</p>
                    <a href="{{ url($svc['cta']['href']) }}" class="pill group reveal self-start">
                        {{ $svc['cta']['label'] }} <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                    </a>
                </div>
            </div>

            <ol class="story__svc">
                @foreach ($svc['pick'] as $n => $index)
                    @php($service = $services[$index])
                    <li class="reveal">
                        <span class="story__svc-num">{{ str_pad($n + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <ul class="story__svc-points">
                            @foreach ($service['points'] as $point)
                                <li>{{ $point }}</li>
                            @endforeach
                        </ul>
                        <div class="story__svc-name">
                            <h4 class="display">{{ implode(' ', $service['title']) }}</h4>
                            <p>{{ $service['lead'] }}</p>
                        </div>
                        <img src="{{ $img($service['image']) }}" alt="" loading="lazy" decoding="async" class="story__svc-pic">
                    </li>
                @endforeach
            </ol>
        </section>

</x-story>

{{-- 4 · Featured projects: the pinned pyramid stack, which needs to sit
     outside the held photograph to pin. --}}
@include('sections.projects')

<x-story :bgs="$later">
        {{-- 5 · How we work --}}
        @php($work = $story['work'])
        <section class="story__win" data-story-win="work">
            <div class="shell">
                <p class="story__eyebrow reveal">{{ $work['eyebrow'] }}</p>
                <h2 class="story__title display">
                    @foreach ($work['title'] as $i => $line)
                        <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
                    @endforeach
                </h2>
            </div>
        </section>

        <section class="story__card">
            <div class="story__head">
                <h3 class="story__big display">
                    @foreach ($work['heading'] as $line)
                        <span class="block">{{ $line }}</span>
                    @endforeach
                </h3>
                <div class="story__head-side">
                    <p class="story__body reveal">{{ $work['body'] }}</p>
                </div>
            </div>

            <div class="story__models">
                @foreach ($models as $i => $model)
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

            <ol class="story__steps">
                @foreach ($work['steps'] as $i => $step)
                    <li class="reveal">
                        <span>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h4>{{ $step['title'] }}</h4>
                        <p>{{ $step['body'] }}</p>
                    </li>
                @endforeach
            </ol>

            <a href="{{ url($work['cta']['href']) }}" class="pill group reveal mt-[clamp(1.5rem,2.5vw,40px)]">
                {{ $work['cta']['label'] }} <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
            </a>
        </section>

        {{-- 6 · Our people on site --}}
        @php($crew = $story['crew'])
        <section class="story__win" data-story-win="crew">
            <div class="shell">
                <p class="story__eyebrow reveal">{{ $crew['eyebrow'] }}</p>
                <h2 class="story__title display">
                    @foreach ($crew['title'] as $i => $line)
                        <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
                    @endforeach
                </h2>
            </div>
        </section>

        <section class="story__card">
            <div class="story__head">
                <h3 class="story__big display">
                    @foreach ($crew['heading'] as $i => $line)
                        <span class="block {{ $i === 1 ? 'text-teal' : '' }}">{{ $line }}</span>
                    @endforeach
                </h3>
                <div class="story__head-side">
                    <p class="story__body reveal">{{ $crew['body'] }}</p>
                    <a href="{{ url($crew['cta']['href']) }}" class="pill group reveal self-start">
                        {{ $crew['cta']['label'] }} <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                    </a>
                </div>
            </div>

            <ul class="story__crew">
                @foreach ($crew['photos'] as $photo)
                    <li class="reveal {{ ! empty($photo['wide']) ? 'is-wide' : '' }}" style="transition-delay:{{ ($loop->index % 4) * 80 }}ms">
                        <img src="{{ $img($photo['src']) }}" alt="Sama Al Tariq team: {{ strtolower($photo['caption']) }}" loading="lazy" decoding="async">
                        <span>{{ $photo['caption'] }}</span>
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- 7 · Alwan --}}
        @php($alwan = $story['alwan'])
        <section class="story__win" data-story-win="alwan">
            <div class="shell">
                <p class="story__eyebrow reveal">{{ $alwan['eyebrow'] }}</p>
                <h2 class="story__title display">
                    @foreach ($alwan['title'] as $i => $line)
                        <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
                    @endforeach
                </h2>
            </div>
        </section>

        <section class="story__card">
            <div class="story__split">
                <figure class="story__pic story__pic--tall">
                    <img src="{{ $img($alwan['image']) }}" alt="A curved panel being finished in the Alwan Interiors workshop" loading="lazy" decoding="async">
                </figure>
                <div>
                    <img src="{{ asset(config('site.joinery_page.partner.logo')) }}" alt="{{ config('site.joinery_page.partner.mark_alt') }}" loading="lazy" decoding="async" class="story__logo reveal">
                    <h3 class="story__heading display reveal">{{ $alwan['heading'] }}</h3>
                    <p class="story__body reveal">{{ $alwan['body'] }}</p>
                    <a href="{{ url($alwan['cta']['href']) }}" class="pill group reveal mt-[clamp(1.25rem,2vw,32px)] self-start">
                        {{ $alwan['cta']['label'] }} <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                    </a>
                </div>
            </div>
        </section>

        {{-- 8 · Questions --}}
        <section class="story__card story__card--last">
            <div class="grid gap-[clamp(1.5rem,4vw,72px)] lg:grid-cols-[0.6fr_1.4fr]">
                <h3 class="story__big display">
                    @foreach ($story['faq_heading'] as $line)
                        <span class="block">{{ $line }}</span>
                    @endforeach
                </h3>
                <div class="faq">
                    @foreach ($faq as $item)
                        <details class="faq__item" @if ($loop->first) open @endif>
                            <summary class="faq__q">
                                <span>{{ $item['q'] }}</span>
                                <span aria-hidden="true" class="faq__sign"></span>
                            </summary>
                            <p class="faq__a">{{ $item['a'] }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>
</x-story>
