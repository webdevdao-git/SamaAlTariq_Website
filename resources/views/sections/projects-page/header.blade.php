@php($page = config('site.projects_page'))

{{--
    The page's masthead, from Figma frame 1402:2 ("Projects"). Every figure
    here is read off that frame at its 1728 width and expressed as a fraction
    of it, so the proportions hold as the page narrows.

    There is no photographic hero here. The projects are the page, and putting
    a picture above them would make the visitor scroll past one project to
    reach the rest — so the type carries the top on its own.

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
    Frame 1443:970. The heading block starts at 247 — the header lock-up ends
    at 167 and the frame's outer stack sets 80 between blocks — and the block
    is 202 tall, being two lines of 108/101.1. The rule that closes it belongs
    to the group below rather than to this header, which is how the frame
    draws it: every group opens with its own line.
--}}
<header class="bg-white pt-[clamp(7rem,14.294vw,247px)] pb-[clamp(2.5rem,4.63vw,80px)]">
    {{-- 79 left and 81 right, which is what the frame actually draws: its
             padding is 79 either side but its content children are fixed at
             1568, so the two spare pixels fall on the right. Matching the
             padding alone would make every column and every picture a pixel
             larger than the frame's. Scoped to this page — the About frame
             gutters at 80, which is what the global shell carries. --}}
        <div class="shell pl-[clamp(1.25rem,4.572vw,79px)] pr-[clamp(1.25rem,4.688vw,81px)]">
        {{--
            Three items on one row, spread. The frame sets 456 between each,
            which is exactly what is left over — 1568 less the heading's 534,
            Gallery's 79 and List's 41, halved — so space-between reproduces
            it at 1728 and keeps reproducing it as the column narrows.

            The switch takes display:contents so its two labels sit in this
            row as siblings of the heading rather than as one block at the end.
            Any other arrangement puts Gallery immediately after the heading
            instead of two thirds across, which is where the frame has it.
        --}}
        <div class="flex flex-col gap-[clamp(1.5rem,2.31vw,40px)] md:flex-row md:items-end md:justify-between">
            <h1 class="text-[clamp(2.75rem,6.25vw,108px)] font-display font-medium uppercase leading-[0.936] tracking-normal text-ink">
                @foreach ($page['heading'] as $i => $line)
                    <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
                @endforeach
            </h1>

            <div class="flex items-center gap-[clamp(1.25rem,2.31vw,40px)] md:contents" role="radiogroup" aria-label="How projects are shown">
                @foreach ($page['views'] as $value => $label)
                    <label class="reveal cursor-pointer self-end" style="transition-delay:220ms">
                        <input type="radio" name="project-view" value="{{ $value }}" class="peer sr-only"
                               @checked($loop->first)>
                        {{-- The checked one is ink, the other the frame's same
                             ink at 60%. Colour only: the two labels must not
                             change width as they switch, or the row would
                             shuffle under the pointer. --}}
                        <span class="text-[clamp(1rem,1.389vw,24px)] font-medium leading-[1.375] text-ink-muted transition-colors duration-200 peer-checked:text-ink peer-focus-visible:underline peer-focus-visible:underline-offset-4">
                            {{ $label }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>
</header>
