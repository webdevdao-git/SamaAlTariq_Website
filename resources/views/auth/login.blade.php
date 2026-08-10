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
    <div class="w-full max-w-[1290px] overflow-hidden rounded-[20px] bg-white shadow-[0_24px_70px_-30px_rgba(31,58,68,0.35)]">
        <div class="grid lg:grid-cols-2">

            <div class="flex flex-col justify-center px-[clamp(1.5rem,4vw,74px)] py-[clamp(2.5rem,4vw,60px)]">
                <div class="mx-auto w-full max-w-[470px]">

                    <div class="flex flex-col items-center text-center">
                        {{-- Teal variant: the stock mark is white and vanishes
                             on this card. --}}
                        <img src="{{ asset('images/logo-mark-teal.png') }}" alt="" width="540" height="462"
                             class="h-auto w-[68px]">
                        <p class="mt-3 text-[11px] font-semibold tracking-[0.3em] text-portal-ink">
                            BUILDING CONTRACTING L.L.C
                        </p>

                        <span aria-hidden="true" class="mt-5 block h-px w-14 bg-portal-ink/20"></span>

                        <h1 class="mt-5 font-wordmark leading-[1.05]">
                            <span class="block text-[clamp(1.9rem,3vw,40px)] tracking-[0.16em] text-portal-ink">PRIVATE</span>
                            {{-- nowrap: the lock-up reads as two lines, PRIVATE over CLIENT PORTAL, so
                                 the second must not break again --}}
                            <span class="mt-1 block whitespace-nowrap text-[clamp(1.7rem,3.1vw,44px)] tracking-[0.08em] text-portal">CLIENT PORTAL</span>
                        </h1>

                        <p class="mt-4 max-w-[340px] text-[15px] leading-relaxed text-ink-muted">
                            Secure access to your project updates, progress reports and site information.
                        </p>
                    </div>

                    @if (session('status'))
                        <p role="status" class="mt-6 rounded-lg bg-portal/10 px-4 py-3 text-sm text-portal-ink">
                            {{ session('status') }}
                        </p>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="mt-8 flex flex-col gap-5">
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
                                class="group mt-1 flex w-full items-center justify-center gap-3 rounded-[10px] bg-portal px-6 py-4
                                       text-[13px] font-bold tracking-[0.18em] text-white transition-colors hover:bg-portal-dark">
                            ACCESS PORTAL
                            <x-icon name="arrow-long-right" class="transition-transform duration-300 group-hover:translate-x-1"/>
                        </button>
                    </form>

                    <div class="mt-8 flex items-center gap-4" aria-hidden="true">
                        <span class="h-px flex-1 bg-portal-ink/12"></span>
                        <x-icon name="shield-check" class="text-portal"/>
                        <span class="h-px flex-1 bg-portal-ink/12"></span>
                    </div>

                    <p class="mt-5 text-center text-[11px] font-semibold tracking-[0.16em]">
                        <span class="text-portal-ink">SECURE. PRIVATE. PROFESSIONAL.</span><br>
                        <span class="text-portal">BUILT ON TRUST</span>
                    </p>

                    <ul class="mt-7 grid grid-cols-3 gap-2 border-t border-portal-ink/12 pt-6">
                        @foreach ([
                            ['chart',    'TRACK',  'PROGRESS'],
                            ['document', 'VIEW',   'REPORTS'],
                            ['gallery',  'PHOTO',  'GALLERY'],
                        ] as [$icon, $lineOne, $lineTwo])
                            <li class="flex items-center justify-center gap-2 text-portal-ink">
                                <x-icon :name="$icon" class="text-portal-ink/70"/>
                                <span class="text-[10px] leading-tight font-bold tracking-[0.1em]">
                                    {{ $lineOne }}<br>{{ $lineTwo }}
                                </span>
                                <x-icon name="chevron-right" class="text-ink-muted"/>
                            </li>
                        @endforeach
                    </ul>

                    <p class="mt-6 flex items-center justify-center gap-2 text-[12px] text-ink-muted">
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
