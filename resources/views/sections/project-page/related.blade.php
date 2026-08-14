{{--
    Figma 1489:148, 1728x1864.

    The same 108/101 slab the projects page opens with, then four tiles in two
    even rows — 772 wide here against the frame's 770, because the frame sets
    this grid 4px narrower than every other block on the page and the column it
    sits in is the same 1568 as the rest.

    Covers are the projects page's own files, so a related tile shows the
    picture the gallery shows and nothing is exported twice. Those exports come
    from their own boxes, which are not this one, so cover crops them here —
    the only place on either page where it does anything.
--}}
<section class="bg-white pt-[clamp(3rem,5.79vw,100px)] pb-[clamp(3rem,5.79vw,100px)]">
    <div class="shell">
        <h2 class="font-display text-[clamp(2.25rem,6.25vw,108px)] font-medium uppercase leading-[0.936] tracking-normal text-ink">
            <span data-split class="block">Related</span>
            <span data-split data-split-delay="110" class="block">Projects</span>
        </h2>

        {{-- 90 from the heading to the first row, 24 between tiles. --}}
        <div class="mt-[clamp(2rem,5.21vw,90px)] grid gap-[clamp(1rem,1.389vw,24px)] sm:grid-cols-2">
            @foreach ($related as $i => $item)
                <a href="{{ route('projects.show', $item['slug']) }}"
                   class="reveal-rise group flex flex-col" style="transition-delay:{{ $i * 40 }}ms">
                    <div class="relative w-full overflow-hidden" style="aspect-ratio:772/635">
                        <img src="{{ \App\Support\Asset::versioned('images/projects/covers/' . $item['image'] . '.webp') }}"
                             alt="{{ $item['title'] }} — {{ $item['location'] }}"
                             loading="lazy" decoding="async"
                             class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.03]">
                    </div>

                    <div class="mt-[clamp(0.5rem,0.694vw,12px)] flex items-baseline justify-between gap-[clamp(0.5rem,0.347vw,6px)]">
                        <span class="text-[clamp(0.875rem,1.157vw,20px)] font-semibold leading-[1.35] text-ink">{{ $item['title'] }}</span>
                        <span class="shrink-0 text-[clamp(0.875rem,1.157vw,20px)] font-semibold leading-[1.35] text-ink-muted">{{ $item['category'] }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
