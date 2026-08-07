@extends('layouts.app')

@section('content')
    <main>
        @include('sections.hero')
        @include('sections.about')
        @include('sections.clients')
        @include('sections.projects')
        @include('sections.services')
        @include('sections.process')
        @include('sections.precision')
        @include('sections.inquiry')
    </main>

    @include('sections.footer')

    @stack('data')
@endsection
