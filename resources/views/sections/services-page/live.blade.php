@php($live = config('site.services_page.live'))

{{-- Working in occupied spaces: a tall photograph, then the four steps. --}}
<section class="live-works bg-mist py-[clamp(3rem,6vw,104px)]">
    <div class="shell grid items-center gap-[clamp(2rem,4vw,72px)] md:grid-cols-[0.8fr_1.2fr]">
        <div class="live-works__pic">
            <img data-drift src="{{ \App\Support\Asset::versioned($live['image']) }}" alt="Pullman hotel suite, finished by Sama Al Tariq"
                 loading="lazy" decoding="async" class="h-full w-full object-cover">
        </div>

        <div>
            <p class="reveal text-fluid-sm font-semibold uppercase tracking-[0.14em] text-teal">{{ $live['label'] }}</p>
            <h2 class="mt-3 display text-fluid-section leading-[1.05] text-ink">
                @foreach ($live['heading'] as $i => $line)
                    <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
                @endforeach
            </h2>
            <p class="reveal mt-4 max-w-[34rem] text-fluid-body text-ink-muted">{{ $live['body'] }}</p>

            <ol class="live-steps mt-[clamp(1.5rem,3vw,48px)]">
                @foreach ($live['steps'] as $i => $step)
                    <li class="reveal" style="transition-delay:{{ $i * 90 }}ms">
                        <span class="live-steps__num">{{ $i + 1 }}</span>
                        <h3 class="display text-fluid-h2 leading-[1.1] text-ink">{{ $step['title'] }}</h3>
                        <p class="mt-1.5 text-fluid-sm text-ink-muted">{{ $step['body'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>
