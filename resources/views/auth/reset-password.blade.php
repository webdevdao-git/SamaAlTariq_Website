@extends('layouts.auth')
@section('title', 'Choose a new password')

@section('content')
    <div class="w-full max-w-[460px] rounded-[18px] bg-white p-[clamp(1.5rem,3vw,44px)] shadow-[0_24px_70px_-30px_rgba(31,58,68,0.35)]">
        <div class="flex flex-col items-center text-center">
            <img src="{{ asset('images/logo-mark-teal.png') }}" alt="" width="540" height="462" class="h-auto w-[54px]">
            <h1 class="mt-5 font-wordmark text-[clamp(1.35rem,2.1vw,28px)] tracking-[0.1em] text-portal-ink">NEW PASSWORD</h1>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="mt-6 flex flex-col gap-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="mb-2 block text-[11px] font-bold tracking-[0.16em] text-portal-ink">EMAIL ADDRESS</label>
                <input id="email" name="email" type="email" required autocomplete="email"
                       value="{{ old('email', $email) }}" class="portal-field"
                       @error('email') aria-invalid="true" @enderror>
                @error('email')<span class="field-error" role="alert">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="password" class="mb-2 block text-[11px] font-bold tracking-[0.16em] text-portal-ink">NEW PASSWORD</label>
                <div class="relative">
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                           class="portal-field pr-12" @error('password') aria-invalid="true" @enderror>
                    <button type="button" data-password-toggle="password" aria-label="Show password" aria-pressed="false"
                            class="absolute inset-y-0 right-2 grid w-10 place-items-center rounded-lg text-ink-muted transition-colors hover:text-portal-ink">
                        <x-icon name="eye" data-icon-show/>
                        <x-icon name="eye-off" data-icon-hide class="hidden"/>
                    </button>
                </div>
                <span class="mt-2 block text-xs text-ink-muted">At least 10 characters.</span>
                @error('password')<span class="field-error" role="alert">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-2 block text-[11px] font-bold tracking-[0.16em] text-portal-ink">CONFIRM PASSWORD</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       autocomplete="new-password" class="portal-field">
            </div>

            <button type="submit"
                    class="mt-1 w-full rounded-[10px] bg-portal px-6 py-3.5 text-[12px] font-bold tracking-[0.18em] text-white transition-colors hover:bg-portal-dark">
                UPDATE PASSWORD
            </button>
        </form>
    </div>
@endsection
