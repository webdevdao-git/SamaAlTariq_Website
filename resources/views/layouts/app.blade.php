<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#3fa7b3">

    {{--
        The page's own title and description, resolved once and then reused by
        the sharing tags below. They used to be yielded into <title> while
        og:title and og:description carried the landing page's copy hardcoded —
        so every page on the site shared as the home page, whatever it was.
    --}}
    @php($metaTitle = trim($__env->yieldContent('title', config('site.legal_name').' — Interior Fit-Out in Dubai')))
    @php($metaDescription = trim($__env->yieldContent('description', 'Sama Al Tariq is an interior fit-out contractor in Dubai: gypsum and ceilings, finishing, bespoke joinery and MEP for villas, hotels, restaurants and offices, all by our own team.')))
    @php($metaImage = \App\Support\Asset::versioned(trim($__env->yieldContent('image', 'images/hero.webp'))))

    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">

    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="robots" content="index,follow,max-image-preview:large">

    <x-favicons/>

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('site.legal_name') }}">
    <meta property="og:locale" content="en_AE">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:image:alt" content="{{ $metaTitle }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">

    {{-- What the search engines are told about the company itself, and where
         this page sits in the site. --}}
    <x-structured-data/>

    {{--
        Reverses the JavaScript-dependent states for a browser that will never
        run them: the reveal blocks stay hidden and the entry curtain stays up
        forever otherwise. Unlayered, so it beats the layered utilities.
    --}}
    <noscript>
        <style>
            .reveal,
            .reveal-media,
            .reveal-rise,
            .reveal-line { opacity: 1 !important; transform: none !important; }
            .line-mask > span,
            .unit-mask > span { transform: none !important; }
            .intro-curtain { display: none !important; }
            html { overflow: visible !important; }
        </style>
    </noscript>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    {{-- Entry curtain: in the HTML so it paints with the page, not after it.
         A plain teal wipe — the mark makes its entrance in the landing hero,
         so it is not spent here first. --}}
    <div class="intro-curtain" data-lifting="false" aria-hidden="true"></div>

    @yield('content')

    {{-- Last in the document and fixed, so it is over every page the layout
         draws without any section having to leave room for it. --}}
    <x-profile-download/>
</body>
</html>
