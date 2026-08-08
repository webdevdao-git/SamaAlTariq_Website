@extends('layouts.portal')
@section('title', 'Reset password')

@section('content')
    <div class="mx-auto w-full max-w-[420px] rounded-2xl bg-white p-8 shadow-sm">
        <h1 class="display text-3xl text-ink">Reset your password</h1>
        <p class="mt-2 text-sm text-ink-muted">
            Enter the email address on your account and we'll send you a link to choose a new password.
        </p>

        <form method="POST" action="{{ route('password.email') }}" class="mt-8 flex flex-col gap-6">
            @csrf

            <div>
                <label for="email" class="mb-2 block text-sm text-ink">Email</label>
                <input id="email" name="email" type="email" required autofocus autocomplete="email"
                       value="{{ old('email') }}" class="field"
                       @error('email') aria-invalid="true" @enderror>
                @error('email')<span class="field-error" role="alert">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="pill w-fit">Send reset link</button>
        </form>

        <p class="mt-6 text-sm">
            <a href="{{ route('login') }}" class="text-teal hover:underline">Back to sign in</a>
        </p>
    </div>
@endsection
