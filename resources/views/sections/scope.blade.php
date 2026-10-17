@php
    $scope = config('site.scope');
    $items = $scope['items'];
    $total = str_pad(count($items), 2, '0', STR_PAD_LEFT);
@endphp

{{--
    We Do Everything Your Project Needs, from the 2026 business profile.

    One held screen (motion/scope-stage.js pins it). The first photograph
    opens out of a small frame under the heading to fill the screen; after
    that, the next service waits as a small preview in the corner, and
    scrolling grows that preview into the new background while the title,
    card and progress change over to it. The photograph is always the work
    the card describes, and the card names the project it is from.

    Without the script it rests on the first service, fully drawn.
--}}
<section id="scope" data-sx class="sx">
    <div class="sx__stage">
        <div class="sx__bgs">
            @foreach ($items as $i => $item)
                <div data-sx-bg class="sx__bg" style="z-index:{{ $i + 1 }}">
                    <img src="{{ \App\Support\Asset::versioned($item['image']) }}" alt="{{ $item['title'] }}, {{ $item['credit'] }}"
                         loading="{{ $i === 0 ? 'eager' : 'lazy' }}" decoding="async">
                </div>
            @endforeach
            <div data-sx-shade aria-hidden="true" class="sx__shade"></div>
        </div>

        <div data-sx-intro class="sx__intro shell">
            <h2 class="editorial-heading text-fluid-h2 uppercase text-ink">
                <span class="block">{{ $scope['heading'][0] }}</span>
                <span class="block text-teal">{{ $scope['heading'][1] }}</span>
            </h2>
            <p class="max-w-[30ch] text-fluid-lead font-medium text-ink-muted">{{ $scope['body'] }}</p>
        </div>

        <div data-sx-ui class="sx__ui">
            <div class="sx__top shell">
                <p class="sx__label">{{ $scope['heading'][0] }}</p>
                <div class="sx__bars" aria-hidden="true">
                    @foreach ($items as $item)
                        <span><i data-sx-bar></i></span>
                    @endforeach
                </div>
            </div>

            {{-- The preview of what comes next. Its picture box is where the
                 next background grows from. --}}
            <div class="sx__next" aria-hidden="true">
                <div data-sx-next-box class="sx__next-box">
                    @foreach ($items as $i => $item)
                        @continue($i === 0)
                        <div data-sx-next="{{ $i }}" class="sx__next-item">
                            <img src="{{ \App\Support\Asset::versioned($item['image']) }}" alt="" loading="lazy" decoding="async">
                        </div>
                    @endforeach
                </div>
                <div class="sx__next-names">
                    @foreach ($items as $i => $item)
                        @continue($i === 0)
                        <p data-sx-next-name="{{ $i }}"><span>Next</span>{{ $item['title'] }}</p>
                    @endforeach
                </div>
            </div>

            <div class="sx__foot shell">
                <div class="sx__heads">
                    <p class="sx__count"><span data-sx-count>01</span> / {{ $total }}</p>
                    <div class="sx__titles">
                        @foreach ($items as $i => $item)
                            <h3 data-sx-title class="sx__title display"><span>{{ $item['title'] }}</span></h3>
                        @endforeach
                    </div>
                </div>

                <div class="sx__cards">
                    @foreach ($items as $i => $item)
                        <div data-sx-card class="sx__card">
                            <p class="sx__lead">{{ $item['lead'] }}</p>
                            <p class="sx__body">{{ $item['body'] }}</p>
                            <ul class="sx__points">
                                @foreach ($item['points'] as $point)
                                    <li>{{ $point }}</li>
                                @endforeach
                            </ul>
                            <div class="sx__credit">
                                <span>Shown: {{ $item['credit'] }}</span>
                                @if ($loop->last)
                                    <a href="{{ route('services') }}" class="pill">All Services <x-icon name="arrow-pill"/></a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
