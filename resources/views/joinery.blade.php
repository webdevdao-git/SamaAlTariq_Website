@extends('layouts.app')

@section('title', 'Joinery — ' . config('site.legal_name'))
@section('description', config('site.joinery_page.hero.lead'))
@section('image', config('site.joinery_page.hero.image.src'))

{{--
    The Joinery page: who makes and fits the interiors on a Sama Al Tariq
    project, and what that covers.

    It runs the partner band first — the page exists to say that Alwan Design
    delivers this work, so it says it before anything else — then the scope,
    which is the services page's own two joinery entries rather than a second
    description of them, then three photographs of the finished thing, and the
    enquiry card and footer every page closes with.
--}}
@section('content')
    @include('sections.joinery-page.hero')

    <main>
        @include('sections.joinery-page.partner')
        @include('sections.joinery-page.scope')
        @include('sections.joinery-page.gallery')
        @include('sections.inquiry')
    </main>

    @include('sections.footer')
@endsection
