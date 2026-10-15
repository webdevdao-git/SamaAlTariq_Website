@php
    /*
     * $items: the projects to stand in the stack, each with title, category,
     * location, src and href. Without it, every project once. $scroll: the
     * scroll drives the stack (pinned) rather than a timer.
     */
    $scroll = $scroll ?? false;
    $stack = $items ?? collect(config('site.projects_page.groups'))
        ->flatMap(fn ($group) => collect($group['rows'])
            ->flatMap(fn ($row) => collect($row['columns'])->flatMap(fn ($column) => $column['tiles'])))
        ->unique(fn ($item) => $item['project'] ?? $item['image'])
        ->map(function ($item) {
            $slug = $item['project'] ?? $item['image'];
            $hero = "images/projects/$slug/hero.webp";

            return [
                'title' => $item['title'],
                'category' => $item['category'],
                'location' => $item['location'] ?? null,
                'src' => \App\Support\Asset::versioned(file_exists(public_path($hero)) ? $hero : "images/projects/covers/$slug.webp"),
                'href' => route('projects.show', $slug),
            ];
        })
        ->values();
@endphp

{{--
    The pyramid stack, after 108.supply's "Pyramid Stack": the projects as
    tall cards in a pyramid — the one in front largest, two either side
    stepping back and down — and every few seconds the next slides into the
    middle as the front one steps aside (motion/project-stack.js).

    Each card is a link to its project. Without the script the cards sit in
    a row that scrolls sideways.
--}}
<div class="pstack bg-night text-white" data-pstack @if ($scroll) data-pstack-scroll @endif>
    <div class="pstack__stage">
        <ul class="pstack__cards">
            @foreach ($stack as $i => $item)
                <li class="pstack__card" data-index="{{ $i }}"
                    data-title="{{ $item['title'] }}" data-category="{{ $item['category'] }}" data-location="{{ $item['location'] }}">
                    <a href="{{ $item['href'] }}" aria-label="{{ $item['title'] }}">
                        <img src="{{ $item['src'] }}" alt="" loading="{{ $i < 5 ? 'eager' : 'lazy' }}" decoding="async">
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="pstack__bar shell" aria-hidden="true">
        <button type="button" class="pstack__nav" data-pstack-prev><x-icon name="arrow-right" class="w-5 rotate-180"/></button>
        <div class="pstack__caption">
            <p class="pstack__meta"><span data-pstack-category>{{ $stack[0]['category'] ?? '' }}</span> <span data-pstack-location class="text-white/55">{{ $stack[0]['location'] ?? '' }}</span></p>
            <p class="pstack__title display" data-pstack-title>{{ $stack[0]['title'] ?? '' }}</p>
        </div>
        <button type="button" class="pstack__nav" data-pstack-next><x-icon name="arrow-right" class="w-5"/></button>
    </div>
</div>
