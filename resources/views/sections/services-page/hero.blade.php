@php($hero = config('site.services_page.hero'))

{{--
    Figma 1545:3, 1728x1117, and it draws two states at once rather than one
    picture: "From vision" at 517,399 and "To spaces built to endure" at
    102,936, both 108/148 Juana Alt.

    They are not two lines of one heading — they are the same sentence before
    and after. The reference does this plainly: two headings at one position,
    "FROM AN IDEA" in black over the drawing and "TO A MASTERPIECE" in white
    inside the window, so the picture opening swaps the words. A static frame
    can only show that by drawing both, which is what this one does; ours puts
    each where the frame puts it and hands over between them.

    THE PHOTOGRAPH IS DRAWN BEFORE IT IS TAKEN, which is for-living.it's hero:
    a drawing of the room underneath, and a window in the photograph over it
    that starts as a strip half the width and a tenth the height standing on
    the foot of the frame, opening to the whole of it as the page scrolls. The
    frame gives us one picture, so the drawing is made from it — greyscale
    dodged against a blurred negative of itself, which leaves ink where the
    tone turns and white everywhere else.

    The scene is two screens with one pinned, so what scrolls is the opening.
    Below lg the lines stack flush left, where a 30% indent would push the
    second off the screen.
--}}
<section id="top" data-reveal-scene class="relative h-[200svh] bg-white">
    <div class="sticky top-0 isolate flex h-[100svh] flex-col overflow-hidden bg-white">

        {{-- The drawing, underneath. --}}
        <img src="{{ \App\Support\Asset::versioned($hero['outline']) }}" alt=""
             aria-hidden="true" fetchpriority="high" decoding="async"
             class="absolute inset-0 -z-20 h-full w-full object-cover">

        {{-- The photograph, in a window that opens from the foot of the frame.
             The window scales and the picture inside it scales by the inverse,
             so the picture stands still while the window grows. --}}
        <div data-reveal-window
             class="absolute inset-0 -z-10 origin-bottom overflow-hidden will-change-transform">
            <img data-reveal-media src="{{ \App\Support\Asset::versioned($hero['image']) }}" alt=""
                 fetchpriority="high" decoding="async"
                 class="absolute inset-0 h-full w-full origin-bottom object-cover will-change-transform">
        </div>

        <div aria-hidden="true" class="absolute inset-0 -z-10"
             style="background-image:linear-gradient(0deg,rgba(0,0,0,0.46) 0%,rgba(102,102,102,0) 117%)"></div>

        {{-- The title again in white, inside a second window on the same
             transform as the photograph's — so a word turns white exactly as
             the picture reaches it rather than the whole title turning at once
             while the upper line is still over the pale drawing.

             Above the content and inert: the ink title beneath it is the one
             that is read, and this is hidden from assistive tech. --}}
        <div data-reveal-window aria-hidden="true"
             class="pointer-events-none absolute inset-0 z-20 origin-bottom overflow-hidden will-change-transform">
            <div data-reveal-media class="absolute inset-0 origin-bottom will-change-transform">
                <p data-reveal-title-white style="opacity:0"
                   class="absolute left-[5.9%] top-[83.8%] font-display text-[clamp(1.75rem,6.25vw,108px)] font-medium leading-[1.37] tracking-normal text-white">
                    {{ $hero['lines'][1] }}
                </p>
            </div>
        </div>

        <x-site-header :login="false"/>

        {{-- Both lines sit where the frame puts them, as fractions of it:
             517,399 and 102,936 of 1728x1117. --}}
        <div class="relative z-10 flex-1">
            <h1 class="absolute inset-0">
                <span data-reveal-title-ink
                      class="absolute left-[29.9%] top-[35.7%] font-display text-[clamp(1.75rem,6.25vw,108px)] font-medium leading-[1.37] tracking-normal text-ink">
                    <span data-split data-split-by="word">{{ $hero['lines'][0] }}</span>
                </span>

                {{-- Present for reading, drawn by the masked copy below: one
                     heading in the accessibility tree, two on the screen. --}}
                <span class="sr-only">{{ $hero['lines'][1] }}</span>
            </h1>
        </div>
    </div>
</section>

<x-site-menu/>
