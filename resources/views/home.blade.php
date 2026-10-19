@extends('layouts.app')

@section('content')
    <main data-page="home">
        @include('sections.hero')
        {{-- Who we are, services, how we work, projects, Alwan and questions,
             as cards over one held photograph. The sections this replaced
             (about, numbers, clients, projects, scope, sectors,
             joinery-teaser, team-band) are kept in sections/ for now. --}}
        @include('sections.trust')
        @include('sections.story')
        @include('sections.inquiry')
    </main>

    @include('sections.footer')

    @stack('data')
@endsection
