@extends('layouts.app')

@section('content')
    <main data-page="home">
        @include('sections.hero')
        @include('sections.about')
        @include('sections.numbers')
        @include('sections.clients')
        @include('sections.projects')
        @include('sections.scope')
        @include('sections.sectors')
        @include('sections.joinery-teaser')
        @include('sections.team-band')
        @include('sections.inquiry')
    </main>

    @include('sections.footer')

    @stack('data')
@endsection
