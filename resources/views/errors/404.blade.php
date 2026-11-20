@extends('layouts.app')

@section('title', 'Page Not Found — ' . config('site.legal_name'))
@section('description', 'The page you were looking for has moved or no longer exists.')

{{--
    A missing page in the site's own dress: the dark-toned header, one line
    and the two ways back, then the footer every page closes with.
--}}
@section('content')
    <x-site-header tone="dark"/>

    <main class="bg-white">
        <section class="shell flex min-h-[70svh] flex-col items-start justify-center pt-[clamp(7rem,14vw,200px)] pb-[clamp(3rem,6vw,96px)]">
            <p class="text-fluid-label font-medium text-teal">404</p>
            <h1 class="editorial-heading mt-3 text-[clamp(2.25rem,5vw,88px)] uppercase leading-[0.95] text-ink">
                <span class="block">This Page</span>
                <span class="block text-teal">Has Moved</span>
            </h1>
            <p class="mt-5 max-w-[44ch] text-fluid-body text-ink-muted">The page you were looking for no longer exists. Our projects and services are a click away.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('home') }}" class="pill group">
                    Back To Home <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                </a>
                <a href="{{ route('projects') }}" class="pill group">
                    Explore Projects <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                </a>
            </div>
        </section>
    </main>

    @include('sections.footer')
@endsection
