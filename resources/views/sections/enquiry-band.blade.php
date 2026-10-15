@php
    $inquiry = config('site.inquiry');
    $contact = config('site.contact');
@endphp

{{--
    The short way in, for every page but the landing page and the contact
    page: the enquiry band's own heading and the profile's brief line, with a
    button to the contact page — where the full form lives — and the number
    to call. A full form at the foot of every page was more page than the
    visitor needed there.
--}}
<section id="contact" class="enquiry-band bg-alabaster py-[clamp(2.5rem,4.5vw,72px)]">
    <div class="shell flex flex-col gap-[clamp(1.25rem,2.5vw,40px)] md:flex-row md:items-end md:justify-between">
        <div class="max-w-[38rem]">
            <p class="reveal text-fluid-sm font-semibold uppercase tracking-[0.14em] text-teal">{{ $inquiry['label'] }}</p>
            <h2 class="mt-3 display text-fluid-h2 leading-[1.15] text-ink">
                @foreach ($inquiry['heading'] as $i => $line)
                    <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
                @endforeach
            </h2>
            @isset($inquiry['brief'])
                <p class="reveal mt-3 text-fluid-body text-ink-muted">{{ $inquiry['brief'] }}</p>
            @endisset
        </div>

        <div class="reveal flex flex-wrap items-center gap-x-6 gap-y-3">
            <a href="{{ route('contact') }}" class="pill">{{ $inquiry['submit'] }} <x-icon name="arrow-pill"/></a>
            @if (! empty($contact['phone']))
                <a href="tel:{{ preg_replace('/\s+/', '', $contact['phone']) }}" class="text-fluid-body font-semibold text-ink underline decoration-teal decoration-2 underline-offset-[6px]">{{ $contact['phone'] }}</a>
            @endif
        </div>
    </div>
</section>
