@php($scope = config('site.joinery_page.scope'))

{{--
    What the partnership covers.

    `$services` is passed by PageController@joinery and is the SERVICES PAGE's
    own entries, filtered to the numbers named in the config — not a second
    description of the same work. Edit service 05 or 06 there and both pages
    change together, which is the same rule the project pages follow for their
    facts.
--}}
<section class="bg-mist py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">

        <div class="reveal flex flex-col gap-[clamp(0.75rem,1.16vw,20px)]">
            <p class="text-fluid-label font-medium text-teal">{{ $scope['label'] }}</p>
            <h2 class="display max-w-[18em] text-fluid-h2 leading-[1.3] text-ink">{{ $scope['heading'] }}</h2>
        </div>

        <div class="mt-[clamp(2.5rem,4.63vw,80px)] grid gap-[clamp(2rem,3.7vw,64px)] md:grid-cols-2">
            @foreach ($services as $service)
                <article class="reveal flex flex-col gap-[clamp(1rem,1.62vw,28px)]" style="transition-delay:{{ $loop->index * 120 }}ms">
                    {{-- The picture at the proportions the services page cuts
                         it to, in half the page rather than the full width, so
                         a 1200-wide file is never asked to cover more than it
                         can. --}}
                    <div class="relative aspect-[600/640] w-full overflow-hidden bg-white">
                        <img src="{{ \App\Support\Asset::versioned($service['image']) }}"
                             alt="{{ str_replace(' AND ', ' and ', implode(' ', $service['title'])) }}"
                             loading="lazy" decoding="async"
                             class="absolute inset-0 h-full w-full object-cover">
                    </div>

                    <p class="text-[clamp(1.25rem,1.62vw,28px)] font-medium leading-[1.357] text-teal">{{ $service['number'] }}</p>

                    {{-- "Joinery, Carpentry AND Millwork" is stored with that
                         AND in capitals because the services page sets these
                         titles upper, where it disappears into the line. Here
                         they are title case and it shouts, so it is set down
                         to match the words around it. --}}
                    <h3 class="display text-[clamp(1.5rem,2.2vw,38px)] leading-[1.2] text-ink">
                        {{ str_replace(' AND ', ' and ', implode(' ', $service['title'])) }}
                    </h3>

                    <p class="text-fluid-lead font-medium text-ink">{{ $service['lead'] }}</p>
                    <p class="max-w-[46ch] text-fluid-body font-medium text-ink-muted">{{ $service['body'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
