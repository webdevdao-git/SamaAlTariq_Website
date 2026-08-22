@extends('layouts.app')

@section('title', 'Joinery — ' . config('site.legal_name'))
@section('description', config('site.joinery_page.hero.summary'))
@section('image', config('site.joinery_page.hero.image.src'))

{{--
    The Joinery page: who makes and fits the interiors on a Sama Al Tariq
    project, and what that covers.

    Arranged after the frame the client sent — see the note in config/site.php
    for what could be read from it and what could not. The bands run in its
    order: the photographic hero with the title split across the gutters, the
    two words either side of a picture, the ecosystem statement, the numbered
    capabilities beside a photograph, the slab with a picture inside the line,
    the staggered process, and the dark question band.

    Then the enquiry card and the footer every page on this site closes with.
--}}
@section('content')
    @include('sections.joinery-page.hero')

    <main>
        @include('sections.joinery-page.wordmark')
        @include('sections.joinery-page.ecosystem')
        @include('sections.joinery-page.capabilities')
        @include('sections.joinery-page.detail')
        @include('sections.joinery-page.process')
        @include('sections.joinery-page.faqs')
        @include('sections.inquiry')
    </main>

    @include('sections.footer')
@endsection
