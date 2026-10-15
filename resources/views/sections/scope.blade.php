@php($scope = config('site.scope'))

{{--
    We Do Everything Your Project Needs, from the 2026 business profile. The
    sectors it serves have a section of their own (sections/sectors).

    The six scopes stand as a numbered list of large rows; the sectors as a
    line of large words that ink in one after another as the section crosses
    the screen (scenes.js scopeScene). As plain markup both are readable lists.
--}}
<section id="scope" class="scope bg-white py-[clamp(3rem,5vw,80px)]">
    <div class="shell">
        <div class="grid gap-[clamp(1.5rem,3vw,52px)] lg:grid-cols-[1fr_auto] lg:items-end">
            <h2 class="editorial-heading text-fluid-h2 uppercase text-ink">
                <span data-split class="block">{{ $scope['heading'][0] }}</span>
                <span data-split data-split-delay="120" class="block text-teal">{{ $scope['heading'][1] }}</span>
            </h2>
            <p class="reveal max-w-[30ch] text-fluid-lead font-medium text-ink-muted">{{ $scope['body'] }}</p>
        </div>

        <ol class="scope__list mt-[clamp(1.75rem,3vw,48px)]">
            @foreach ($scope['items'] as $i => $item)
                <li class="scope__row">
                    <span aria-hidden="true" class="scope__fill"></span>
                    <span class="scope__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="scope__name display">{{ $item }}</span>
                    <span aria-hidden="true" class="scope__arrow"><x-icon name="arrow-right" class="w-full"/></span>
                </li>
            @endforeach
        </ol>

    </div>
</section>
