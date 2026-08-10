@php($current = $current ?? null)
@php($switchable = $switchable ?? collect())

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#71b0b9">
    <meta name="robots" content="noindex, nofollow">

    <title>@yield('title', 'Portal') · {{ config('site.name') }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="48x48">
    <link rel="icon" type="image/png" href="{{ asset('icon-32.png') }}" sizes="32x32">

    @vite(['resources/css/app.css', 'resources/js/portal.js'])
</head>
<body class="min-h-svh bg-[#f6f8f9] antialiased">

{{--
    Two-column shell ported from the previous portal: a white sidebar carrying
    the brand, the current project, the section links and the account, with the
    content on a soft grey field.

    Below `lg` the sidebar becomes a slide-over rather than stacking. At 345px
    wide it would otherwise push the content off a phone screen, and a client
    checking progress on site is the likeliest visitor of all.
--}}
<div class="lg:flex">

    <button type="button" data-sidebar-open
            class="fixed top-4 left-4 z-40 grid size-11 place-items-center rounded-xl border border-portal-ink/12 bg-white text-portal-ink shadow-sm lg:hidden"
            aria-label="Open menu" aria-controls="portal-sidebar" aria-expanded="false">
        <x-icon name="menu" size="22"/>
    </button>

    <div data-sidebar-backdrop
         class="pointer-events-none fixed inset-0 z-40 bg-portal-ink/40 opacity-0 transition-opacity duration-300 lg:hidden"></div>

    <aside id="portal-sidebar" data-sidebar
           class="fixed inset-y-0 left-0 z-50 flex w-[300px] -translate-x-full flex-col border-r border-portal-ink/10 bg-white
                  transition-transform duration-300 lg:sticky lg:top-0 lg:h-svh lg:w-[300px] lg:translate-x-0 xl:w-[345px]">

        <div class="flex items-center justify-between px-7 pt-7 lg:justify-center">
            <a href="{{ route('portal.dashboard') }}" class="block" aria-label="{{ config('site.name') }} portal home">
                <img src="{{ asset('images/logo-mark-teal.png') }}" alt="" width="540" height="462" class="mx-auto h-auto w-[62px]">
                <span class="mt-1.5 block text-center font-wordmark text-[13px] font-semibold tracking-[0.14em] text-portal">
                    {{ Str::upper(config('site.name')) }}
                </span>
            </a>
            <button type="button" data-sidebar-close class="text-portal-ink lg:hidden" aria-label="Close menu">
                <x-icon name="menu" size="22"/>
            </button>
        </div>

        @if ($current)
            <div class="mx-6 mt-7 rounded-xl border border-portal-ink/12 p-4">
                <p class="text-[10px] font-bold tracking-[0.16em] text-ink-muted">CURRENT PROJECT</p>

                <div class="mt-3 flex items-center gap-3">
                    <span class="block size-11 shrink-0 overflow-hidden rounded-lg bg-alabaster">
                        @if ($cover = $current->images->first())
                            <img src="{{ route('portal.files.show', ['path' => $cover->storage_path]) }}" alt=""
                                 loading="lazy" class="h-full w-full object-cover">
                        @endif
                    </span>
                    <span class="min-w-0">
                        <span class="block truncate text-[15px] font-semibold text-portal-ink">{{ $current->title }}</span>
                        @if ($current->location)
                            <span class="mt-0.5 flex items-center gap-1 text-[12px] text-ink-muted">
                                <x-icon name="map-pin" size="13"/>
                                <span class="truncate">{{ $current->location }}</span>
                            </span>
                        @endif
                    </span>
                </div>

                {{-- Only shown when there is somewhere to switch to. --}}
                @if ($switchable->count() > 1)
                    <form method="GET" class="mt-3">
                        <label for="project-switch" class="sr-only">Switch project</label>
                        <select id="project-switch" name="project" onchange="this.form.submit()"
                                class="w-full rounded-lg border border-portal-ink/12 bg-white px-3 py-2 text-[13px] text-portal-ink">
                            @foreach ($switchable as $option)
                                <option value="{{ $option->id }}" @selected($option->id === $current->id)>{{ $option->title }}</option>
                            @endforeach
                        </select>
                    </form>
                @endif
            </div>
        @endif

        <nav class="mt-7 flex flex-col gap-1 px-4" aria-label="Portal">
            @foreach ([
                ['portal.dashboard', 'home',     'Project Overview'],
                ['portal.images',    'gallery',  'View Project Images'],
                ['portal.documents', 'document', 'Reports & Documents'],
            ] as [$route, $icon, $label])
                @php($active = request()->routeIs($route))
                <a href="{{ route($route, $current ? ['project' => $current->id] : []) }}"
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
                    {{ Str::upper(Str::substr(auth()->user()->name, 0, 2)) }}
                </span>
                <span class="min-w-0">
                    <span class="block truncate text-[15px] font-semibold text-portal-ink">{{ auth()->user()->name }}</span>
                    <span class="block text-[12px] text-ink-muted">{{ auth()->user()->isAdmin() ? 'Administrator' : 'Client' }}</span>
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

    <main class="min-w-0 flex-1 px-[clamp(1rem,3vw,44px)] pt-[clamp(4.5rem,5vw,44px)] pb-10 lg:pt-[clamp(2rem,3vw,44px)]">
        @if (session('status'))
            <p role="status" class="mb-6 rounded-xl bg-portal/10 px-4 py-3 text-sm text-portal-ink">{{ session('status') }}</p>
        @endif

        @yield('content')

        <p class="mt-12 text-center text-[13px] text-ink-muted">
            © {{ now()->year }} {{ config('site.legal_name') }} All rights reserved.
        </p>
    </main>
</div>
</body>
</html>
