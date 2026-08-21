@php($partner = config('site.joinery_page.partner'))
@php($logo = $partner['logo'] && file_exists(public_path($partner['logo'])) ? $partner['logo'] : null)

{{--
    The partner band. The page exists to say this, so it is said first and
    plainly.

    The logo is drawn only if the file is actually there. It was handed over in
    a message rather than as a file, so the path in the config points at
    somewhere it can be dropped — public/images/partners/alwan-design.webp —
    and until it is, the name is set as type. A picture that renders as a
    broken frame is worse than a name that renders as a name.

    The three facts under it — established, location, workshop — are null in
    the config because nothing verified them. They draw when they are filled
    in and the row disappears entirely while they are not, so the band never
    shows a label with nothing after it.
--}}
<section class="bg-white py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">
        {{-- 389 is the width the about page gives its picture, and the mark
             handed over is a wide lock-up rather than a square, so a fixed
             column carries it and the copy takes what is left. In fr both
             tracks would share the row by ratio and the copy would be a
             column 3 wide. --}}
        <div class="grid gap-[clamp(2rem,3.7vw,64px)] lg:grid-cols-[389px_1fr] lg:items-start lg:gap-[clamp(2.5rem,4.63vw,80px)]">

            {{-- The mark, boxed to the proportions of the artwork handed over
                 (2.8:1) and contained rather than stretched, so a wider or
                 narrower file dropped in later still sits correctly. --}}
            <div class="reveal flex flex-col gap-[clamp(0.75rem,1.16vw,20px)]">
                @if ($logo)
                    <img src="{{ \App\Support\Asset::versioned($logo) }}"
                         alt="{{ $partner['name'] }}"
                         loading="lazy" decoding="async"
                         class="w-full max-w-[389px] object-contain object-left">
                @else
                    <p class="display text-fluid-h2 leading-[1.1] text-ink">{{ $partner['name'] }}</p>
                @endif

                <p class="text-fluid-body font-semibold text-ink">{{ $partner['descriptor'] }}</p>

                @if ($partner['website'])
                    <a href="{{ $partner['website'] }}" target="_blank" rel="noreferrer noopener"
                       class="group inline-flex w-fit items-center gap-1.5 py-2 -my-2 text-fluid-sm font-medium text-teal transition-opacity hover:opacity-70">
                        {{ preg_replace('~^https?://~', '', $partner['website']) }}
                        <x-icon name="arrow-right" class="w-5 transition-transform duration-300 group-hover:translate-x-0.5"/>
                    </a>
                @endif
            </div>

            <div class="reveal flex flex-col gap-[clamp(1.25rem,2.31vw,40px)]" style="transition-delay:120ms">
                <h2 class="display max-w-[20em] text-fluid-h2 leading-[1.3] text-ink">
                    Every interior and joinery package on this site is delivered with {{ $partner['name'] }}.
                </h2>

                <p class="max-w-[46ch] text-fluid-lead font-medium text-ink-muted">
                    They make and fit the work; we hold the programme, the site and the client. One party is
                    responsible for the interior from the set-out drawings to the last fitted element, which is
                    what keeps a finish from being resolved on site between trades that were never drawn against
                    each other.
                </p>

                @php($facts = array_filter([
                    'Established' => $partner['established'],
                    'Location' => $partner['location'],
                    'Workshop' => $partner['workshop'],
                ]))

                @if ($facts)
                    <dl class="flex flex-wrap gap-x-[clamp(1.5rem,2.31vw,40px)] gap-y-4 border-t border-black/10 pt-[clamp(1.25rem,1.85vw,32px)]">
                        @foreach ($facts as $label => $value)
                            <div>
                                <dt class="text-fluid-label font-medium text-teal">{{ $label }}</dt>
                                <dd class="mt-1 text-fluid-body font-medium text-ink">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @endif
            </div>
        </div>
    </div>
</section>
