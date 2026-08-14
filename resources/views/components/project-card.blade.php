@props(['project'])

@php
    /*
     * The card opens the project. The slug is the cover's filename, which is
     * what the single project pages are keyed by — no second list to keep in
     * step — and a cover without a page simply renders as it always did.
     */
    $slug = pathinfo($project['image'], PATHINFO_FILENAME);
    $page = config()->has("site.project_pages.$slug") ? $slug : null;
@endphp

{{--
    The image is oversized inside a clipping frame and drifts within it, so the
    parallax never exposes an edge as the card crosses the viewport.

    The link sits on the picture and reaches the caption with an ::after over
    the whole figure, rather than wrapping both: a <figcaption> has to be a
    direct child of its <figure>, so it cannot live inside the anchor. One
    target the size of the card, one link in the accessibility tree — the same
    arrangement the projects page uses for its tiles.
--}}
<figure class="group relative flex h-full flex-col gap-3">
    <div class="relative w-full flex-1 overflow-hidden bg-white" style="aspect-ratio:{{ $project['ratio'] }}">
        <div data-parallax="{{ $project['drift'] }}" class="absolute inset-x-0 -inset-y-[8%]" style="will-change:transform">
            <img src="{{ \App\Support\Asset::versioned($project['image']) }}" alt="{{ $project['title'] }}" loading="lazy" decoding="async"
                 class="h-full w-full object-cover transition-transform duration-[900ms] ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-[1.04]">
        </div>

    </div>

    <figcaption class="flex items-center justify-between gap-4 text-fluid-body font-semibold">
        <span class="text-ink">{{ $project['title'] }}</span>
        <span class="shrink-0 text-ink-muted">{{ $project['category'] }}</span>
    </figcaption>

    {{-- Laid over the whole figure rather than wrapped around it, for the
         reason above. Last, so it is over the picture without the picture
         needing a z-index of its own. --}}
    @if ($page)
        <a href="{{ route('projects.show', $page) }}" aria-label="{{ $project['title'] }}"
           class="absolute inset-0 z-10 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-ink"></a>
    @endif
</figure>
