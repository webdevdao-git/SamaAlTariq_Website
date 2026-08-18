@extends('layouts.auth')
@section('title', 'Client Login')

@section('content')
    {{--
        Ported from the previous app's /login: a split card with the form on the
        left and the interior photograph on the right.

        Below `lg` the photo is dropped rather than stacked. It is decorative,
        and on a phone a 1400×1867 image above the form would push the fields
        off screen for no benefit.
    --}}
    <div class="w-full max-w-[1060px] overflow-hidden rounded-[18px] bg-white shadow-[0_24px_70px_-30px_rgba(31,58,68,0.35)]">
        <div class="grid lg:grid-cols-2">

            <div class="flex flex-col justify-center px-[clamp(1.25rem,3vw,52px)] py-[clamp(2rem,3vw,44px)]">
                <div class="mx-auto w-full max-w-[392px]">

                    <div class="flex flex-col items-center text-center">
                        {{-- Teal variant: the stock mark is white and vanishes
                             on this card. --}}
                        <img src="{{ asset('images/logo-mark-teal.png') }}" alt="" width="540" height="462"
                             class="h-auto w-[54px]">
                        <p class="mt-2.5 text-[10px] font-semibold tracking-[0.28em] text-portal-ink">
                            BUILDING CONTRACTING L.L.C
                        </p>

                        <span aria-hidden="true" class="mt-4 block h-px w-12 bg-portal-ink/20"></span>

                        <h1 class="mt-4 font-wordmark leading-[1.05]">
                            <span class="block text-[clamp(1.5rem,2.3vw,31px)] tracking-[0.16em] text-portal-ink">PRIVATE</span>
                            {{-- nowrap: the lock-up reads as two lines, PRIVATE over CLIENT PORTAL, so
                                 the second must not break again --}}
                            <span class="mt-1 block whitespace-nowrap text-[clamp(1.35rem,2.45vw,34px)] tracking-[0.08em] text-portal">CLIENT PORTAL</span>
                        </h1>

                        <p class="mt-3 max-w-[310px] text-[13.5px] leading-relaxed text-ink-muted">
                            Secure access to your project updates, progress reports and site information.
                        </p>
                    </div>

                    @if (session('status'))
                        <p role="status" class="mt-6 rounded-lg bg-portal/10 px-4 py-3 text-sm text-portal-ink">
                            {{ session('status') }}
                        </p>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="mt-6 flex flex-col gap-4">
                        @csrf

                        <div>
                            <label for="identifier" class="mb-2 block text-[11px] font-bold tracking-[0.16em] text-portal-ink">
                                EMAIL ADDRESS
                            </label>
                            <div class="relative">
                                <span aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-4 grid place-items-center text-ink-muted">
                                    <x-icon name="user"/>
                                </span>
                                <input id="identifier" name="identifier" type="text" required autofocus
                                       autocomplete="username" placeholder="Enter your email"
                                       value="{{ old('identifier') }}" class="portal-field pl-12"
                                       @error('identifier') aria-invalid="true" aria-describedby="identifier-error" @enderror>
                            </div>
                            @error('identifier')
                                <span id="identifier-error" class="field-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-[11px] font-bold tracking-[0.16em] text-portal-ink">
                                PASSWORD
                            </label>
                            <div class="relative">
                                <span aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-4 grid place-items-center text-ink-muted">
                                    <x-icon name="lock"/>
                                </span>
                                <input id="password" name="password" type="password" required
                                       autocomplete="current-password" placeholder="Enter your password"
                                       class="portal-field px-12">
                                {{-- The button carries its own label because the icon alone
                                     says nothing, and it flips as the state changes. --}}
                                <button type="button" data-password-toggle="password"
                                        aria-label="Show password" aria-pressed="false"
                                        class="absolute inset-y-0 right-2 grid w-10 place-items-center rounded-lg text-ink-muted transition-colors hover:text-portal-ink">
                                    <x-icon name="eye" data-icon-show/>
                                    <x-icon name="eye-off" data-icon-hide class="hidden"/>
                                </button>
                            </div>
                            @error('password')<span class="field-error" role="alert">{{ $message }}</span>@enderror
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <label class="flex items-center gap-2.5 text-sm text-portal-ink">
                                <input type="checkbox" name="remember" value="1" @checked(old('remember'))
                                       class="size-[18px] rounded-[4px] border-ink/25 text-portal focus:ring-portal">
                                Remember me
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm text-portal hover:underline">
                                Forgot password?
                            </a>
                        </div>

                        <button type="submit"
                                class="group mt-1 flex w-full items-center justify-center gap-3 rounded-[10px] bg-portal px-6 py-3.5
                                       text-[12px] font-bold tracking-[0.18em] text-white transition-colors hover:bg-portal-dark">
                            ACCESS PORTAL
                            <x-icon name="arrow-long-right" class="transition-transform duration-300 group-hover:translate-x-1"/>
                        </button>
                    </form>

                    <div class="mt-6 flex items-center gap-4" aria-hidden="true">
                        <span class="h-px flex-1 bg-portal-ink/12"></span>
                        <x-icon name="shield-check" class="text-portal"/>
                        <span class="h-px flex-1 bg-portal-ink/12"></span>
                    </div>

                    <p class="mt-4 text-center text-[10px] font-semibold tracking-[0.16em]">
                        <span class="text-portal-ink">SECURE. PRIVATE. PROFESSIONAL.</span><br>
                        <span class="text-portal">BUILT ON TRUST</span>
                    </p>

                    {{--
                        The three carried a chevron and did nothing, which is a
                        promise a signed-out visitor cannot cash. Each one now
                        opens a picture of the screen it names.

                        The pictures are of the portal itself, taken against a
                        made-up project — "Marina Heights Villa", a demo client,
                        captions written for the purpose — because this page is
                        public and a real client's project name, documents and
                        photographs are not.

                        Links, not buttons, and the href is the picture: with no
                        JavaScript they simply open it. The script upgrades them
                        into a dialog in place.
                    --}}
                    <ul class="mt-5 grid grid-cols-3 gap-2 border-t border-portal-ink/12 pt-4">
                        @foreach ([
                            ['chart',    'TRACK',  'PROGRESS', 'progress', 'Track progress', 'Every stage of the build, with its own status and the date it is due.'],
                            ['document', 'VIEW',   'REPORTS',  'reports',  'View reports',   'Progress reports and paperwork, shared as they are issued.'],
                            ['gallery',  'PHOTO',  'GALLERY',  'gallery',  'Photo gallery',  'Site and finish photography, captioned and dated.'],
                        ] as [$icon, $lineOne, $lineTwo, $shot, $title, $blurb])
                            <li>
                                <a href="{{ \App\Support\Asset::versioned("images/portal-preview/{$shot}.webp") }}"
                                   data-preview="{{ $title }}" data-preview-blurb="{{ $blurb }}"
                                   class="flex w-full items-center justify-center gap-2 rounded-lg py-2 text-portal-ink transition-colors hover:text-portal focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-portal">
                                    <x-icon :name="$icon" class="text-portal-ink/70"/>
                                    <span class="text-[10px] leading-tight font-bold tracking-[0.1em]">
                                        {{ $lineOne }}<br>{{ $lineTwo }}
                                    </span>
                                    <x-icon name="chevron-right" class="text-ink-muted"/>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    {{-- One dialog for the three, filled in on open. Native
                         <dialog>, so Escape closes it, the page behind is inert
                         and focus is handled without any of it being written
                         here. --}}
                    <dialog data-preview-dialog
                            class="m-auto max-h-[88vh] w-[min(1100px,92vw)] flex-col overflow-hidden rounded-2xl p-0 backdrop:bg-portal-ink/60 backdrop:backdrop-blur-sm open:flex">
                        <div class="flex shrink-0 items-start justify-between gap-4 border-b border-portal-ink/10 px-5 py-4">
                            <span>
                                <span data-preview-title class="block font-wordmark text-[15px] tracking-[0.08em] text-portal-ink"></span>
                                <span data-preview-blurb class="mt-1 block text-[13px] text-ink-muted"></span>
                            </span>
                            <button type="button" data-preview-close
                                    class="shrink-0 rounded-lg px-3 py-2 text-[13px] font-semibold text-portal-ink transition-colors hover:text-portal focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-portal">
                                Close
                            </button>
                        </div>
                        {{-- The picture is a screen 1600 wide: unbounded, the
                             dialog grew past the window and a <dialog> that is
                             taller than the viewport cannot centre itself.
                             Capped, it scrolls inside its own box. --}}
                        <div class="min-h-0 overflow-auto">
                            <img data-preview-image src="" alt="" class="block h-auto w-full">
                        </div>
                    </dialog>

                    <p class="mt-4 flex items-center justify-center gap-2 text-[11.5px] text-ink-muted">
                        <x-icon name="lock" size="14"/>
                        All data is encrypted and secure
                    </p>
                </div>
            </div>

            <div class="relative hidden lg:block">
                <img src="{{ asset('images/portal-login.webp') }}" alt=""
                     width="1400" height="1867" fetchpriority="high" decoding="async"
                     class="absolute inset-0 h-full w-full object-cover">
            </div>
        </div>
    </div>
@endsection
