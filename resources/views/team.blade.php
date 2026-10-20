@extends('layouts.app')

@php
    $team = config('site.team_page');
@endphp

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
        @php
            $img = fn (string $path) => \App\Support\Asset::versioned($path);
            // The team's own site photographs (source-photos/workers), three
            // columns for the wall beside the heading and all of them for the reel.
            $wall = [
                ['work-9', 'work-3', 'work-13', 'work-7'],
                ['work-12', 'work-10', 'work-14', 'work-1'],
                ['work-11', 'work-18', 'work-5', 'work-16'],
            ];
            $reel = ['work-1', 'work-2', 'work-3', 'work-4', 'work-5', 'work-7', 'work-8', 'work-9', 'work-10', 'work-11', 'work-12', 'work-13', 'work-14', 'work-15', 'work-16', 'work-18'];
        @endphp

        {{-- The heading beside a wall of the team at work: three columns of
             their own photographs, each drifting at its own pace on scroll
             (team-scenes.js). --}}
        <section class="tm-hero">
            <div class="shell tm-hero__grid">
                <div class="tm-hero__copy">
                    <p class="reveal text-fluid-label font-medium text-teal">{{ $team['label'] }}</p>
                    <h1 class="editorial-heading mt-3 text-[clamp(2rem,4.2vw,72px)] uppercase leading-[0.95] text-ink">
                        @foreach ($team['heading'] as $i => $line)
                            <span data-split data-split-delay="{{ $i * 120 }}" class="block {{ $i === 1 ? 'text-teal' : '' }}">{{ $line }}</span>
                        @endforeach
                    </h1>
                    <p class="reveal mt-5 display text-fluid-h2 leading-[1.25] text-ink">{{ $team['lead'] }}</p>
                    <p class="reveal mt-3 max-w-[44ch] text-fluid-body text-ink-muted">{{ $team['body'] }}</p>
                    <dl class="tm-figures">
                        @foreach ($team['figures'] as $figure)
                            <div class="reveal" style="transition-delay:{{ $loop->index * 80 }}ms">
                                <dd>{{ $figure['value'] }}</dd>
                                <dt>{{ $figure['label'] }}</dt>
                            </div>
                        @endforeach
                    </dl>
                </div>

                {{-- Three columns that run on their own, endlessly: each holds
                     its photographs twice and slides by exactly one set, so the
                     loop has no seam. The middle one runs the other way. --}}
                <div class="tm-wall" aria-hidden="true">
                    @foreach ($wall as $c => $column)
                        <div class="tm-wall__col {{ $c === 1 ? 'is-down' : '' }}" style="--wall-time:{{ [38, 46, 42][$c] }}s">
                            <div class="tm-wall__track">
                                @foreach ([false, true] as $copy)
                                    @foreach ($column as $photo)
                                        <img src="{{ $img("images/workers/$photo.webp") }}" alt="" loading="{{ ! $copy && $loop->first ? 'eager' : 'lazy' }}" decoding="async">
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- The six departments, each a tall card with its own photograph. --}}
        <section class="tm-depts">
            <div class="shell">
                <div class="tm-head">
                    <h2 class="editorial-heading text-fluid-section uppercase text-ink">
                        <span data-split class="block">Six Departments,</span>
                        <span data-split data-split-delay="110" class="block text-teal">One Team</span>
                    </h2>
                    <p class="reveal max-w-[36ch] text-fluid-body text-ink-muted">Every trade an interior needs, employed by Sama Al Tariq and run under one project lead.</p>
                </div>
                <ul class="tm-cards">
                    @foreach ($team['departments'] as $i => $dept)
                        <li class="tm-card reveal" style="transition-delay:{{ ($i % 3) * 90 }}ms">
                            {{-- Portrait, like the photographs themselves: the
                                 card takes the picture's shape, so a person on
                                 site is shown whole rather than cut to a strip. --}}
                            <div class="tm-card__pic">
                                <img src="{{ $img($dept['image']) }}" alt="{{ $dept['name'] }}" loading="lazy" decoding="async">
                                <span class="tm-card__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <h3 class="tm-card__name display">{{ $dept['name'] }}</h3>
                            </div>
                            <ul class="tm-card__roles">
                                @foreach ($dept['roles'] as $role)
                                    <li>{{ $role }}</li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- The reel: every site photograph, running slowly across. --}}
        <section class="tm-reel">
            <div class="shell tm-head">
                <h2 class="editorial-heading text-fluid-section uppercase text-white">
                    <span data-split class="block">On Site,</span>
                    <span data-split data-split-delay="110" class="block">Every Day</span>
                </h2>
                <p class="reveal max-w-[36ch] text-fluid-body text-white/85">No subcontractors. The people in these photographs are the people who build your project.</p>
            </div>
            <div class="marquee tm-reel__strip" style="--marquee-duration:{{ count($reel) * 4 }}s">
                <div class="marquee__track">
                    @foreach ([false, true] as $isClone)
                        <ul class="tm-reel__row" @if ($isClone) aria-hidden="true" @endif>
                            @foreach ($reel as $photo)
                                <li><img src="{{ $img("images/workers/$photo.webp") }}" alt="{{ $isClone ? '' : 'Sama Al Tariq team on site' }}" loading="lazy" decoding="async"></li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- How a project is staffed, drawn as a tree. --}}
        <section class="tm-tree">
            <div class="shell">
                <div class="tm-head">
                    <h2 class="editorial-heading text-fluid-section uppercase text-ink">
                        <span data-split class="block">How A Project</span>
                        <span data-split data-split-delay="110" class="block text-teal">Is Staffed</span>
                    </h2>
                    <p class="reveal max-w-[36ch] text-fluid-body text-ink-muted">One project manager runs the job, with a head for every trade and their team under them.</p>
                </div>
                <div class="tm-tree__card">
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
                </div>
            </div>
        </section>

        @if (! empty($team['leadership']))
            <section class="bg-white py-[clamp(3rem,5vw,80px)]">
                <div class="shell">
                    <h2 class="editorial-heading text-fluid-section uppercase text-ink"><span data-split class="block">Leadership</span></h2>
                    <div class="mt-[clamp(1.5rem,3vw,48px)] grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
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
                </div>
            </section>
        @endif

        @include('sections.enquiry-band')
    </main>

    @include('sections.footer')

    <x-site-menu/>
@endsection
