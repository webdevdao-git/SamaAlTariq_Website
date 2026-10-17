@php($scope = config('site.scope'))

{{--
    Sectors We Serve — its own section, from the 2026 business profile.

    On a wide screen the section is one screen, pinned: the frame on the left,
    the six sectors on the right, and the scroll lights them one at a time
    while the frame turns to a project of that sector (scenes.js
    sectorsScene). On a phone each sector is a card with its own picture.
--}}
<section id="sectors" class="sectors bg-teal py-[clamp(3.5rem,6vw,100px)] text-white">
    <div class="shell grid gap-[clamp(2rem,4vw,72px)] lg:grid-cols-[5fr_7fr]">
        <div class="sectors__aside">
            <div class="sectors__sticky">
                <p class="reveal text-fluid-label font-medium text-teal">{{ $scope['sectors_label'] }}</p>
                <p class="reveal mt-4 max-w-[36ch] text-fluid-body text-white/70">{{ $scope['sectors_body'] }}</p>
                <div class="sectors__frame mt-[clamp(1.5rem,3vw,48px)]">
                    @foreach ($scope['sectors'] as $i => $sector)
                        @php($src = $scope['sector_images'][$sector] ?? null)
                        <figure class="sectors__pic {{ $i === 0 ? 'is-current' : '' }}" data-sector-pic="{{ $i }}">
                            @if ($src)
                                <img src="{{ \App\Support\Asset::versioned($src) }}" alt="" loading="lazy" decoding="async">
                            @else
                                <span class="sectors__plain display">{{ $sector }}</span>
                            @endif
                        </figure>
                    @endforeach
                </div>
            </div>
        </div>

        <ol class="sectors__list">
            @foreach ($scope['sectors'] as $i => $sector)
                @php($src = $scope['sector_images'][$sector] ?? null)
                <li class="sectors__item {{ $i === 0 ? 'is-current' : '' }}" data-sector="{{ $i }}">
                    {{-- The card's own picture on a phone, where there is no
                         frame beside the list to carry it. --}}
                    <span class="sectors__thumb" aria-hidden="true">
                        @if ($src)
                            <img src="{{ \App\Support\Asset::versioned($src) }}" alt="" loading="lazy" decoding="async">
                        @endif
                    </span>
                    <span class="sectors__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="sectors__name editorial-heading uppercase">{{ $sector }}</span>
                </li>
            @endforeach
        </ol>
    </div>
</section>
