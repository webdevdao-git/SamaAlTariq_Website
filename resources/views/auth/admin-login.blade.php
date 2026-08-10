@extends('layouts.auth')
@section('title', 'Admin Sign In')

@section('content')
    {{--
        The staff door. Deliberately plainer than the client portal's split
        card — no marketing photograph, no feature links. Nobody arrives here
        needing to be sold the product; they arrive to get to work.
    --}}
    <div class="w-full max-w-[430px] rounded-[18px] bg-white p-[clamp(1.5rem,3vw,44px)] shadow-[0_24px_70px_-30px_rgba(31,58,68,0.35)]">

        <div class="flex flex-col items-center text-center">
            <img src="{{ asset('images/logo-mark-teal.png') }}" alt="" width="540" height="462" class="h-auto w-[54px]">
            <p class="mt-2.5 text-[10px] font-semibold tracking-[0.28em] text-portal-ink">
                BUILDING CONTRACTING L.L.C
            </p>

            <span aria-hidden="true" class="mt-4 block h-px w-12 bg-portal-ink/20"></span>

            <h1 class="mt-4 font-wordmark leading-[1.05]">
                <span class="block text-[clamp(1.4rem,2.2vw,29px)] tracking-[0.16em] text-portal-ink">ADMIN</span>
                <span class="mt-1 block whitespace-nowrap text-[clamp(1.25rem,2.2vw,31px)] tracking-[0.08em] text-portal">CONTROL PANEL</span>
            </h1>

            <p class="mt-3 max-w-[300px] text-[13.5px] leading-relaxed text-ink-muted">
                Manage projects, media, reports and client access.
            </p>
        </div>

        @if (session('status'))
            <p role="status" class="mt-6 rounded-lg bg-portal/10 px-4 py-3 text-sm text-portal-ink">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('admin.login') }}" class="mt-6 flex flex-col gap-4">
            @csrf

            <div>
                <label for="identifier" class="mb-2 block text-[11px] font-bold tracking-[0.16em] text-portal-ink">
                    EMAIL OR USERNAME
                </label>
                <div class="relative">
                    <span aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-4 grid place-items-center text-ink-muted">
                        <x-icon name="user"/>
                    </span>
                    <input id="identifier" name="identifier" type="text" required autofocus autocomplete="username"
                           placeholder="Enter your email" value="{{ old('identifier') }}" class="portal-field pl-12"
                           @error('identifier') aria-invalid="true" aria-describedby="identifier-error" @enderror>
                </div>
                @error('identifier')<span id="identifier-error" class="field-error" role="alert">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="password" class="mb-2 block text-[11px] font-bold tracking-[0.16em] text-portal-ink">PASSWORD</label>
                <div class="relative">
                    <span aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-4 grid place-items-center text-ink-muted">
                        <x-icon name="lock"/>
                    </span>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                           placeholder="Enter your password" class="portal-field px-12">
                    <button type="button" data-password-toggle="password" aria-label="Show password" aria-pressed="false"
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
                <a href="{{ route('password.request') }}" class="text-sm text-portal hover:underline">Forgot password?</a>
            </div>

            <button type="submit"
                    class="group mt-1 flex w-full items-center justify-center gap-3 rounded-[10px] bg-portal px-6 py-3.5
                           text-[12px] font-bold tracking-[0.18em] text-white transition-colors hover:bg-portal-dark">
                SIGN IN
                <x-icon name="arrow-long-right" class="transition-transform duration-300 group-hover:translate-x-1"/>
            </button>
        </form>

        <p class="mt-6 flex items-center justify-center gap-2 border-t border-portal-ink/12 pt-5 text-[12px] text-ink-muted">
            <x-icon name="lock" size="14"/>
            Staff access only
        </p>

        <p class="mt-3 text-center text-[13px]">
            <a href="{{ route('login') }}" class="text-portal hover:underline">Client portal sign in</a>
        </p>
    </div>
@endsection
