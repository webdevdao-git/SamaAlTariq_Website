@php($precision = config('site.precision'))

{{--
    Figma: frame 1226:1150, 1728×922, background #161616.
    A faint hairline grid, a tower photo centred at 64% opacity, PRECISION at
    128px pinned bottom-left, and a copy block in the right column.

    The three hairlines are the grid this section is built on, not texture laid
    over it, so they are declared once here and everything else is placed off
    them: the photo is centred between the two rails and stands on the shelf,
    the copy block starts at the right rail and ends on the shelf, and the word
    puts its baseline on the shelf.

    They used to be two systems. The rules were percentages of the section
    while the content sat on a twelve-column grid inside the shell, and the two
    agreed at no width — at 1728 the copy began 95px short of the right rail
    and the shelf ran through the middle of the word, 56px above its baseline
    and 66px below the foot of the photo. Three lines that all want to be one.

    0.05em is the word's descender allowance. Juana Alt has ascent 1.044 and
    descent 0.324, so at leading 0.82 the line box ends
    (0.82 - 1.044 + 0.324) / 2 below the baseline; dropping the box by that
    much puts the baseline itself on the shelf rather than the box edge.

    Below lg the rails and the shelf go back to being texture: there is not
    enough width to hang a column off a rail at 30%, so the copy and the word
    return to normal flow and stack.
--}}
<section class="relative isolate overflow-hidden bg-night"
         style="--rail-1:30.32%;--rail-2:71.53%;--shelf:85.3%">

    {{-- The shelf is the one line that stays full-bleed: it reads as a horizon
         rather than as a column edge, and it is what the eye uses to tie the
         photo, the copy and the word together. --}}
    <span aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-[var(--shelf)] h-px bg-white/10"></span>

    {{-- The rails live inside the shell rather than on the section so they
         share a coordinate space with the content they align. On a display
         wider than the 1728 frame the shell caps and centres, and rails drawn
         on the section would slide out from under the copy block. --}}
    <div class="shell relative flex min-h-[clamp(520px,53.36vw,922px)] flex-col justify-between gap-[clamp(5rem,12vw,14rem)] py-[clamp(2.5rem,4.63vw,80px)] lg:block lg:py-0">

        <span aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-[var(--rail-1)] w-px bg-white/10"></span>
        <span aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-[var(--rail-2)] w-px bg-white/10"></span>

        {{-- Below lg the framed photo is the wrong object: at 600px it is a
             rectangle in the middle of the column and the body copy reads
             straight across the concrete. It becomes a full-bleed backdrop
             instead, dim enough to sit under text, which also frees the copy
             and the word to stack without dodging it. --}}
        <div aria-hidden="true"
             class="pointer-events-none absolute inset-0 opacity-[0.16]
                    lg:inset-auto lg:bottom-[calc(100%_-_var(--shelf))] lg:left-[calc((var(--rail-1)_+_var(--rail-2))_/_2)] lg:h-[clamp(380px,42vw,720px)] lg:w-[clamp(180px,26.4vw,456px)] lg:-translate-x-1/2 lg:opacity-[0.64]">
            {{-- The word crosses the last ~115px of this photo. It stays
                 readable because that corner of the tower is in shadow, not
                 because anything protects it — check the overlap by eye if the
                 image is ever swapped. A scrim was tried and rejected: enough
                 of one to matter also dissolved the foot of the photo, which
                 is the edge that puts it on the shelf. --}}
            <img src="{{ asset($precision['image']) }}" alt="" loading="lazy" decoding="async"
                 class="h-full w-full object-cover">
        </div>

        {{-- The 24px inset off the rail is optical: text set hard against a
             hairline reads as touching it. --}}
        <div class="reveal z-10 flex flex-col gap-[clamp(0.875rem,1.16vw,20px)]
                    lg:absolute lg:bottom-[calc(100%_-_var(--shelf))] lg:left-[var(--rail-2)] lg:right-[var(--spacing-gutter)] lg:pl-[clamp(1rem,1.39vw,24px)]"
             style="transition-delay:120ms">
            <p class="max-w-[423px] text-fluid-lead font-bold text-white lg:max-w-none">{{ $precision['heading'] }}</p>
            <p class="max-w-[423px] text-fluid-lead text-white/60 lg:max-w-none">{{ $precision['body'] }}</p>
            <a href="{{ $precision['cta']['href'] }}" class="pill group w-fit">
                {{ $precision['cta']['label'] }}
                <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
            </a>
        </div>

        {{-- leading-[0.82] stays on the element: this one line sits tighter
             than the 0.88 the editorial tier uses elsewhere, per the Figma. --}}
        <h2 class="reveal editorial-heading z-10 text-fluid-mega leading-[0.82] uppercase text-white
                   lg:absolute lg:bottom-[calc(100%_-_var(--shelf)_-_0.05em)] lg:left-[var(--spacing-gutter)]">{{ $precision['word'] }}</h2>
    </div>
</section>
