@php($page = config('site.projects_page'))

{{--
    The page's masthead: the display heading on the gutter with the view
    switch opposite it, closed by a hairline.

    There is no photographic hero here. The projects are the page, and putting
    a picture above them would make the visitor scroll past one project to
    reach the rest — so the type carries the top on its own, set in the same
    editorial tier the landing page uses for OUR PROCESS and FEATURED PROJECTS.

    The switch is two radio inputs rather than buttons: it is a choice between
    two exclusive states, which is what radios are, and it means the keyboard
    and screen-reader behaviour comes from the platform rather than from
    JavaScript. motion/project-view.js only mirrors the checked one onto the
    page as a data attribute; with the script absent the gallery stays up and
    nothing is lost.
--}}
{{-- The bar is white-on-photo everywhere else; this page opens on white, so
     it takes the dark tone. Same bar, same box, ink instead of white. --}}
<x-site-header tone="dark"/>

{{--
    The masthead's vertical rhythm, on the site's own scale — 80 between
    blocks, 100 before a new one starts.

        page top .. 234   clears the header lock-up, which ends at 154, by 80
        heading block      the switch sits on its last baseline
        .. 80 ..           to the rule
        rule
        .. 100 ..          to the first group heading (in groups.blade.php)

    234 rather than 180: at 180 the heading's cap sat 26px under the lock-up
    and the two read as one crowded block. The gap below a masthead has to be
    at least the gap inside it or the page opens looking compressed.
--}}
<header class="bg-white pt-[clamp(7rem,13.54vw,234px)]">
    <div class="shell">
        <div class="flex flex-col gap-[clamp(1.5rem,2.31vw,40px)] pb-[clamp(2rem,4.63vw,80px)] md:flex-row md:items-end md:justify-between">
            <h1 class="editorial-heading text-fluid-section uppercase text-ink">
                @foreach ($page['heading'] as $i => $line)
                    <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
                @endforeach
            </h1>

            <div class="reveal flex shrink-0 items-center gap-[clamp(1.25rem,2.31vw,40px)]"
                 style="transition-delay:220ms" role="radiogroup" aria-label="How projects are shown">
                @foreach ($page['views'] as $value => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="project-view" value="{{ $value }}" class="peer sr-only"
                               @checked($loop->first)>
                        {{-- The checked one is ink, the other muted. Colour only:
                             the two labels must not change width as they switch,
                             or the row would shuffle under the pointer. --}}
                        <span class="text-fluid-body font-medium text-ink-muted transition-colors duration-200 peer-checked:text-ink peer-focus-visible:underline peer-focus-visible:underline-offset-4">
                            {{ $label }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Draws left to right rather than fading — the reference sets its
             rules to scaleX(0) and runs them out on an expo in-out. --}}
        <span aria-hidden="true" class="reveal-line block h-px w-full bg-black/10"></span>
    </div>
</header>
