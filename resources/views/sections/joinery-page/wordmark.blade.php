@php($band = config('site.joinery_page.wordmark'))
@php($partner = config('site.joinery_page.partner'))
@php($logo = $partner['logo'] && file_exists(public_path($partner['logo'])) ? $partner['logo'] : null)

{{--
    The frame's second band: a small centred label, then the two words either
    side of a small landscape picture.

    THE LABEL IS THE ALWAN MARK RATHER THAN THE WORDS "ALWAN INTERIORS". The
    frame sets that line as type, but the mark was asked for on this page and
    the frame's own layout has no other slot for it — this is the line that
    names the company, so it is the line the logo belongs on. Without the file
    it falls back to the label as the frame sets it.

    The words are the display serif at the size the frame draws them, and the
    picture between them is small: 766 wide in a box of about 230, which is
    three source pixels per CSS pixel.
--}}
<section class="bg-white py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell flex flex-col items-center gap-[clamp(2rem,3.7vw,64px)]">

        <div class="reveal flex flex-col items-center">
            @if ($logo)
                <img src="{{ \App\Support\Asset::versioned($logo) }}"
                     alt="{{ $partner['mark_alt'] ?? $partner['name'] }}"
                     loading="lazy" decoding="async"
                     class="w-full max-w-[clamp(9rem,14vw,220px)] object-contain">
            @else
                <p class="text-fluid-sm font-semibold uppercase tracking-[0.12em] text-ink-muted">{{ $band['label'] }}</p>
            @endif
        </div>

        {{-- One row from sm: word, picture, word. Below it the picture goes
             between them still, but the row is a column and the words centre
             over it — at 390 the three across leave the picture 90 wide. --}}
        <div class="flex w-full flex-col items-center justify-center gap-[clamp(1.25rem,2.5vw,44px)] sm:flex-row">
            <p class="reveal display text-[clamp(1.75rem,3.2vw,56px)] leading-[1.1] text-ink">{{ $band['words'][0] }}</p>

            <figure class="reveal relative aspect-[4/3] w-full max-w-[clamp(11rem,16vw,280px)] shrink-0 overflow-hidden bg-mist"
                    style="transition-delay:100ms">
                <img src="{{ \App\Support\Asset::versioned($band['image']['src']) }}" alt="{{ $band['image']['alt'] }}"
                     loading="lazy" decoding="async"
                     class="absolute inset-0 h-full w-full object-cover">
            </figure>

            <p class="reveal display text-[clamp(1.75rem,3.2vw,56px)] leading-[1.1] text-ink" style="transition-delay:200ms">{{ $band['words'][1] }}</p>
        </div>
    </div>
</section>
