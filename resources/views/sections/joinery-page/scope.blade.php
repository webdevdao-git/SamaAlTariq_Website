@php($scope = config('site.joinery_page.scope'))

{{--
    The ruled heading and the row of three upright pictures, as the reference
    sets that band: heading centred, a hairline under it, centred copy, then
    three equal columns of portrait photographs.

    `$services` is passed by PageController@joinery and is the SERVICES PAGE's
    own entries, filtered to the numbers named in the config — the two joinery
    services and their pictures, not a second description of them. The third
    picture is named in the config, because the row wants three and the site
    has two joinery services.

    Their row runs captionless. Ours names the two services under their
    pictures: the whole point of the band is which parts of the site's work
    this is, and an unlabelled photograph does not answer that.
--}}
<section class="bg-white py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">

        <div class="reveal flex flex-col items-center gap-[clamp(1rem,1.85vw,32px)] text-center">
            <h2 class="display max-w-[16em] text-fluid-h2 uppercase leading-[1.2] text-ink">{{ $scope['heading'] }}</h2>

            {{-- The rule the reference draws under its centred headings. Its
                 width is the copy's, not the page's — full-bleed it would read
                 as a section divider rather than as part of the heading. --}}
            <span aria-hidden="true" class="block h-px w-full max-w-[46em] bg-black/15"></span>

            <p class="max-w-[46em] text-fluid-body font-medium text-ink-muted">{{ $scope['body'] }}</p>
        </div>

        <div class="mt-[clamp(2rem,3.7vw,64px)] grid gap-[clamp(1rem,1.85vw,32px)] md:grid-cols-3">
            @foreach ($services as $service)
                <div class="reveal flex flex-col gap-[clamp(0.75rem,1.16vw,20px)]" style="transition-delay:{{ $loop->index * 110 }}ms">
                    <div class="relative aspect-[4/5] w-full overflow-hidden bg-mist">
                        <img src="{{ \App\Support\Asset::versioned($service['image']) }}"
                             alt="{{ str_replace(' AND ', ' and ', implode(' ', $service['title'])) }}"
                             loading="lazy" decoding="async"
                             class="absolute inset-0 h-full w-full object-cover">
                    </div>

                    <div class="flex flex-col gap-1 text-center">
                        {{-- "Joinery, Carpentry AND Millwork" is stored with
                             that AND in capitals because the services page
                             sets these titles upper, where it disappears into
                             the line. Here they are title case and it shouts,
                             so it is set down to match the words around it. --}}
                        <h3 class="display text-[clamp(1.125rem,1.39vw,24px)] leading-[1.25] text-ink">
                            {{ str_replace(' AND ', ' and ', implode(' ', $service['title'])) }}
                        </h3>
                        <p class="text-fluid-sm font-medium text-ink-muted">{{ $service['lead'] }}</p>
                    </div>
                </div>
            @endforeach

            {{-- The third picture carries no service under it, so the column
                 keeps the same top edge as the two beside it and simply ends
                 where the photograph does. --}}
            <div class="reveal" style="transition-delay:220ms">
                <div class="relative aspect-[4/5] w-full overflow-hidden bg-mist">
                    <img src="{{ \App\Support\Asset::versioned($scope['third']['src']) }}" alt="{{ $scope['third']['alt'] }}"
                         loading="lazy" decoding="async"
                         class="absolute inset-0 h-full w-full object-cover">
                </div>
            </div>
        </div>
    </div>
</section>
