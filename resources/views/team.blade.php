@extends('layouts.app')

@php($team = config('site.team_page'))

@section('title', 'Our Team — ' . config('site.legal_name'))
@section('description', $team['lead'])
@section('image', $team['image'])

{{--
    The in-house team: who does the work, department by department, how a
    project is staffed, and — once supplied — the people who lead it.
--}}
@section('content')
    <x-site-header tone="dark"/>

    <main data-page="team">
        {{-- Heading, the one-line claim, and the team at work. --}}
        <section class="team-hero bg-white pt-[clamp(7rem,13vw,220px)]">
            <div class="shell grid gap-[clamp(1.5rem,3vw,52px)] lg:grid-cols-[7fr_5fr] lg:items-end">
                <div>
                    <p class="reveal text-fluid-label font-medium text-teal">{{ $team['label'] }}</p>
                    <h1 class="editorial-heading mt-3 text-[clamp(2.75rem,5.25vw,90px)] uppercase text-ink">
                        @foreach ($team['heading'] as $i => $line)
                            <span data-split data-split-delay="{{ $i * 120 }}" class="block">{{ $line }}</span>
                        @endforeach
                    </h1>
                </div>
                <div class="flex flex-col gap-4">
                    <p class="reveal display text-fluid-h2 leading-[1.2] text-ink">{{ $team['lead'] }}</p>
                    <p class="reveal text-fluid-body text-ink-muted">{{ $team['body'] }}</p>
                </div>
            </div>

            <div class="team-hero__pic shell mt-[clamp(2rem,4vw,64px)]">
                <div class="team-hero__frame" data-team-reveal>
                    <img src="{{ \App\Support\Asset::versioned($team['image']) }}" alt="Sama Al Tariq team fitting a joinery wall on site" decoding="async">
                </div>
            </div>
        </section>

        {{-- The figures, large. --}}
        <section class="bg-white py-[clamp(2.5rem,5vw,80px)]">
            <dl class="shell team-figures">
                @foreach ($team['figures'] as $figure)
                    <div class="team-figure">
                        <dt class="sr-only">{{ $figure['label'] }}</dt>
                        <dd>
                            <span data-count class="team-figure__value">{{ $figure['value'] }}</span>
                            <span class="team-figure__label">{{ $figure['label'] }}</span>
                        </dd>
                    </div>
                @endforeach
            </dl>
        </section>

        {{-- Departments: a large picture each, the roles beside it. --}}
        <section class="team-depts bg-mist py-[clamp(3rem,5vw,88px)]">
            <div class="shell">
                <h2 class="editorial-heading text-fluid-section uppercase text-ink">
                    <span data-split class="block">Departments</span>
                </h2>
                <div class="mt-[clamp(1.75rem,3.5vw,56px)] flex flex-col gap-[clamp(1rem,2vw,32px)]">
                    @foreach ($team['departments'] as $i => $dept)
                        <article class="team-dept {{ $i % 2 ? 'is-flip' : '' }}">
                            <div class="team-dept__pic">
                                <img src="{{ \App\Support\Asset::versioned($dept['image']) }}" alt="{{ $dept['name'] }}" loading="lazy" decoding="async">
                            </div>
                            <div class="team-dept__text">
                                <span class="team-dept__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <h3 class="display team-dept__name">{{ $dept['name'] }}</h3>
                                <ul class="team-dept__roles">
                                    @foreach ($dept['roles'] as $role)
                                        <li>{{ $role }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- How a project is staffed, drawn as a tree. --}}
        <section class="bg-white py-[clamp(3rem,5vw,88px)]">
            <div class="shell">
                <h2 class="editorial-heading text-fluid-section uppercase text-ink">
                    <span data-split class="block">How A Project</span>
                    <span data-split data-split-delay="110" class="block text-teal">Is Staffed</span>
                </h2>
                <div class="team-tree mt-[clamp(2rem,4vw,64px)]" data-team-tree>
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
            </div>
        </section>

        @if (! empty($team['leadership']))
            <section class="bg-mist py-[clamp(3rem,5vw,88px)]">
                <div class="shell">
                    <h2 class="editorial-heading text-fluid-section uppercase text-ink"><span data-split class="block">Leadership</span></h2>
                    <div class="mt-[clamp(1.75rem,3.5vw,56px)] grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($team['leadership'] as $person)
                            <figure class="team-person">
                                @if (! empty($person['photo']))
                                    <img src="{{ \App\Support\Asset::versioned($person['photo']) }}" alt="{{ $person['name'] }}" loading="lazy" decoding="async">
                                @endif
                                <figcaption>
                                    <span class="display text-fluid-h2 text-ink">{{ $person['name'] }}</span>
                                    <span class="text-fluid-sm text-teal">{{ $person['title'] }}</span>
                                    @if (! empty($person['bio'])) <span class="text-fluid-body text-ink-muted">{{ $person['bio'] }}</span> @endif
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @include('sections.enquiry-band')
    </main>

    @include('sections.footer')

    <x-site-menu/>
@endsection
