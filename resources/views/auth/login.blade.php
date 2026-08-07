@extends('layouts.portal')
@section('title', 'Sign in')

@section('content')
    <div class="mx-auto w-full max-w-[420px] rounded-2xl bg-white p-8 shadow-sm">
        <h1 class="display text-3xl text-ink">Client portal</h1>
        <p class="mt-2 text-sm text-ink-muted">Sign in to view your projects.</p>

        <form method="POST" action="{{ route('login') }}" class="mt-8 flex flex-col gap-6">
            @csrf

            <div>
                <label for="identifier" class="mb-2 block text-sm text-ink">Email or username</label>
                <input id="identifier" name="identifier" type="text" required autofocus
                       value="{{ old('identifier') }}" class="field"
                       @error('identifier') aria-invalid="true" @enderror>
                @error('identifier')<span class="field-error" role="alert">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm text-ink">Password</label>
                <input id="password" name="password" type="password" required autocomplete="current-password" class="field">
                @error('password')<span class="field-error" role="alert">{{ $message }}</span>@enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-ink-muted">
                <input type="checkbox" name="remember" value="1"> Remember me
            </label>

            <button type="submit" class="pill w-fit">Sign in</button>
        </form>
    </div>
@endsection
