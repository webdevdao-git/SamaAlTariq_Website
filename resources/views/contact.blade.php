@extends('layouts.app')

@section('title', 'Contact — ' . config('site.legal_name'))
@section('description', config('site.contact_page.body'))
@section('image', config('site.inquiry.background'))

{{--
    The contact page, drawn as the enquiry card the rest of the site closes
    with: a full-bleed interior photograph with the white card floating over
    it, copy on the left and the form on the right.

    Its own words, though — every string here comes from `contact_page` as it
    did before, and the page's three ways of reaching the company are the one
    thing the card has no slot for, so they go under the invitation where the
    card's own copy ends.

    The measurements are the card's, not this frame's: the same 1466 box, 40
    radius, 48 padding and 548 copy column, and the section carries the
    viewport height for the same reason the card's does — without it the photo
    reads as a band rather than a backdrop.

    Still no enquiry card at the foot. This page IS the enquiry, and closing it
    with the band that invites you to one would ask twice.
--}}
@section('content')
    @php($contact = config('site.contact_page'))

    <x-site-header tone="dark"/>

    <main>
        <section id="contact"
                 class="relative isolate flex min-h-[100svh] items-center px-[clamp(1rem,4.63vw,80px)] py-[clamp(6rem,9vw,150px)]">
            <img src="{{ \App\Support\Asset::versioned(config('site.inquiry.background')) }}" alt=""
                 fetchpriority="high" decoding="async"
                 class="absolute inset-0 -z-10 h-full w-full object-cover">

            {{-- 1340: wider than it was and still under the shared band's
                 1466, which is what leaves the photograph behind it reading as
                 a backdrop rather than a margin — this page is the card and
                 nothing else, so edge to edge would have nothing to sit on.

                 The height comes off the form rather than the copy: the rows
                 close from the component's 34 to 24 and the brief takes two
                 lines' box instead of three, which is 60-odd off the card
                 without touching a word or a field. --}}
            <div class="reveal mx-auto w-full max-w-[1340px] rounded-[clamp(20px,2.31vw,40px)] bg-white p-[clamp(1.25rem,2.2vw,38px)] shadow-[0_30px_80px_-40px_rgba(0,0,0,0.35)]">
                <div class="flex flex-col gap-[clamp(2rem,3.4vw,58px)] lg:flex-row">
                    <div class="flex w-full flex-col justify-between gap-[clamp(1.5rem,2.6vw,44px)] lg:w-[486px] lg:shrink-0">
                        <div class="flex flex-col gap-[clamp(0.875rem,1.5vw,26px)]">
                            <p class="text-fluid-label font-medium text-teal">{{ $contact['label'] }}</p>

                            {{-- The page's own h1, set as the card sets its
                                 heading: the display serif in the case the copy
                                 is written in, two lines from sm. --}}
                            <h1 class="display max-w-[444px] text-fluid-h2 leading-[1.3] text-ink">
                                @foreach ($contact['heading'] as $line)
                                    <span class="inline sm:block">{{ $line }} </span>
                                @endforeach
                            </h1>

                            <p class="text-fluid-body font-medium text-ink-muted">{{ $contact['body'] }}</p>

                            {{-- Telephone, email and the office. Each is its
                                 line over a rule, as the old page drew them,
                                 sized to the card: three rules and not four,
                                 because the first row needs nothing above it to
                                 separate it from the paragraph. --}}
                            <dl class="mt-[clamp(0.5rem,1vw,18px)] flex flex-col gap-[clamp(0.625rem,0.8vw,14px)]">
                                @foreach ($contact['details'] as $detail)
                                    <div class="-mb-px border-b border-black/[0.16] pb-[clamp(0.625rem,0.8vw,14px)]">
                                        <dt class="sr-only">{{ $detail['label'] }}</dt>
                                        <dd class="text-fluid-body font-medium text-ink">
                                            @if ($detail['href'])
                                                <a href="{{ $detail['href'] }}"
                                                   class="inline-block py-[11px] -my-[11px] transition-opacity hover:opacity-70">{{ $detail['value'] }}</a>
                                            @else
                                                {{ $detail['value'] }}
                                            @endif
                                        </dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>

                        <p class="text-fluid-body font-medium text-ink">{{ config('site.copyright') }}</p>
                    </div>

                    <x-enquiry-form gap="clamp(0.85rem,1.45vw,24px)"/>
                </div>
            </div>
        </section>
    </main>

    @include('sections.footer')

    <x-site-menu/>
@endsection
