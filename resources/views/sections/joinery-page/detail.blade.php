@php($band = config('site.joinery_page.detail'))

{{--
    The slab with a picture set inside the line — the frame's own device: the
    heading runs across three lines and a small landscape photograph sits in
    the second, between the words rather than beside them.

    Held to lg. Below that the line is too narrow to give a picture room
    without breaking the sentence into fragments, so the photograph moves under
    the heading and the words close up.
--}}
<section class="bg-mist py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">

        <h2 class="reveal editorial-heading text-fluid-section uppercase text-ink">
            <span class="block">{{ $band['words'][0] }}</span>

            {{-- The middle line: picture, then the words that follow it. The
                 box is set in em so it tracks the type — a pixel width would
                 hold its size while the slab around it shrank. --}}
            <span class="flex flex-col gap-[0.2em] lg:flex-row lg:items-center lg:gap-[0.35em]">
                <span aria-hidden="true"
                      class="order-2 block aspect-[16/9] w-full max-w-[7.5rem] shrink-0 overflow-hidden bg-white lg:order-none lg:h-[0.78em] lg:w-[1.9em] lg:max-w-none">
                    <img src="{{ \App\Support\Asset::versioned($band['image']['src']) }}" alt=""
                         loading="lazy" decoding="async"
                         class="h-full w-full object-cover">
                </span>
                <span class="block">{{ $band['words'][1] }}</span>
            </span>

            <span class="block">{{ $band['words'][2] }}</span>
        </h2>

        <div class="mt-[clamp(2rem,3.7vw,64px)] grid gap-[clamp(1.5rem,3vw,52px)] md:grid-cols-2">
            @foreach ($band['body'] as $paragraph)
                <p class="reveal max-w-[52ch] text-fluid-body font-medium text-ink-muted" style="transition-delay:{{ $loop->index * 110 }}ms">{{ $paragraph }}</p>
            @endforeach
        </div>
    </div>
</section>
