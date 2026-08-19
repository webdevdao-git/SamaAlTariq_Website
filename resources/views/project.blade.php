@extends('layouts.app')

@section('title', $project['title'] . ' — ' . config('site.legal_name'))
@section('description', $page['lead'])
@section('image', 'images/projects/covers/' . $slug . '.webp')

{{--
    One project, from Figma frame 1472:1339: the photograph and title, the lead
    beside its gallery, the specification, the related projects, and then the
    same enquiry card and footer every page on this site closes with.
--}}
@section('content')
    @include('sections.project-page.hero', ['slug' => $slug, 'project' => $project, 'slides' => $slides])

    <main>
        @include('sections.project-page.intro', ['slug' => $slug, 'page' => $page, 'photographs' => $photographs])
        @include('sections.project-page.spec', ['project' => $project, 'page' => $page])
        @include('sections.project-page.related', ['related' => $related])
        @include('sections.inquiry')
    </main>

    @include('sections.footer')
@endsection
