@php($profile = config('site.profile'))

{{--
    The company profile, offered from wherever the visitor happens to be.

    Fixed on the gutter rather than placed in a section, and on every page the
    marketing layout draws: it is the one thing a contractor's visitor is most
    likely to want to take away with them, and hunting for it in a footer is
    the reason the old site put it here too.

    Bottom LEFT from sm, where the page has empty gutter to spare. On a phone
    it swaps to the right: the footer stacks everything against the left edge
    there, and a button in that corner sat on top of "Back to top".

    A circle below sm and a pill from there: at phone width a labelled pill
    spans a third of the screen and sits over the copy it is offering. Both are
    a size down from what they were — 44 rather than 48 on the circle, and the
    pill's padding, mark and tracking each a step smaller — because this floats
    over the page rather than sitting in it, and it was reading as the loudest
    thing on the screen. 44 is the floor: below that it stops being a reliable
    tap target. The
    label is not hidden from assistive tech with it — the aria-label carries
    the whole sentence at every size, so what is announced does not change
    when the words come off the screen.

    `download` rather than a new tab: the file is nine megabytes, and a tab
    that spends ten seconds rendering a PDF viewer reads as a broken link.

    z-40, which is one band UNDER the navigation overlay rather than over it.
    The site the button is copied from sets its own at 90, above everything —
    here that would leave a white pill floating on top of the full-screen menu,
    which is the one moment a visitor is not looking for a download. The entry
    curtain is at 9999 and covers it either way.
--}}
<a href="{{ asset($profile['file']) }}" download
   aria-label="{{ $profile['aria'] }}"
   class="group fixed bottom-[calc(1.25rem+env(safe-area-inset-bottom))] right-5 z-40 sm:right-auto sm:left-5 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white text-teal shadow-[0_14px_30px_rgba(17,17,17,0.18)] transition-transform duration-300 hover:scale-105 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-teal sm:h-auto sm:w-auto sm:gap-2 sm:px-4 sm:py-2.5 md:bottom-7 md:left-7">
    <x-icon name="download" :size="14" class="transition-transform duration-300 group-hover:translate-y-0.5"/>
    <span class="hidden text-[10px] font-bold uppercase tracking-[0.14em] sm:inline">{{ $profile['label'] }}</span>
</a>
