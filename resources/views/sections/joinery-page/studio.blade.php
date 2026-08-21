@php($studio = config('site.joinery_page.studio'))

{{--
    Picture beside copy, as the reference arranges its studio band: the
    photograph on the left at about half the page, the heading in title-case
    serif on the right with body copy under it and a link out.

    The picture is 1200 wide and sits in half a page rather than across one,
    which is the same rule the rest of this page follows: at 1728 that half is
    784, so the file is still ahead of the box it fills.
--}}
<section class="bg-white py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">
        <div class="grid items-center gap-[clamp(2rem,3.7vw,64px)] lg:grid-cols-2 lg:gap-[clamp(2.5rem,4.63vw,80px)]">

            <figure class="reveal relative aspect-[4/3] w-full overflow-hidden bg-mist">
                <img src="{{ \App\Support\Asset::versioned($studio['image']['src']) }}" alt="{{ $studio['image']['alt'] }}"
                     loading="lazy" decoding="async"
                     class="absolute inset-0 h-full w-full object-cover">
            </figure>

            <div class="reveal flex flex-col gap-[clamp(1rem,1.62vw,28px)]" style="transition-delay:120ms">
                <h2 class="display max-w-[14em] text-fluid-h2 leading-[1.25] text-ink">{{ $studio['heading'] }}</h2>

                @foreach ($studio['body'] as $paragraph)
                    <p class="max-w-[46ch] text-fluid-body font-medium text-ink-muted">{{ $paragraph }}</p>
                @endforeach

                <a href="{{ $studio['cta']['href'] }}" class="pill group w-fit">
                    {{ $studio['cta']['label'] }}
                    <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
                </a>
            </div>
        </div>
    </div>
</section>
