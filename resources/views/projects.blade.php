@extends('layouts.app')

@section('title', 'Projects — ' . config('site.legal_name'))
@section('description', 'Selected projects by Sama Al Tariq Building Contracting L.L.C. — luxury residential, corporate and office fit-out, hospitality and wellness across Dubai.')
@section('image', 'images/projects/covers/jumeirah-golf-estate-villas.webp')

{{--
    The projects page. Header, the grouped projects, then the landing page's
    own enquiry card and footer — the same two this site closes every page
    with.
--}}
@section('content')
    <main>
        @include('sections.projects-page.header')
        @include('sections.projects-page.groups')
        @include('sections.inquiry')
    </main>

    @include('sections.footer')

    {{-- The overlay the header's MENU button opens; one per page. --}}
    <x-site-menu/>
@endsection
