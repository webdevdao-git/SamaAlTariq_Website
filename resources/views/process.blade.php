@extends('layouts.app')

@section('title', 'Our Process — ' . config('site.legal_name'))
@section('description', config('site.process_page.hero.body'))
@section('image', config('site.process_page.hero.image'))

{{--
    How the work is delivered, from Figma frame 1508:2: the photographic hero,
    the four steps in brief, each of them again as a phase with its four
    points, the consultation band, and then the same enquiry card and footer
    every page on this site closes with.
--}}
@section('content')
    @include('sections.process-page.hero')

    <main>
        @include('sections.process-page.steps')
        @include('sections.process-page.phases')
        @include('sections.process-page.consultation')
        @include('sections.inquiry')
    </main>

    @include('sections.footer')
@endsection
