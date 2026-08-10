@extends('layouts.admin')
@section('title', 'Profile Settings')
@section('icon', 'user-cog')
@section('heading', 'Profile Settings')
@section('subheading', 'Manage admin profile, client accounts, and access permissions.')

@php($me = auth()->user())

@section('content')
    {{-- ADMIN PROFILE ---------------------------------------------------- --}}
    <section class="rounded-2xl border border-portal-ink/10 bg-white p-[clamp(1.25rem,2.2vw,32px)]">
        <h2 class="font-wordmark text-[17px] tracking-[0.08em] text-portal-ink">ADMIN PROFILE</h2>

        <form method="POST" action="{{ route('admin.profile.update') }}" class="mt-6">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-4">
                <span class="grid size-16 place-items-center rounded-full bg-portal/15 text-[20px] font-bold text-portal">
                    {{ Str::upper(Str::substr($me->name, 0, 1)) }}
                </span>
                <div>
                    <p class="text-[19px] font-semibold text-portal-ink">{{ $me->name }}</p>
                    <p class="text-[14px] text-ink-muted">Admin</p>
                </div>
            </div>

            <div class="mt-7 grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="mb-2 block text-[14px] text-portal-ink">Full Name <span class="text-red-500">*</span></label>
                    <input id="name" name="name" required value="{{ old('name', $me->name) }}" class="portal-field"
                           @error('name') aria-invalid="true" @enderror>
                    @error('name')<span class="field-error" role="alert">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="email" class="mb-2 block text-[14px] text-portal-ink">Email Address</label>
                    <input id="email" name="email" type="email" required value="{{ old('email', $me->email) }}" class="portal-field"
                           @error('email') aria-invalid="true" @enderror>
                    @error('email')<span class="field-error" role="alert">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="phone" class="mb-2 block text-[14px] text-portal-ink">Phone Number</label>
                    <input id="phone" name="phone" value="{{ old('phone', $me->phone) }}" placeholder="+971 50 123 4567" class="portal-field">
                </div>
                <div>
                    <label for="job_title" class="mb-2 block text-[14px] text-portal-ink">Job Title</label>
                    <input id="job_title" name="job_title" value="{{ old('job_title', $me->job_title) }}" placeholder="Admin" class="portal-field">
                </div>
            </div>

            <div class="mt-7 grid items-end gap-5 border-t border-portal-ink/10 pt-7 lg:grid-cols-[190px_repeat(3,minmax(0,1fr))_auto]">
                <p class="flex items-center gap-3 text-[13px] font-bold tracking-[0.08em] text-portal-ink">
                    <x-icon name="lock" size="19" class="text-ink-muted"/> CHANGE PASSWORD
                </p>

                @foreach ([
                    ['current_password', 'Current Password', 'Enter current password'],
                    ['password', 'New Password', 'Enter new password'],
                    ['password_confirmation', 'Confirm New Password', 'Confirm new password'],
                ] as [$field, $label, $placeholder])
                    <div>
                        <label for="{{ $field }}" class="mb-2 block text-[13px] text-ink-muted">{{ $label }}</label>
                        <div class="relative">
                            <input id="{{ $field }}" name="{{ $field }}" type="password" placeholder="{{ $placeholder }}"
                                   autocomplete="{{ $field === 'current_password' ? 'current-password' : 'new-password' }}"
                                   class="portal-field pr-11" @error($field) aria-invalid="true" @enderror>
                            <button type="button" data-password-toggle="{{ $field }}" aria-label="Show password" aria-pressed="false"
                                    class="absolute inset-y-0 right-1 grid w-9 place-items-center rounded-lg text-ink-muted hover:text-portal-ink">
                                <x-icon name="eye" size="18" data-icon-show/>
                                <x-icon name="eye-off" size="18" data-icon-hide class="hidden"/>
                            </button>
                        </div>
                        @error($field)<span class="field-error" role="alert">{{ $message }}</span>@enderror
                    </div>
                @endforeach

                <button type="submit" class="rounded-[10px] bg-portal px-6 py-3 text-[14px] font-semibold text-white transition-colors hover:bg-portal-dark">
                    Save Profile
                </button>
            </div>
            <p class="mt-3 text-xs text-ink-muted">Leave the password fields empty to keep your current password.</p>
        </form>
    </section>
@endsection
