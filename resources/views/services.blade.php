@extends('layouts.app')

@section('title', 'Services — ' . config('site.legal_name'))
@section('description', config('site.services_page.intro.body'))

{{--
    What the company does, from Figma frame 1545:2: the photographic hero, the
    opening statement, ten services each with its own photograph, and then the
    consultation band, enquiry card and footer this site closes every page with.

    The consultation band is the process page's own section, not a copy — the
    same frame appears in both, so it is included rather than rewritten.
--}}
@section('content')
    @include('sections.services-page.hero')

    <main>
        @include('sections.services-page.intro')
        @include('sections.services-page.services')
        @include('sections.process-page.consultation')
        @include('sections.inquiry')
    </main>

    @include('sections.footer')
@endsection
