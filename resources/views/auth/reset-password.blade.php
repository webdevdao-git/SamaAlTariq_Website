@extends('layouts.portal')
@section('title', 'Choose a new password')

@section('content')
    <div class="mx-auto w-full max-w-[420px] rounded-2xl bg-white p-8 shadow-sm">
        <h1 class="display text-3xl text-ink">Choose a new password</h1>

        <form method="POST" action="{{ route('password.store') }}" class="mt-8 flex flex-col gap-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="mb-2 block text-sm text-ink">Email</label>
                <input id="email" name="email" type="email" required autocomplete="email"
                       value="{{ old('email', $email) }}" class="field"
                       @error('email') aria-invalid="true" @enderror>
                @error('email')<span class="field-error" role="alert">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm text-ink">New password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" class="field"
                       @error('password') aria-invalid="true" @enderror>
                <span class="mt-2 block text-xs text-ink-muted">At least 10 characters.</span>
                @error('password')<span class="field-error" role="alert">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-2 block text-sm text-ink">Confirm new password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       autocomplete="new-password" class="field">
            </div>

            <button type="submit" class="pill w-fit">Update password</button>
        </form>
    </div>
@endsection
