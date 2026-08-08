<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#3fa7b3">

    <title>@yield('title', config('site.legal_name') . ' — Building With Precision')</title>
    <meta name="description" content="@yield('description', 'Sama Al Tariq delivers exceptional construction, engineering, and contracting solutions across Dubai — fit-out, design & build, villa renovation, joinery, and millwork.')">

    <link rel="canonical" href="{{ url()->current() }}">

    {{--
        The logo mark is near-white, so every icon here is the mark on the brand
        teal rather than on transparency — on a light tab bar a transparent
        version is invisible. Regenerate with deploy/make-favicons.py after
        changing public/images/logo-mark.png.
    --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="48x48">
    <link rel="icon" type="image/png" href="{{ asset('icon-32.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('site.legal_name') }}">
    <meta property="og:title" content="Building With Precision — {{ config('site.name') }}">
    <meta property="og:description" content="Exceptional construction, engineering, and contracting solutions that shape modern communities.">
    <meta property="og:image" content="{{ asset('images/hero.webp') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">

    {{--
        Reverses the JavaScript-dependent states for a browser that will never
        run them: the reveal blocks stay hidden and the entry curtain stays up
        forever otherwise. Unlayered, so it beats the layered utilities.
    --}}
    <noscript>
        <style>
            .reveal { opacity: 1 !important; transform: none !important; }
            .line-mask > span { transform: none !important; }
            .intro-curtain { display: none !important; }
            html { overflow: visible !important; }
        </style>
    </noscript>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    {{-- Entry curtain: in the HTML so it paints with the page, not after it. --}}
    <div class="intro-curtain" data-lifting="false" aria-hidden="true">
        <div class="intro-curtain__mark">
            <img src="{{ asset('images/logo-mark.png') }}" alt=""
                 class="h-auto w-[clamp(56px,6vw,96px)]" width="540" height="462">
        </div>
    </div>

    @yield('content')
</body>
</html>
