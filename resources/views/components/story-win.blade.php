@props(['key', 'eyebrow' => null, 'title' => []])

{{-- A window in <x-story>: the photograph shows through, with the chapter's title. --}}
<section class="story__win" data-story-win="{{ $key }}">
    <div class="shell">
        @if ($eyebrow)
            <p class="story__eyebrow reveal">{{ $eyebrow }}</p>
        @endif
        <h2 class="story__title display">
            @foreach ((array) $title as $i => $line)
                <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
            @endforeach
        </h2>
    </div>
</section>
