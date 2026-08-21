@extends('layouts.app')

@section('title', 'Joinery — ' . config('site.legal_name'))
@section('description', config('site.joinery_page.hero.lead'))
@section('image', config('site.joinery_page.studio.image.src'))

{{--
    The Joinery page: who makes and fits the interiors on a Sama Al Tariq
    project, and what that covers.

    Arranged after homekode.com/pages/interior-design-services, which the
    client asked this page to follow — see the note in config/site.php for
    what was taken from it and what deliberately was not. The bands run in
    that page's order: title over centred copy, the split with the partner's
    mark against a panel, the ruled heading over three upright pictures, the
    card on a dark band, picture beside copy, the question stack, and two
    large pictures to close.

    Then the enquiry card and the footer every page on this site closes with.
--}}
@section('content')
    @include('sections.joinery-page.hero')

    <main>
        @include('sections.joinery-page.scope')
        @include('sections.joinery-page.package')
        @include('sections.joinery-page.studio')
        @include('sections.joinery-page.faqs')
        @include('sections.joinery-page.gallery')
        @include('sections.inquiry')
    </main>

    @include('sections.footer')
@endsection
