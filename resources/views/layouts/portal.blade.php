<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portal') · {{ config('site.name') }}</title>
    <meta name="robots" content="noindex, nofollow">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-svh bg-mist antialiased">
    <header class="border-b border-black/10 bg-white">
        <div class="shell flex items-center justify-between gap-6 py-5">
            <a href="{{ route('portal.dashboard') }}" class="font-wordmark text-2xl font-semibold text-ink">
                {{ Str::upper(config('site.name')) }}
            </a>

            <nav class="flex items-center gap-6 text-sm">
                @auth
                    @can('viewAny', App\Models\User::class)
                        <a href="{{ route('admin.clients.index') }}" class="text-ink hover:text-teal">Clients</a>
                    @endcan
                    <span class="text-ink-muted">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-ink hover:text-teal">Sign out</button>
                    </form>
                @endauth
            </nav>
        </div>
    </header>

    @if (session('status'))
        <div class="shell pt-6">
            <p role="status" class="rounded-lg bg-teal/10 px-4 py-3 text-sm text-teal">{{ session('status') }}</p>
        </div>
    @endif

    <main class="shell py-10">
        @yield('content')
    </main>
</body>
</html>
