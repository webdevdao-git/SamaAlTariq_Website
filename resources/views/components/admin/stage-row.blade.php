@props(['index', 'row' => [], 'statuses'])

{{--
    One editable stage: name, status, target date, and a control to drop it.

    `index` is the position in the `stages[]` payload rather than the stage's
    id, because it has to number blank rows the browser has just added as well
    as saved ones. The id rides along in a hidden field so the server can tell
    an edit from an insert; rows the browser adds have none, and rows the user
    removes never arrive, which is what marks them for deletion.

    Rendered twice — once per saved stage, once inside the <template> that the
    add button clones — so it takes its values from an array rather than a
    model, and `__INDEX__` stands in for the number in the template copy.
--}}

<div class="grid grid-cols-[1fr_auto] gap-2 sm:grid-cols-[minmax(0,1fr)_10rem_10rem_auto] sm:items-center"
     data-stage-row>
    @if (data_get($row, 'id'))
        <input type="hidden" name="stages[{{ $index }}][id]" value="{{ data_get($row, 'id') }}" data-stage-id>
    @endif

    <input name="stages[{{ $index }}][name]" value="{{ data_get($row, 'name') }}" maxlength="200"
           placeholder="Stage {{ is_numeric($index) ? $index + 1 : '' }} name"
           class="portal-field col-span-2 sm:col-span-1" data-stage-name
           aria-label="Stage name">

    <select name="stages[{{ $index }}][status]" class="portal-field" aria-label="Stage status">
        @foreach ($statuses as $status)
            <option value="{{ $status }}" @selected(data_get($row, 'status', 'Pending') === $status)>{{ $status }}</option>
        @endforeach
    </select>

    <input type="date" name="stages[{{ $index }}][target_date]" value="{{ data_get($row, 'target_date') }}"
           class="portal-field" aria-label="Stage target date">

    {{-- Removing the last row is allowed and leaves the add button on its own,
         which is the honest state for a project with no stages. --}}
    <button type="button" data-stage-remove
            class="justify-self-end rounded-[10px] p-2.5 text-ink-muted transition-colors hover:bg-red-50 hover:text-red-600"
            aria-label="Remove this stage">
        <x-icon name="trash" size="20"/>
    </button>
</div>
