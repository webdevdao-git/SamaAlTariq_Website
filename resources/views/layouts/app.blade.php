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
        Regenerate with deploy/make-favicons.py after changing
        public/images/logo-mark.png.

        The ?v= is the file's mtime. Hostinger's CDN caches these for seven days
        (max-age=604800), so without it a replaced icon keeps serving the old
        one for a week — which is exactly what happened the first time these
        were swapped. Changing the file changes the URL, so the CDN treats it as
        a new resource.
    --}}
    @php($iconVersion = fn (string $file) => asset($file).'?v='.(@filemtime(public_path($file)) ?: 1))

    <link rel="icon" href="{{ $iconVersion('favicon.ico') }}" sizes="48x48">
    <link rel="icon" type="image/png" href="{{ $iconVersion('icon-32.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ $iconVersion('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ $iconVersion('site.webmanifest') }}">

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
