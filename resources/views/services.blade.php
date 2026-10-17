@extends('layouts.app')

@section('title', 'Services — ' . config('site.legal_name'))
@section('description', config('site.services_page.intro.body'))
@section('image', config('site.services_page.hero.image'))

{{--
    What the company does: the hero, the opening statement, the eight services
    as an index, the two ways to engage, how works run inside occupied spaces,
    the consultation band, common questions, and the enquiry band.

    The consultation band is the process page's own section, not a copy — the
    same frame appears in both, so it is included rather than rewritten.
--}}
@section('content')
    @include('sections.services-page.hero')

    <main>
        @include('sections.services-page.intro')
        @include('sections.services-page.services')
        @include('sections.services-page.delivery')
        @include('sections.services-page.live')
        @include('sections.process-page.consultation')
        @include('sections.services-page.faq')
        @include('sections.enquiry-band')
    </main>

    @include('sections.footer')
@endsection
