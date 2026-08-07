@php($process = config('site.process'))

{{--
    Figma: frame 1226:731, 1728×1019.
    Two 752px columns: "OUR PROCESS" top-left with the numbered step pinned to
    the bottom of that column, and a full-height image on the right. Only step
    01 is drawn in the file; the rest live in config/site.php.
--}}
<section id="process" class="bg-white py-[clamp(3.5rem,5.79vw,100px)]" data-process>
    <div class="shell">
        <div class="grid gap-[clamp(2rem,3.7vw,64px)] lg:grid-cols-2">
            <div class="reveal flex flex-col justify-between gap-[clamp(2.5rem,6vw,104px)]">
                <h2 class="display text-fluid-section uppercase text-ink">
                    @foreach ($process['heading'] as $line)
                        <span class="block">{{ $line }}</span>
                    @endforeach
                </h2>

                <div>
                    <div class="mb-[clamp(1.5rem,2.3vw,40px)] flex gap-2">
                        @foreach ($process['steps'] as $i => $step)
                            <button type="button" data-process-tab="{{ $i }}"
                                    aria-label="Step {{ $step['number'] }}: {{ $step['title'] }}"
                                    @if ($i === 0) aria-current="true" @endif
                                    class="group relative h-[3px] flex-1 overflow-hidden rounded-full bg-black/10">
                                <span data-process-fill
                                      class="absolute inset-y-0 left-0 rounded-full bg-teal transition-[width] duration-500 {{ $i === 0 ? 'w-full' : 'w-0 group-hover:w-1/3' }}"></span>
                            </button>
                        @endforeach
                    </div>

                    <div>
                        <p data-process-number class="display text-[clamp(1.35rem,1.85vw,32px)] text-teal">{{ $process['steps'][0]['number'] }}</p>
                        <h3 data-process-title class="display mt-[clamp(0.75rem,1.16vw,20px)] text-[clamp(1.15rem,1.85vw,32px)] text-ink">{{ $process['steps'][0]['title'] }}</h3>
                        <p data-process-body class="mt-[clamp(0.5rem,0.7vw,12px)] max-w-[560px] text-fluid-body font-medium text-ink-muted">{{ $process['steps'][0]['body'] }}</p>
                    </div>
                </div>
            </div>

            <div class="reveal relative" style="transition-delay:120ms">
                <div class="relative aspect-[752/819] w-full overflow-hidden bg-mist">
                    @foreach ($process['steps'] as $i => $step)
                        <img src="{{ asset($step['image']) }}" alt="" loading="lazy" decoding="async"
                             data-process-image="{{ $i }}"
                             @if ($i !== 0) aria-hidden="true" @endif
                             class="absolute inset-0 h-full w-full object-cover transition-opacity duration-[900ms] ease-out {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}">
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@push('data')
    <script type="application/json" id="process-data">@json($process['steps'])</script>
@endpush
