@php($team = config('site.team_page'))

{{--
    The landing page's pointer to the team: the claim, the headcount large,
    and the six departments as a row of pictures that lead to the Team page.
--}}
<section id="team" class="team-band bg-white py-[clamp(3rem,5vw,80px)]">
    <div class="shell grid gap-[clamp(1.5rem,3vw,48px)] lg:grid-cols-[5fr_7fr] lg:items-end">
        <div>
            <p class="reveal text-fluid-label font-medium text-teal">{{ $team['label'] }}</p>
            <p class="mt-2 leading-none"><span data-count class="team-band__big">{{ $team['figures'][0]['value'] }}</span></p>
            <p class="reveal mt-2 text-fluid-body text-ink-muted">{{ $team['figures'][0]['label'] }}</p>
        </div>
        <div class="flex flex-col gap-4 lg:items-start">
            <p class="reveal display text-fluid-h2 leading-[1.2] text-ink">{{ $team['lead'] }}</p>
            <a href="{{ route('team') }}" class="pill reveal">Meet The Team <x-icon name="arrow-pill"/></a>
        </div>
    </div>

    <ul class="team-band__row shell mt-[clamp(1.75rem,3.5vw,56px)]">
        @foreach ($team['departments'] as $dept)
            <li class="team-band__item">
                <a href="{{ route('team') }}">
                    <img src="{{ \App\Support\Asset::versioned($dept['image']) }}" alt="" loading="lazy" decoding="async">
                    <span>{{ $dept['name'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</section>
