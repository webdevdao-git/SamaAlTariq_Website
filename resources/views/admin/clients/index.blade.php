@extends('layouts.portal')
@section('title', 'Clients')

@section('content')
    <h1 class="display text-4xl text-ink">Clients</h1>

    <form method="POST" action="{{ route('admin.clients.store') }}"
          class="mt-8 grid gap-4 rounded-xl bg-white p-6 sm:grid-cols-2">
        @csrf
        <h2 class="text-lg font-semibold text-ink sm:col-span-2">Add a client</h2>

        <div>
            <label for="name" class="mb-2 block text-sm text-ink">Name</label>
            <input id="name" name="name" required value="{{ old('name') }}" class="field">
            @error('name')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div>
            <label for="email" class="mb-2 block text-sm text-ink">Email</label>
            <input id="email" name="email" type="email" required value="{{ old('email') }}" class="field">
            @error('email')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div>
            <label for="username" class="mb-2 block text-sm text-ink">Username (optional)</label>
            <input id="username" name="username" value="{{ old('username') }}" class="field">
            @error('username')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div>
            <label for="phone" class="mb-2 block text-sm text-ink">Phone (optional)</label>
            <input id="phone" name="phone" value="{{ old('phone') }}" class="field">
        </div>

        <div>
            <label for="role" class="mb-2 block text-sm text-ink">Role</label>
            <select id="role" name="role" class="field">
                <option value="client">Client</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <label class="flex items-end gap-2 pb-3 text-sm text-ink">
            <input type="checkbox" name="can_download" value="1"> May download files
        </label>

        <div class="sm:col-span-2">
            <button type="submit" class="pill">Create client</button>
        </div>
    </form>

    <div class="mt-10 grid gap-2">
        @foreach ($clients as $client)
            <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl bg-white px-6 py-4">
                <div>
                    <p class="font-semibold text-ink">{{ $client->name }}</p>
                    <p class="text-sm text-ink-muted">
                        {{ $client->email }} · {{ $client->role }}
                        @if ($client->can_download) · downloads enabled @endif
                        @if ($client->must_change_password) · temporary password @endif
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('admin.clients.update', $client) }}">
                        @csrf @method('PUT')
                        <input type="hidden" name="reset_password" value="1">
                        <input type="hidden" name="can_download" value="{{ $client->can_download ? 1 : 0 }}">
                        <button type="submit" class="text-sm text-teal hover:underline">Reset password</button>
                    </form>

                    @can('delete', $client)
                        <form method="POST" action="{{ route('admin.clients.destroy', $client) }}"
                              onsubmit="return confirm('Remove {{ $client->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-sm text-[#c0392b] hover:underline">Remove</button>
                        </form>
                    @endcan
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">{{ $clients->links() }}</div>
@endsection
