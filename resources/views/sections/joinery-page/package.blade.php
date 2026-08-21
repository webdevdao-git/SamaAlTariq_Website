@php($package = config('site.joinery_page.package'))

{{--
    The card floating on a dark band — the strongest element on the reference
    page, and the one this section is built from: a full-width block of colour
    with a narrow light card centred on it, carrying a serif title, a small
    meta line, a bulleted list, a summary line, small print and one solid
    button.

    Their card closes on a price. This one closes on "quoted per project",
    because a joinery package is priced against its drawings — a figure here
    would be one nobody could stand behind, and the note says why rather than
    leaving the absence to read as an omission.

    The band is our night rather than their forest green, for the reason set
    out in the config: a ninth page in another site's colours reads as a
    different site.
--}}
<section class="bg-night px-[clamp(1rem,4.63vw,80px)] py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="reveal mx-auto w-full max-w-[560px] bg-mist p-[clamp(1.5rem,2.55vw,44px)] text-center">

        <h2 class="display text-[clamp(1.5rem,2.2vw,38px)] uppercase leading-[1.2] text-ink">{{ $package['title'] }}</h2>

        <p class="mt-[clamp(0.5rem,0.7vw,12px)] text-fluid-sm font-medium text-ink-muted">{{ $package['meta'] }}</p>

        {{-- Bulleted and left-set inside a centred card, as the reference has
             it: a list of seven items centred line by line would give the
             column a ragged edge on both sides and stop reading as a list.
             The block itself is centred in the card, so the card still reads
             as symmetrical. --}}
        <ul class="mx-auto mt-[clamp(1.25rem,1.85vw,32px)] flex w-fit list-disc flex-col gap-2 pl-5 text-left text-fluid-body font-medium text-ink marker:text-teal">
            @foreach ($package['items'] as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>

        <p class="mt-[clamp(1.25rem,1.85vw,32px)] text-fluid-body font-semibold uppercase tracking-[0.06em] text-ink">{{ $package['summary'] }}</p>

        <p class="mx-auto mt-[clamp(0.5rem,0.93vw,16px)] max-w-[38em] text-fluid-sm font-medium text-ink-muted">{{ $package['note'] }}</p>

        {{-- One button, solid and full width inside the card's padding — the
             reference's own treatment, and the only place on this page where
             a link is a filled block rather than a pill. --}}
        <a href="{{ $package['cta']['href'] }}"
           class="group mt-[clamp(1.5rem,2.31vw,40px)] flex w-full items-center justify-center gap-2 bg-night px-6 py-4 text-fluid-sm font-medium text-white transition-colors duration-300 hover:bg-teal">
            {{ $package['cta']['label'] }}
            <x-icon name="arrow-right" class="w-5 transition-transform duration-300 group-hover:translate-x-0.5"/>
        </a>
    </div>
</section>
