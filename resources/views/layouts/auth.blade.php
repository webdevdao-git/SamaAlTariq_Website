<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#71b0b9">
    <meta name="robots" content="noindex, nofollow">

    <title>@yield('title', 'Sign in') | {{ config('site.legal_name') }}</title>

    <x-favicons/>

    @vite(['resources/css/app.css', 'resources/js/portal.js'])
</head>
{{-- The soft blue-grey wash the previous app used behind the card. --}}
<body class="grid min-h-svh place-items-center bg-[linear-gradient(160deg,#eef2f4_0%,#e6ecee_50%,#eef1f3_100%)] p-[clamp(0.75rem,3vw,48px)] antialiased">
    @yield('content')
</body>
</html>
