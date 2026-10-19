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
                    <h1 class="editorial-heading mt-3 text-[clamp(1.716rem,3.276vw,56.16px)] uppercase text-ink">
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

        @include('sections.team-page-story')

        @include('sections.enquiry-band')
    </main>

    @include('sections.footer')

    <x-site-menu/>
@endsection
