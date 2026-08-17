@php($inquiry = config('site.inquiry'))

{{--
    Figma: frame 1226:955, 1728×980.
    A full-bleed interior photo with a white card (1466 wide, 40px radius,
    64px padding) floating over it: copy column on the left with the copyright
    pinned to its bottom, form on the right.

    The photo is sized by the section, so the section carries a viewport
    min-height: without it the section is only as tall as the card plus its
    padding and the photo reads as a band rather than a backdrop. min-h with
    centred content rather than a fixed height — a viewport shorter than the
    card (or a card grown by validation errors) pushes the section taller
    instead of overflowing it. svh, not vh, so mobile browser chrome does not
    make it overshoot.

    The card keeps its own max-width, so none of this changes its size.

    The form is a plain POST with CSRF and server-side validation. It works
    without JavaScript — old input and errors come back from the session.
--}}
<section id="contact"
         class="relative isolate flex min-h-[100svh] items-center border-t border-hairline px-[clamp(1rem,4.63vw,80px)] py-[clamp(2rem,3.4vw,58px)]">
    <img src="{{ asset($inquiry['background']) }}" alt="" loading="lazy" decoding="async"
         class="absolute inset-0 -z-10 h-full w-full object-cover">

    <div class="reveal mx-auto w-full max-w-[1466px] rounded-[clamp(20px,2.31vw,40px)] bg-white p-[clamp(1.25rem,2.78vw,48px)] shadow-[0_30px_80px_-40px_rgba(0,0,0,0.35)]">
        <div class="flex flex-col gap-[clamp(2rem,4vw,68px)] lg:flex-row">
            <div class="flex w-full flex-col justify-between gap-[clamp(1.5rem,3vw,52px)] lg:w-[548px] lg:shrink-0">
                <div class="flex flex-col gap-[clamp(0.875rem,1.5vw,26px)]">
                    <p class="text-fluid-label font-medium text-teal">{{ $inquiry['label'] }}</p>
                    <h2 class="display max-w-[444px] text-fluid-h2 leading-[1.3] text-ink">
                        @foreach ($inquiry['heading'] as $line)
                            <span class="inline sm:block">{{ $line }} </span>
                        @endforeach
                    </h2>
                    <p class="text-fluid-body font-medium text-ink-muted">{{ $inquiry['body'] }}</p>
                </div>

                <p class="text-fluid-body font-medium text-ink">{{ config('site.copyright') }}</p>
            </div>

            <x-enquiry-form/>
        </div>
    </div>
</section>
