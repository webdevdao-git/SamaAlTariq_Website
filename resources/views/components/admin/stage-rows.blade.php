@props(['stages' => null, 'statuses' => \App\Models\ProjectStage::STATUSES])

@php
    /*
     * What to show, in order of authority: whatever the user just submitted and
     * had bounced back by validation, then the saved stages, then a single
     * blank row so the field is never an empty space with a button under it.
     *
     * old() wins outright — a failed submission must come back exactly as it
     * was typed, including rows added or removed in the browser.
     */
    $saved = collect($stages ?? [])->map(fn ($stage) => [
        'id' => $stage->id,
        'name' => $stage->name,
        'status' => $stage->status,
        'target_date' => $stage->target_date?->toDateString(),
    ])->values()->all();

    $rows = array_values(old('stages', $saved)) ?: [['status' => 'Pending']];
@endphp

{{-- Stages are optional; the server drops rows left without a name, so an
     untouched blank row costs nothing. --}}
<fieldset class="mt-6" data-stage-list>
    <legend class="mb-2 text-[14px] text-portal-ink">Project Stages</legend>

    <div data-stage-rows class="grid gap-2">
        @foreach ($rows as $i => $row)
            <x-admin.stage-row :index="$i" :row="$row" :statuses="$statuses"/>
        @endforeach
    </div>

    {{-- The blank the add button clones. Inert until then: markup inside a
         <template> is not part of the form, so its fields never submit and
         its `__INDEX__` placeholder never reaches the server. --}}
    <template data-stage-template>
        <x-admin.stage-row index="__INDEX__" :row="['status' => 'Pending']" :statuses="$statuses"/>
    </template>

    <button type="button" data-stage-add
            class="mt-2 inline-flex items-center gap-1.5 rounded-[10px] border border-portal-ink/14 px-4 py-2.5 text-[14px] font-semibold text-portal transition-colors hover:border-portal hover:bg-portal/5">
        <span class="text-[17px] leading-none">+</span> Add stage
    </button>

    @error('stages')<span class="field-error mt-2 block" role="alert">{{ $message }}</span>@enderror
    @foreach ($errors->get('stages.*') as $message)
        <span class="field-error mt-1 block" role="alert">{{ $message[0] }}</span>
    @endforeach
</fieldset>
