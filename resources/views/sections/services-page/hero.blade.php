@php($hero = config('site.services_page.hero'))

{{--
    Figma 1545:3, 1728x1117. A photograph with one sentence broken across two
    lines that sit at different indents — "From vision" at 517 and "To spaces
    built to endure" at 102, both 108/148 Juana Alt in white. The offset is the
    whole composition: set flush they read as a heading, staggered they read as
    a thought finishing.

    Both indents go in as fractions of the frame so the stagger holds as the
    page narrows, and below lg they stack flush left, where a 30% indent would
    push the second line off the screen.
--}}
<section id="top" class="relative isolate flex h-[100svh] flex-col overflow-hidden bg-night">

    <img src="{{ \App\Support\Asset::versioned($hero['image']) }}" alt=""
         fetchpriority="high" decoding="async"
         class="absolute inset-0 -z-10 h-full w-full object-cover">

    <div aria-hidden="true" class="absolute inset-0 -z-10"
         style="background-image:linear-gradient(0deg,rgba(0,0,0,0.46) 0%,rgba(102,102,102,0) 117%)"></div>

    <x-site-header :login="false"/>

    <div class="relative z-10 mt-auto pb-[clamp(1rem,1.91vw,33px)] text-white">
        <div class="shell">
            <h1 class="font-display text-[clamp(2.25rem,6.25vw,108px)] font-medium leading-[1.37] tracking-normal">
                {{-- 517 of 1728, which is where the frame sets the first line. --}}
                <span data-split data-split-by="word" class="block lg:ml-[29.9%]">{{ $hero['lines'][0] }}</span>
                <span data-split data-split-by="word" data-split-delay="160" class="block lg:-ml-[1.3%]">{{ $hero['lines'][1] }}</span>
            </h1>
        </div>
    </div>
</section>

<x-site-menu/>
