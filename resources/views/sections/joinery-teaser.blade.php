@php
    $partner = config('site.joinery_page.partner');
    $feature = config('site.alwan_feature');
@endphp

{{--
    Alwan Interiors, the joinery side of the business, shown through the
    hardest kind of piece it makes: a ribbed wall that curves up into the
    ceiling, the one in the About photograph.

    Told in 3D on one held screen (motion/rib-vault.js): a flat sheet of
    board, the CNC tracing every rib's profile across it, the cut pieces
    lifting off and standing up one after another into the curved vault,
    and then the photograph of the real one. Where WebGL can't run, the
    photograph is simply the picture.
--}}
<section id="joinery" class="rv bg-mist">
    <div class="shell rv__grid py-[clamp(3rem,5vw,80px)]">
        <div class="rv__copy">
            <div class="rv__brand">
                <img src="{{ asset($partner['logo']) }}" alt="{{ $partner['mark_alt'] }}" loading="lazy" decoding="async">
                <p>{{ $feature['label'] }}</p>
            </div>
            <h2 class="rv__heading display">
                @foreach ($feature['heading'] as $i => $line)
                    <span data-split data-split-delay="{{ $i * 110 }}" class="block {{ $i === 1 ? 'text-teal' : '' }}">{{ $line }}</span>
                @endforeach
            </h2>
            <p class="reveal rv__body">{{ $feature['body'] }}</p>
            <a href="{{ route('joinery') }}" class="reveal pill group self-start">
                Explore Joinery
                <x-icon name="arrow-pill" class="transition-transform duration-300 group-hover:translate-x-0.5"/>
            </a>
        </div>

        <div class="rv__visual" data-rvault>
            <canvas class="rv__canvas" aria-hidden="true"></canvas>

            <ol data-rv-phases class="rv__phases" aria-hidden="true">
                @foreach ($feature['phases'] as $i => $phase)
                    <li class="{{ $i === 0 ? 'is-on' : '' }}"><span>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>{{ $phase }}</li>
                @endforeach
            </ol>

            <figure data-rv-photo class="rv__photo">
                <img src="{{ \App\Support\Asset::versioned($feature['image']) }}" alt="{{ $feature['alt'] }}" loading="lazy" decoding="async">
                <figcaption>
                    <span>{{ $feature['caption'][0] }}</span>
                    <x-icon name="arrow-pill"/>
                    <span>{{ $feature['caption'][1] }}</span>
                </figcaption>
            </figure>
        </div>
    </div>
</section>
