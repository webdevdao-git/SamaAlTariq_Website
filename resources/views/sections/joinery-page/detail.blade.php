@php($band = config('site.joinery_page.detail'))

{{--
    Figma 1803:2, y4416–4808: three lines on the mist ground, each at its own
    indent, with a 218x106 picture set inside the second line before the words.

    The frame's own figures, as fractions of its 1728 width: the first line
    starts on the gutter; the second row starts at 843 (48.8%) with the
    picture, and "is often" follows at 1084; the third starts at 298 (17.2%).

    THE PICTURE IS SIZED IN EM AGAINST THE TYPE, not in pixels: the frame sets
    it 218x106 against 128px type, which is 1.7em wide and 0.83em tall. Read as
    a pixel box it would hold its size while the slab shrank around it — and
    read too small, as it was at 0.52em, it sits in the line like a stray mark
    rather than like a word.

    No copy under it. The frame carries none, and the two paragraphs that stood
    here before were written when the file could not be read.
--}}
<section class="bg-mist py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">
        <h2 class="reveal editorial-heading text-fluid-section uppercase text-ink">
            <span class="block">{{ $band['words'][0] }}</span>

            {{-- Picture then words. The box is set in em so it tracks the type:
                 a pixel width would hold its size while the slab shrank around
                 it. Below lg the line has no room for a picture inside it, so
                 it sits above the words instead and the indent is dropped. --}}
            <span class="flex flex-col gap-[0.15em] lg:ml-[48.8%] lg:flex-row lg:items-center lg:gap-[0.17em]">
                <span aria-hidden="true"
                      class="block aspect-[218/106] w-full max-w-[8rem] shrink-0 overflow-hidden bg-white lg:h-[0.83em] lg:w-[1.7em] lg:max-w-none">
                    <img src="{{ \App\Support\Asset::versioned($band['image']['src']) }}" alt="{{ $band['image']['alt'] }}"
                         loading="lazy" decoding="async"
                         class="h-full w-full object-cover">
                </span>
                <span class="block">{{ $band['words'][1] }}</span>
            </span>

            <span class="block lg:ml-[17.2%]">{{ $band['words'][2] }}</span>
        </h2>
    </div>
</section>
