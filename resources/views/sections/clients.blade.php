@php($clients = config('site.clients'))

{{--
    Figma: frame 1195:119, 1728×294, background #F9F9F9.
    The render shows the marks desaturated, so they are greyscaled in CSS and
    lift to full colour on hover.
--}}
<section class="bg-mist py-[clamp(2.5rem,4vw,68px)]">
    <div class="shell">
        <div class="reveal flex flex-col items-start gap-[clamp(1.5rem,3vw,52px)] lg:flex-row lg:items-center">
            <p class="shrink-0 text-fluid-label font-medium leading-snug text-teal">
                @foreach ($clients['label'] as $line)
                    <span class="block">{{ $line }}</span>
                @endforeach
            </p>

            <ul class="grid w-full grid-cols-3 items-center gap-x-[clamp(0.75rem,2vw,34px)] gap-y-8 sm:grid-cols-6">
                @foreach ($clients['logos'] as $logo)
                    <li class="flex items-center justify-center">
                        <img src="{{ asset($logo['src']) }}" alt="{{ $logo['name'] }}" loading="lazy" decoding="async"
                             class="h-auto max-h-[clamp(30px,2.9vw,50px)] w-auto max-w-[clamp(88px,7.9vw,137px)] object-contain grayscale transition duration-300 hover:grayscale-0">
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
