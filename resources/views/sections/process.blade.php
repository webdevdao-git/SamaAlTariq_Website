@php($process = config('site.process'))

{{--
    Our Process.

    Figma draws one step (01) as a static two-column frame. The interaction is
    modelled on concept-interiors-pearl.vercel.app: the text column sticks while
    the step images scroll past it, and the copy cross-fades between steps as
    each image reaches the text.

    Measured off the reference before building — the blocks do not snap between
    steps, their opacities blend continuously (mid-transition it read
    Design 0.47 / Build 0.53), so the fade is driven by scroll progress rather
    than by an active index.

    Progressive enhancement: the markup below is a plain, readable list of four
    steps beside four images. `initProcessScroll()` adds `is-stacked`, which is
    what collapses the steps onto each other for the cross-fade. Without
    JavaScript — or under prefers-reduced-motion — nothing stacks and all four
    steps simply read in order.
--}}
<section id="process" class="bg-white py-[clamp(3.5rem,5.79vw,100px)]" data-process>
    <div class="shell">
        <h2 class="reveal display text-fluid-section uppercase text-ink">
            @foreach ($process['heading'] as $line)
                <span class="block">{{ $line }}</span>
            @endforeach
        </h2>

        <div class="mt-[clamp(2rem,3.7vw,64px)] grid gap-[clamp(2rem,3.7vw,64px)] lg:grid-cols-2">

            {{-- Text column — sticky while the images travel past it --}}
            <div class="self-start lg:sticky lg:top-[18vh]">
                <div data-process-stack class="relative">
                    @foreach ($process['steps'] as $i => $step)
                        <div data-process-step="{{ $i }}"
                             class="process-step flex flex-col gap-[clamp(0.5rem,0.7vw,12px)]">
                            <p class="display text-[clamp(1.35rem,1.85vw,32px)] text-teal">{{ $step['number'] }}</p>
                            <h3 class="display text-[clamp(1.5rem,2.55vw,44px)] text-ink">{{ $step['title'] }}</h3>
                            <p class="max-w-[46ch] text-fluid-body font-medium text-ink-muted">{{ $step['body'] }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Progress rail --}}
                <ol class="mt-[clamp(1.5rem,2.55vw,44px)] flex items-center gap-[clamp(0.5rem,0.9vw,16px)]"
                    aria-hidden="true">
                    @foreach ($process['steps'] as $i => $step)
                        <li class="flex items-center gap-[clamp(0.5rem,0.9vw,16px)]">
                            <span data-process-rail-line="{{ $i }}"
                                  class="block h-px w-[clamp(20px,2.3vw,40px)] origin-left bg-ink/20 transition-colors duration-500"></span>
                            <span data-process-rail="{{ $i }}"
                                  class="text-[clamp(11px,0.81vw,14px)] font-medium tabular-nums text-ink/35 transition-colors duration-500">
                                {{ $step['number'] }}
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>

            {{-- Image column --}}
            <div class="flex flex-col gap-[6vh]">
                @foreach ($process['steps'] as $i => $step)
                    <figure data-process-image="{{ $i }}"
                            class="relative aspect-[752/819] max-h-[72vh] w-full overflow-hidden bg-mist">
                        <img src="{{ asset($step['image']) }}"
                             alt="{{ $step['number'] }} — {{ $step['title'] }}"
                             loading="{{ $i === 0 ? 'eager' : 'lazy' }}" decoding="async"
                             class="absolute inset-0 h-full w-full object-cover">
                    </figure>
                @endforeach
            </div>
        </div>
    </div>
</section>
