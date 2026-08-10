@extends('layouts.auth')
@section('title', 'Reset password')

@section('content')
    {{-- layouts.auth, not layouts.portal: the portal shell renders the signed-in
         user's name, so a guest reaching this page hit a fatal. --}}
    <div class="w-full max-w-[460px] rounded-[18px] bg-white p-[clamp(1.5rem,3vw,44px)] shadow-[0_24px_70px_-30px_rgba(31,58,68,0.35)]">
        <div class="flex flex-col items-center text-center">
            <img src="{{ asset('images/logo-mark-teal.png') }}" alt="" width="540" height="462" class="h-auto w-[54px]">
            <h1 class="mt-5 font-wordmark text-[clamp(1.5rem,2.3vw,31px)] tracking-[0.1em] text-portal-ink">RESET PASSWORD</h1>
            <p class="mt-3 max-w-[330px] text-[13.5px] leading-relaxed text-ink-muted">
                Enter the email address on your account and we'll send you a link to choose a new password.
            </p>
        </div>

        @if (session('status'))
            <p role="status" class="mt-6 rounded-lg bg-portal/10 px-4 py-3 text-sm text-portal-ink">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-6 flex flex-col gap-4">
            @csrf

            <div>
                <label for="email" class="mb-2 block text-[11px] font-bold tracking-[0.16em] text-portal-ink">EMAIL ADDRESS</label>
                <div class="relative">
                    <span aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-4 grid place-items-center text-ink-muted">
                        <x-icon name="user"/>
                    </span>
                    <input id="email" name="email" type="email" required autofocus autocomplete="email"
                           placeholder="Enter your email" value="{{ old('email') }}" class="portal-field pl-12"
                           @error('email') aria-invalid="true" @enderror>
                </div>
                @error('email')<span class="field-error" role="alert">{{ $message }}</span>@enderror
            </div>

            <button type="submit"
                    class="group flex w-full items-center justify-center gap-3 rounded-[10px] bg-portal px-6 py-3.5
                           text-[12px] font-bold tracking-[0.18em] text-white transition-colors hover:bg-portal-dark">
                SEND RESET LINK
                <x-icon name="arrow-long-right" class="transition-transform duration-300 group-hover:translate-x-1"/>
            </button>
        </form>

        <p class="mt-6 text-center text-sm">
            <a href="{{ route('login') }}" class="text-portal hover:underline">Back to sign in</a>
        </p>
    </div>
@endsection
