@php($hero = config('site.joinery_page.hero'))
@php($partner = config('site.joinery_page.partner'))
@php($logo = $partner['logo'] && file_exists(public_path($partner['logo'])) ? $partner['logo'] : null)

{{--
    The opening, arranged as the reference page opens: a light band carrying a
    centred serif slab over centred copy, and under it a half-and-half split of
    picture against a solid panel.

    THE LEFT HALF OF THE SPLIT IS THE ALWAN MARK, which is what was asked for —
    the first picture on the page is the partner's logo rather than a
    photograph. It is set on the light ground rather than the dark one because
    the artwork is drawn for white: the wordmark is black and the roundel runs
    orange into green, none of which survives being dropped on #161616.

    Until the file is there the half sets the name as type at the same size.
    The band is not empty and nothing renders as a broken frame; the moment
    public/images/partners/alwan-design.webp exists and the config points at
    it, the mark takes its place.
--}}
<section id="top" class="relative isolate flex min-h-[100svh] flex-col bg-mist">

    <x-site-header tone="dark"/>

    {{-- The title band. Centred, and the measure is held well inside the
         gutters — the reference sets its copy to about half the page, which is
         what keeps a centred paragraph from reading as a full-width block. --}}
    {{-- Anchored under the header rather than centred in what is left of the
         band, and that is the difference between a predictable gap and a
         drifting one: centred, the space above the slab was whatever the
         split below happened to leave — 65 at 1440, 52 at 1728 and 31 on a
         phone, tightening exactly as the type grew.

         The header is absolutely positioned, so this padding is what the
         slab clears it by rather than a gap under it — the same expression
         the contact page uses to sit its card clear of the same bar. Width
         AND height, because a wide shallow window is where a figure read off
         width alone pushes the block down the screen: 12.5vw of 1728 is 216
         against the 200 that 20vh gives at 1000 tall, and the smaller wins.
         The 6.5rem floor is the phone, where both terms are small. --}}
    <div class="flex flex-1 flex-col pt-[max(6.5rem,min(12.5vw,20vh))] pb-[clamp(3rem,7vh,7rem)]">
        <div class="shell flex flex-col items-center gap-[clamp(1rem,1.85vw,32px)] text-center">
            <h1 class="editorial-heading text-fluid-section uppercase text-ink">
                <span data-split data-split-delay="120">{{ $hero['heading'] }}</span>
            </h1>

            <p data-split data-split-delay="320" class="max-w-[24em] text-fluid-lead font-medium text-ink">{{ $hero['lead'] }}</p>

            <p class="reveal max-w-[46em] text-fluid-body font-medium text-ink-muted" style="transition-delay:200ms">{{ $hero['body'] }}</p>
        </div>
    </div>

    {{-- The split. Equal halves from lg, stacked below it, and both halves are
         given the same height so the panel is a block of colour against the
         mark rather than a caption under it. --}}
    <div class="grid lg:grid-cols-2">
        <div class="reveal flex min-h-[clamp(18rem,38vh,30rem)] items-center justify-center bg-white px-[clamp(1.5rem,4vw,80px)] py-[clamp(2.5rem,5vw,80px)]">
            @if ($logo)
                {{-- The descriptor is in the artwork itself, and the artwork
                     is a picture: without it in the alt, the one line that
                     says what this company does reaches a screen reader on
                     the type fallback and vanishes the moment the mark is
                     dropped in. --}}
                <img src="{{ \App\Support\Asset::versioned($logo) }}"
                     alt="{{ $partner['name'] }} — {{ $partner['descriptor'] }}"
                     fetchpriority="high" decoding="async"
                     class="w-full max-w-[clamp(16rem,32vw,520px)] object-contain">
            @else
                <div class="flex flex-col items-center gap-2 text-center">
                    <p class="display text-fluid-h2 leading-[1.1] text-ink">{{ $partner['name'] }}</p>
                    <p class="text-fluid-body font-semibold uppercase tracking-[0.08em] text-ink-muted">{{ $partner['descriptor'] }}</p>
                </div>
            @endif
        </div>

        <div class="reveal flex min-h-[clamp(18rem,38vh,30rem)] flex-col items-center justify-center gap-[clamp(1rem,1.62vw,28px)] bg-night px-[clamp(1.5rem,4vw,80px)] py-[clamp(2.5rem,5vw,80px)] text-center text-white"
             style="transition-delay:120ms">
            <h2 class="display text-fluid-h2 uppercase leading-[1.2]">{{ $hero['panel']['heading'] }}</h2>
            <p class="max-w-[30em] text-fluid-body font-medium text-white/70">{{ $hero['panel']['body'] }}</p>
        </div>
    </div>
</section>

{{--
    The overlay the header's MENU button opens, rendered by the hero that draws
    the button — the same pairing every other hero on this site makes.
    motion/menu.js binds to the first [data-menu] on the page and returns when
    there is none, and the button's aria-controls points at this element's id,
    so without it the control is inert and refers to nothing.
--}}
<x-site-menu/>
