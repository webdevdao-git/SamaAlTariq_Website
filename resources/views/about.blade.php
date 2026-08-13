@extends('layouts.app')

@section('title', 'About — ' . config('site.legal_name'))
@section('description', 'Sama Al Tariq Building Contracting L.L.C. is a Dubai-based construction and fit-out company, managing every stage from tender and planning through construction and handover.')

{{--
    Figma: frame 1377:3, "About Us", 1728×8276.

    The order below is the order of the frame. Two of its bands are the landing
    page's own sections, reused rather than rebuilt — the enquiry card
    (frame 2134280727, the same layer name as the landing page's) and the
    footer, both 980 and 774 tall in each file.
--}}
@section('content')
    <main>
        @include('sections.about-page.hero')
        @include('sections.about-page.intro')
        @include('sections.about-page.vision')
        @include('sections.about-page.approach')
        @include('sections.about-page.values')
        @include('sections.about-page.purpose')
        @include('sections.inquiry')
    </main>

    @include('sections.footer')
@endsection
