@props(['stages'])

@php
    /*
     * The stages sit on a shallow wave rather than a straight rule, as in the
     * previous portal. Node y-positions alternate high/low and the connecting
     * path is built from cubic segments through them, so it stays smooth for
     * any number of stages instead of only the five the design was drawn with.
     */
    $count = max($stages->count(), 1);
    $width = 1000;
    $inset = $count > 1 ? 60 : $width / 2;
    $step  = $count > 1 ? ($width - $inset * 2) / ($count - 1) : 0;
    $high  = 26;
    $low   = 74;

    $points = $stages->values()->map(fn ($stage, $i) => [
        'x' => $inset + $step * $i,
        'y' => $i % 2 === 0 ? $high : $low,
    ])->all();

    // Lead-in and lead-out so the wave runs off both edges.
    $path = 'M 0 '.($points[0]['y'] ?? $high).' L '.($points[0]['x'] ?? 0).' '.($points[0]['y'] ?? $high);
    for ($i = 1; $i < count($points); $i++) {
        [$px, $py] = [$points[$i - 1]['x'], $points[$i - 1]['y']];
        [$cx, $cy] = [$points[$i]['x'], $points[$i]['y']];
        $mid = ($px + $cx) / 2;
        $path .= " C {$mid} {$py}, {$mid} {$cy}, {$cx} {$cy}";
    }
    $last = end($points) ?: ['x' => 0, 'y' => $high];
    $path .= ' L '.$width.' '.$last['y'];

    // Everything up to and including the first non-complete stage is "reached".
    $currentIndex = $stages->search(fn ($s) => $s->status !== 'Completed');
    $currentIndex = $currentIndex === false ? $stages->count() - 1 : $currentIndex;
@endphp

<div class="overflow-x-auto">
    <div class="min-w-[720px]">
        <svg viewBox="0 0 {{ $width }} 100" preserveAspectRatio="none" class="h-[100px] w-full" aria-hidden="true">
            <path d="{{ $path }}" fill="none" stroke="rgba(31,58,68,0.12)" stroke-width="2"/>
            {{-- The teal overlay is the same path clipped to the reached portion. --}}
            <clipPath id="timeline-progress">
                <rect x="0" y="0" width="{{ $points[$currentIndex]['x'] ?? 0 }}" height="100"/>
            </clipPath>
            <path d="{{ $path }}" fill="none" stroke="var(--color-portal)" stroke-width="2" clip-path="url(#timeline-progress)"/>

            @foreach ($points as $i => $point)
                @php($stage = $stages[$i])
                @if ($stage->status === 'Completed')
                    <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="11" fill="var(--color-portal)"/>
                @elseif ($i === $currentIndex)
                    <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="15" fill="none" stroke="var(--color-portal)" stroke-width="2" opacity="0.4"/>
                    <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="9" fill="none" stroke="var(--color-portal)" stroke-width="2"/>
                    <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="4" fill="var(--color-portal)"/>
                @else
                    <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="10" fill="#fff" stroke="rgba(31,58,68,0.18)" stroke-width="2"/>
                @endif
            @endforeach
        </svg>

        {{--
            The labels are a plain list rather than SVG text: they need to wrap,
            and a screen reader should get an ordered set of stages, not a
            decorative graphic.
        --}}
        <ol class="grid gap-4" style="grid-template-columns: repeat({{ $count }}, minmax(0, 1fr));">
            @foreach ($stages as $i => $stage)
                @php($reached = $stage->status === 'Completed' || $i === $currentIndex)
                <li class="flex flex-col items-center text-center">
                    <span aria-hidden="true" class="mb-2 block h-6 w-px border-l border-dashed border-portal-ink/25"></span>
                    <span aria-hidden="true" class="mb-2 block size-1.5 rounded-full {{ $reached ? 'bg-portal' : 'bg-portal-ink/25' }}"></span>

                    <span class="text-[15px] leading-tight font-semibold text-portal-ink">{{ $stage->name }}</span>
                    <span class="mt-1 text-[13px] {{ $stage->status === 'Completed' ? 'text-portal' : ($i === $currentIndex ? 'text-portal' : 'text-ink-muted') }}">
                        {{ $stage->status === 'Pending' ? 'Upcoming' : $stage->status }}
                    </span>

                    @if ($stage->target_date)
                        <span class="mt-1.5 flex items-center gap-1.5 text-[13px] text-ink-muted">
                            <x-icon name="calendar" size="14"/>
                            {{ $stage->target_date->format('M j, Y') }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</div>
