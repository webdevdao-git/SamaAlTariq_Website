@php($hero = config('site.services_page.hero'))

{{--
    Figma 1545:3, 1728x1117. A photograph with one sentence broken across two
    lines that sit at different indents — "From vision" at 517 and "To spaces
    built to endure" at 102, both 108/148 Juana Alt in white. The offset is the
    whole composition: set flush they read as a heading, staggered they read as
    a thought finishing.

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

        <x-site-header :login="false"/>

        <div class="relative z-10 mt-auto pb-[clamp(1rem,1.91vw,33px)]">
            <div class="shell">
                <div class="relative">
                    <h1 class="font-display text-[clamp(2.25rem,6.25vw,108px)] font-medium leading-[1.37] tracking-normal text-ink">
                        {{-- 517 of 1728, which is where the frame sets the first line. --}}
                        <span data-split data-split-by="word" class="block lg:ml-[29.9%]">{{ $hero['lines'][0] }}</span>
                        <span data-split data-split-by="word" data-split-delay="160" class="block lg:-ml-[1.3%]">{{ $hero['lines'][1] }}</span>
                    </h1>

                    {{-- The same words in white, brought up as the photograph
                         passes the type. Hidden from the reading order: there
                         is one title on this page, not two. --}}
                    <p data-reveal-title-light aria-hidden="true"
                       class="pointer-events-none absolute inset-0 font-display text-[clamp(2.25rem,6.25vw,108px)] font-medium leading-[1.37] tracking-normal text-white"
                       style="opacity:0">
                        <span class="block lg:ml-[29.9%]">{{ $hero['lines'][0] }}</span>
                        <span class="block lg:-ml-[1.3%]">{{ $hero['lines'][1] }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<x-site-menu/>
