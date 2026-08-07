@props(['project'])

{{--
    The image is oversized inside a clipping frame and drifts within it, so the
    parallax never exposes an edge as the card crosses the viewport.
--}}
<figure class="group flex h-full flex-col gap-3">
    <div class="relative w-full flex-1 overflow-hidden bg-white" style="aspect-ratio:{{ $project['ratio'] }}">
        <div data-parallax="{{ $project['drift'] }}" class="absolute inset-x-0 -inset-y-[8%]" style="will-change:transform">
            <img src="{{ asset($project['image']) }}" alt="{{ $project['title'] }}" loading="lazy" decoding="async"
                 class="h-full w-full object-cover transition-transform duration-[900ms] ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-[1.04]">
        </div>
    </div>
    <figcaption class="flex items-center justify-between gap-4 text-fluid-body font-semibold">
        <span class="text-ink">{{ $project['title'] }}</span>
        <span class="shrink-0 text-ink-muted">{{ $project['category'] }}</span>
    </figcaption>
</figure>
