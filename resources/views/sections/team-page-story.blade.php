@php
    $team = config('site.team_page');
    $img = fn (string $path) => \App\Support\Asset::versioned($path);
@endphp

{{--
    The Team page below its heading, in the landing page's held-photograph
    layout: the figures and the six departments, how a project is staffed,
    and — once supplied — the people who lead it.
--}}
<x-story :bgs="[
    'depts' => 'images/projects/dubai-hills-business-park/hero.webp',
    'staffing' => 'images/projects/wasl-properties-hq/hero-3.webp',
]">
    <x-story-win key="depts" eyebrow="Our Departments" :title="['Six Departments,', 'One Team']"/>

    <section class="story__card">
        <dl class="story__stats story__stats--3 !mt-0">
            @foreach ($team['figures'] as $figure)
                <div class="reveal">
                    <dd>{{ $figure['value'] }}</dd>
                    <dt>{{ $figure['label'] }}</dt>
                </div>
            @endforeach
        </dl>
        <ul class="story__depts">
            @foreach ($team['departments'] as $i => $dept)
                <li class="reveal" style="transition-delay:{{ ($i % 3) * 80 }}ms">
                    <img src="{{ $img($dept['image']) }}" alt="{{ $dept['name'] }}" loading="lazy" decoding="async">
                    <div>
                        <span class="story__svc-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h4 class="display">{{ $dept['name'] }}</h4>
                        <p>{{ implode(' · ', $dept['roles']) }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    </section>

    <x-story-win key="staffing" eyebrow="How A Project Is Staffed" :title="['One Project Lead,', 'Every Trade Under Them']"/>

    <section class="story__card">
        <div class="team-tree" data-team-tree>
            <ol class="team-tree__row is-top">
                @foreach ($team['structure']['top'] as $role)
                    <li class="team-node {{ $loop->index === 1 ? 'is-key' : '' }}">{{ $role }}</li>
                @endforeach
            </ol>
            <span class="team-tree__line" aria-hidden="true"></span>
            <ol class="team-tree__row">
                @foreach ($team['structure']['leads'] as $role)
                    <li class="team-node">{{ $role }}</li>
                @endforeach
            </ol>
            <span class="team-tree__line" aria-hidden="true"></span>
            <ol class="team-tree__row is-heads">
                @foreach ($team['structure']['heads'] as $role)
                    <li class="team-node is-head"><span>Head of {{ $role }}</span><small>{{ $role }} Team</small></li>
                @endforeach
            </ol>
        </div>
    </section>

    @if (! empty($team['leadership']))
        <section class="story__card">
            <h3 class="story__big display">Leadership</h3>
            <div class="mt-[clamp(1rem,2vw,28px)] grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($team['leadership'] as $person)
                    <figure class="team-person">
                        @if (! empty($person['photo']))
                            <img src="{{ $img($person['photo']) }}" alt="{{ $person['name'] }}" loading="lazy" decoding="async">
                        @endif
                        <figcaption>
                            <span class="display text-fluid-h2 text-ink">{{ $person['name'] }}</span>
                            <span class="text-fluid-sm text-teal">{{ $person['title'] }}</span>
                            @if (! empty($person['bio'])) <span class="text-fluid-body text-ink-muted">{{ $person['bio'] }}</span> @endif
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </section>
    @endif
</x-story>
