@extends('layouts.admin')
@section('title', 'Add Clients')
@section('icon', 'user-plus')
@section('heading', 'Add Clients')
@section('subheading', 'Create client accounts and manage which projects each one can see.')

@section('content')
    {{-- CLIENT ACCOUNT --------------------------------------------------- --}}
    <section class="mt-6 rounded-2xl border border-portal-ink/10 bg-white p-[clamp(1.25rem,2.2vw,32px)]">
        <div class="flex items-center gap-3">
            <span class="text-portal-ink/60"><x-icon name="user-cog" size="24"/></span>
            <div>
                <h2 class="font-wordmark text-[17px] tracking-[0.08em] text-portal-ink">CLIENT ACCOUNT</h2>
                <p class="mt-0.5 text-[14px] text-ink-muted">Create client accounts and assign project access.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.clients.store') }}" class="mt-7">
            @csrf

            <div class="rounded-xl border border-portal-ink/12 p-[clamp(1rem,1.6vw,24px)]">
                <div class="flex items-start gap-4">
                    <span class="grid size-9 shrink-0 place-items-center rounded-full bg-portal/15 text-[14px] font-bold text-portal">1</span>
                    <div>
                        <h3 class="text-[15px] font-semibold tracking-[0.06em] text-portal">PROJECT ACCESS ASSIGNMENT</h3>
                        <p class="mt-0.5 text-[14px] text-ink-muted">Select the projects and set permission level for this client.</p>
                    </div>
                </div>

                <fieldset class="mt-6">
                    <legend class="mb-2 text-[14px] text-portal-ink">Select projects this client can view <span class="text-red-500">*</span></legend>
                    {{-- Checkboxes rather than the reference's dropdown: the
                         selection is multiple and needs to stay visible, and a
                         native list keeps it usable without JavaScript. --}}
                    <div class="grid gap-2 rounded-xl border border-portal-ink/12 p-4 sm:grid-cols-2 lg:grid-cols-3">
                        @forelse ($projects as $project)
                            <label class="flex items-center gap-2.5 text-[14px] text-portal-ink">
                                <input type="checkbox" name="projects[]" value="{{ $project->id }}"
                                       class="size-[17px] rounded-[4px] border-ink/25 text-portal focus:ring-portal">
                                <span class="truncate">{{ $project->title }}</span>
                            </label>
                        @empty
                            <p class="text-[14px] text-ink-muted">No projects yet.</p>
                        @endforelse
                    </div>
                </fieldset>

                <fieldset class="mt-6">
                    <legend class="mb-2 text-[14px] text-portal-ink">Permission Level <span class="text-red-500">*</span></legend>
                    <div class="grid gap-3 sm:grid-cols-2 lg:max-w-[620px]">
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-portal-ink/14 px-4 py-3.5 has-checked:border-portal has-checked:bg-portal/6">
                            <input type="radio" name="can_download" value="0" checked class="size-[17px] text-portal focus:ring-portal">
                            <x-icon name="eye" size="19" class="text-portal"/>
                            <span class="text-[15px] text-portal-ink">View Only</span>
                        </label>
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-portal-ink/14 px-4 py-3.5 has-checked:border-portal has-checked:bg-portal/6">
                            <input type="radio" name="can_download" value="1" class="size-[17px] text-portal focus:ring-portal">
                            <x-icon name="download" size="19" class="text-portal"/>
                            <span class="text-[15px] text-portal-ink">View &amp; Download</span>
                        </label>
                    </div>
                    <p class="mt-2 text-[13px] text-ink-muted">Client can view projects and media based on the selected permission.</p>
                </fieldset>
            </div>

            <div class="mt-5 rounded-xl border border-portal-ink/12 p-[clamp(1rem,1.6vw,24px)]">
                <div class="flex items-start gap-4">
                    <span class="grid size-9 shrink-0 place-items-center rounded-full bg-portal/15 text-[14px] font-bold text-portal">2</span>
                    <div>
                        <h3 class="text-[15px] font-semibold tracking-[0.06em] text-portal">CREATE CLIENT ACCOUNT</h3>
                        <p class="mt-0.5 text-[14px] text-ink-muted">Enter client details and set a temporary password.</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-5 lg:grid-cols-3">
                    <div>
                        <label for="client_name" class="mb-2 block text-[14px] text-portal-ink">Client Full Name <span class="text-red-500">*</span></label>
                        <input id="client_name" name="name" required placeholder="Enter full name" value="{{ old('name') }}" class="portal-field"
                               @error('name') aria-invalid="true" @enderror>
                        @error('name')<span class="field-error" role="alert">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="client_email" class="mb-2 block text-[14px] text-portal-ink">Email Address <span class="text-red-500">*</span></label>
                        <input id="client_email" name="email" type="email" required placeholder="Enter email address" value="{{ old('email') }}" class="portal-field"
                               @error('email') aria-invalid="true" @enderror>
                        @error('email')<span class="field-error" role="alert">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="client_phone" class="mb-2 block text-[14px] text-portal-ink">Phone Number</label>
                        <div class="flex gap-2">
                            <label for="dial_code" class="sr-only">Country dialling code</label>
                            <select id="dial_code" name="dial_code" class="portal-field !w-auto">
                                @foreach (['+971 🇦🇪', '+966 🇸🇦', '+974 🇶🇦', '+973 🇧🇭', '+968 🇴🇲', '+965 🇰🇼'] as $code)
                                    <option value="{{ Str::before($code, ' ') }}">{{ $code }}</option>
                                @endforeach
                            </select>
                            <input id="client_phone" name="phone" placeholder="Enter phone number" value="{{ old('phone') }}" class="portal-field">
                        </div>
                    </div>

                    <div>
                        <label for="client_password" class="mb-2 block text-[14px] text-portal-ink">Temporary Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input id="client_password" name="password" required placeholder="Generate password" class="portal-field pr-24"
                                   @error('password') aria-invalid="true" @enderror>
                            <button type="button" data-generate-password="client_password,client_password_confirmation"
                                    class="absolute inset-y-0 right-2 my-1.5 rounded-lg px-3 text-[13px] font-semibold text-portal hover:bg-portal/10">
                                Generate
                            </button>
                        </div>
                        <span class="mt-2 block text-xs text-ink-muted">At least 10 characters.</span>
                        @error('password')<span class="field-error" role="alert">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="client_password_confirmation" class="mb-2 block text-[14px] text-portal-ink">Confirm Password <span class="text-red-500">*</span></label>
                        <input id="client_password_confirmation" name="password_confirmation" required placeholder="Confirm password" class="portal-field">
                    </div>
                </div>

                <p class="mt-5 flex items-center gap-2 text-[14px] text-ink-muted">
                    <x-icon name="shield-check" size="18" class="text-portal"/>
                    Clients can view only the projects you assign.
                </p>

                <div class="mt-5 flex flex-wrap justify-end gap-3">
                    <button type="submit" class="flex items-center gap-2.5 rounded-[10px] bg-portal px-6 py-3 text-[14px] font-semibold text-white transition-colors hover:bg-portal-dark">
                        <x-icon name="user-cog" size="18"/> Create Client
                    </button>
                </div>
            </div>
        </form>
    </section>

    {{-- CLIENT ACCESS OVERVIEW ------------------------------------------- --}}
    <section class="mt-6 rounded-2xl border border-portal-ink/10 bg-white p-[clamp(1.25rem,2.2vw,32px)]">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-portal-ink/60"><x-icon name="user-cog" size="24"/></span>
                <div>
                    <h2 class="font-wordmark text-[17px] tracking-[0.08em] text-portal-ink">CLIENT ACCESS OVERVIEW</h2>
                    <p class="mt-0.5 text-[14px] text-ink-muted">View all client accounts and their project access.</p>
                </div>
            </div>
        </div>

        <div class="mt-7 overflow-x-auto">
            <table class="w-full min-w-[720px] text-left">
                <thead>
                    <tr class="text-[12px] font-bold tracking-[0.08em] text-ink-muted">
                        <th class="pb-4">CLIENT NAME</th>
                        <th class="pb-4">ASSIGNED PROJECTS</th>
                        <th class="pb-4">STATUS</th>
                        <th class="pb-4">DOWNLOADS</th>
                        <th class="pb-4 text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr class="border-t border-portal-ink/10">
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <span class="grid size-9 shrink-0 place-items-center rounded-full bg-portal/15 text-[13px] font-bold text-portal">
                                        {{ Str::upper(Str::substr($client->name, 0, 1)) }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate text-[15px] font-semibold text-portal-ink">{{ $client->name }}</p>
                                        <p class="truncate text-[13px] text-ink-muted">{{ $client->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 text-[14px] text-portal-ink">
                                {{ $client->projects->pluck('title')->implode(', ') ?: '—' }}
                            </td>
                            <td class="py-4">
                                <span class="inline-block rounded-md bg-emerald-50 px-2.5 py-1 text-[13px] font-semibold text-emerald-700">Active</span>
                            </td>
                            <td class="py-4 text-[14px] text-ink-muted">
                                {{ $client->can_download ? 'Allowed' : 'View only' }}
                            </td>
                            <td class="py-4">
                                <details class="relative text-right">
                                    <summary class="inline-grid size-9 cursor-pointer list-none place-items-center rounded-lg text-ink-muted hover:bg-alabaster hover:text-portal">
                                        <x-icon name="pencil" size="18"/>
                                    </summary>
                                    <form method="POST" action="{{ route('admin.clients.access', $client) }}"
                                          class="absolute right-0 z-20 mt-2 w-[300px] rounded-xl border border-portal-ink/12 bg-white p-4 text-left shadow-lg">
                                        @csrf
                                        @method('PUT')
                                        <p class="mb-3 text-[13px] font-semibold text-portal-ink">Projects this client can see</p>
                                        <div class="grid max-h-44 gap-2 overflow-y-auto">
                                            @foreach ($projects as $project)
                                                <label class="flex items-center gap-2.5 text-[14px] text-portal-ink">
                                                    <input type="checkbox" name="projects[]" value="{{ $project->id }}"
                                                           @checked($client->projects->contains($project->id))
                                                           class="size-[17px] rounded-[4px] border-ink/25 text-portal focus:ring-portal">
                                                    <span class="truncate">{{ $project->title }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <label class="mt-3 flex items-center gap-2.5 border-t border-portal-ink/10 pt-3 text-[14px] text-portal-ink">
                                            <input type="checkbox" name="can_download" value="1" @checked($client->can_download)
                                                   class="size-[17px] rounded-[4px] border-ink/25 text-portal focus:ring-portal">
                                            Allow downloads
                                        </label>
                                        <button type="submit" class="mt-4 w-full rounded-lg bg-portal px-4 py-2.5 text-[13px] font-semibold text-white hover:bg-portal-dark">
                                            Save Access Permissions
                                        </button>
                                    </form>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr class="border-t border-portal-ink/10">
                            <td colspan="5" class="py-10 text-center text-ink-muted">No client accounts yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="mt-5 text-[13px] text-ink-muted">
            Showing {{ $clients->count() }} of {{ $clients->count() }} {{ Str::plural('client', $clients->count()) }}
        </p>
    </section>
@endsection
