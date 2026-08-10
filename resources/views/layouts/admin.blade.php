<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#71b0b9">
    <meta name="robots" content="noindex, nofollow">

    <title>@yield('title', 'Admin') · {{ config('site.name') }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="48x48">
    <link rel="icon" type="image/png" href="{{ asset('icon-32.png') }}" sizes="32x32">

    @vite(['resources/css/app.css', 'resources/js/portal.js'])
</head>
<body class="min-h-svh bg-[#f6f8f9] antialiased">

{{--
    Admin shell. Same sidebar geometry as the client portal, different nav —
    the client's "current project" card has no meaning here, where every screen
    already works across all projects.
--}}
<div class="lg:flex">

    <button type="button" data-sidebar-open
            class="fixed top-4 left-4 z-40 grid size-11 place-items-center rounded-xl border border-portal-ink/12 bg-white text-portal-ink shadow-sm lg:hidden"
            aria-label="Open menu" aria-controls="admin-sidebar" aria-expanded="false">
        <x-icon name="menu" size="22"/>
    </button>

    <div data-sidebar-backdrop
         class="pointer-events-none fixed inset-0 z-40 bg-portal-ink/40 opacity-0 transition-opacity duration-300 lg:hidden"></div>

    <aside id="admin-sidebar" data-sidebar
           class="fixed inset-y-0 left-0 z-50 flex w-[300px] -translate-x-full flex-col border-r border-portal-ink/10 bg-white
                  transition-transform duration-300 lg:sticky lg:top-0 lg:h-svh lg:w-[300px] lg:translate-x-0 xl:w-[345px]">

        <div class="flex items-center justify-between px-7 pt-7 lg:justify-center">
            <a href="{{ route('admin.dashboard') }}" class="block" aria-label="{{ config('site.name') }} admin home">
                <img src="{{ asset('images/logo-mark-teal.png') }}" alt="" width="540" height="462" class="mx-auto h-auto w-[62px]">
                <span class="mt-1.5 block text-center font-wordmark text-[13px] font-semibold tracking-[0.14em] text-portal">
                    {{ Str::upper(config('site.name')) }}
                </span>
            </a>
            <button type="button" data-sidebar-close class="text-portal-ink lg:hidden" aria-label="Close menu">
                <x-icon name="menu" size="22"/>
            </button>
        </div>

        <nav class="mt-9 flex flex-col gap-1 px-4" aria-label="Admin">
            @foreach ([
                ['admin.dashboard', 'grid',       'Dashboard'],
                ['admin.projects',  'file-plus',  'Add Projects'],
                ['admin.images',    'image-plus', 'Images Upload'],
                ['admin.reports',   'document',   'Reports Upload'],
                ['admin.settings',  'user-cog',   'Profile Settings'],
            ] as [$route, $icon, $label])
                @php($active = request()->routeIs($route.'*'))
                <a href="{{ route($route) }}"
                   @if ($active) aria-current="page" @endif
                   class="flex items-center gap-3.5 rounded-xl px-3.5 py-3 text-[15px] transition-colors
                          {{ $active ? 'bg-portal/10 font-semibold text-portal' : 'text-portal-ink hover:bg-alabaster' }}">
                    <x-icon :name="$icon"/>
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="mt-auto border-t border-portal-ink/10 px-6 py-5">
            <div class="flex items-center gap-3">
                <span class="grid size-10 shrink-0 place-items-center rounded-full bg-portal/15 text-[13px] font-bold text-portal">
                    {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
                </span>
                <span class="min-w-0">
                    <span class="block truncate text-[15px] font-semibold text-portal-ink">{{ auth()->user()->name }}</span>
                    <span class="block text-[12px] text-ink-muted">Administrator</span>
                </span>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="flex items-center gap-3.5 text-[15px] text-portal-ink transition-colors hover:text-portal">
                    <x-icon name="logout"/>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="min-w-0 flex-1 px-[clamp(1rem,3vw,44px)] pt-[clamp(4.5rem,5vw,40px)] pb-10 lg:pt-[clamp(2rem,3vw,40px)]">

        <header class="mb-8 flex flex-wrap items-start justify-between gap-4">
            <div class="flex items-start gap-4">
                <span class="mt-1 text-portal-ink/70">
                    <x-icon :name="View::yieldContent('icon') ?: 'grid'" size="26"/>
                </span>
                <div>
                    <h1 class="font-wordmark text-[clamp(1.7rem,2.6vw,34px)] text-portal-ink">@yield('heading')</h1>
                    <p class="mt-1 text-[15px] text-ink-muted">@yield('subheading')</p>
                </div>
            </div>

            {{-- Today's date, as in the reference. A <time> element so the
                 machine-readable value is present, not just the display form. --}}
            <time datetime="{{ now()->toDateString() }}"
                  class="flex items-center gap-3 rounded-xl border border-portal-ink/12 bg-white px-5 py-3 text-[15px] text-portal-ink">
                {{ now()->format('F j, Y') }}
                <x-icon name="calendar" size="18" class="text-ink-muted"/>
            </time>
        </header>

        @if (session('status'))
            <p role="status" class="mb-6 rounded-xl bg-portal/10 px-4 py-3 text-sm text-portal-ink">{{ session('status') }}</p>
        @endif

        @yield('content')
    </main>
</div>
</body>
</html>
