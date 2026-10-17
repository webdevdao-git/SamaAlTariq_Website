@php($services = config('site.services_page.services'))

{{--
    The services as an index: the list on the left, and on the right one
    photograph held in place while the list scrolls past it. Whichever row is
    across the middle of the screen is the active one, and its picture wipes
    up over the last (motion/services-index.js). Hovering a row does the same.

    Below lg there is no held picture: each row carries its own above it.
--}}
<section data-svc-index class="svc-index bg-mist py-[clamp(3rem,6vw,104px)]">
    <div class="shell grid gap-[clamp(2rem,4vw,72px)] lg:grid-cols-[1.05fr_0.95fr]">
        <ol class="svc-list">
            @foreach ($services as $i => $service)
                <li data-svc-row="{{ $i }}" class="svc-row {{ $i === 0 ? 'is-active' : '' }}">
                    <img src="{{ \App\Support\Asset::versioned($service['image']) }}" alt="" loading="lazy" decoding="async"
                         class="svc-row__pic lg:hidden">
                    <span class="svc-row__num">{{ $service['number'] }}</span>
                    <div class="min-w-0">
                        <h2 class="svc-row__title display">{{ implode(' ', $service['title']) }}</h2>
                        <p class="svc-row__lead">{{ $service['lead'] }}</p>
                        <ul class="svc-row__points">
                            @foreach ($service['points'] as $point)
                                <li>{{ $point }}</li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            @endforeach
        </ol>

        <div aria-hidden="true" class="svc-media hidden lg:block">
            <div class="svc-media__frame">
                @foreach ($services as $i => $service)
                    <img data-svc-img="{{ $i }}" src="{{ \App\Support\Asset::versioned($service['image']) }}" alt=""
                         loading="{{ $i === 0 ? 'eager' : 'lazy' }}" decoding="async"
                         class="svc-media__img {{ $i === 0 ? 'is-active' : '' }}">
                @endforeach
                <span data-svc-count class="svc-media__count">{{ $services[0]['number'] }} / {{ str_pad(count($services), 2, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </div>
</section>
