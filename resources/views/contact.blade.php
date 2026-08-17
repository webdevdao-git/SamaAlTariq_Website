@extends('layouts.app')

@section('title', 'Contact — ' . config('site.legal_name'))
@section('description', config('site.contact_page.body'))

{{--
    Figma frame 1594:1608. The page opens on type rather than a photograph:
    the title on the gutter, a hairline under it, then the ways of reaching
    the office against the form.

    No hero image and no enquiry card at the foot — this page is the enquiry,
    so closing it with the card that invites you to one would ask twice.
--}}
@section('content')
    @php($contact = config('site.contact_page'))

    <x-site-header tone="dark" :login="false"/>

    <main class="bg-white pt-[clamp(7rem,14.294vw,247px)] pb-[clamp(3rem,5.79vw,100px)]">
        <div class="shell">
            {{-- 108/101 Juana Alt, uppercase, on the left gutter — the same
                 slab the projects page opens with. --}}
            <h1 class="font-display text-[clamp(2.5rem,6.25vw,108px)] font-medium uppercase leading-[0.936] tracking-normal text-ink">
                @foreach ($contact['heading'] as $i => $line)
                    <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
                @endforeach
            </h1>

            <span aria-hidden="true"
                  class="reveal-line mt-[clamp(2.5rem,4.63vw,80px)] block h-px w-full bg-black/[0.24]"></span>

            {{-- Left: who to speak to, and at the foot of that column the three
                 ways of doing it. Right: the invitation and the form. --}}
            <div class="mt-[clamp(2rem,2.315vw,40px)] flex flex-col gap-[clamp(2.5rem,4.63vw,80px)] lg:flex-row lg:gap-[clamp(2rem,7.35vw,127px)]">

                <div class="flex flex-col justify-between gap-[clamp(2.5rem,10vw,172px)] lg:w-[455px] lg:shrink-0">
                    <p class="reveal text-[clamp(1.125rem,1.389vw,24px)] font-medium leading-[1.375] text-teal">{{ $contact['label'] }}</p>

                    <dl class="flex flex-col">
                        @foreach ($contact['details'] as $i => $detail)
                            <div class="reveal border-t border-black/[0.24] py-[clamp(0.75rem,1.157vw,20px)] last:border-b"
                                 style="transition-delay:{{ $i * 90 }}ms">
                                <dt class="sr-only">{{ $detail['label'] }}</dt>
                                <dd class="text-[clamp(0.875rem,1.042vw,18px)] font-medium leading-[1.389] text-ink">
                                    @if ($detail['href'])
                                        <a href="{{ $detail['href'] }}" class="transition-opacity hover:opacity-70">{{ $detail['value'] }}</a>
                                    @else
                                        {{ $detail['value'] }}
                                    @endif
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                <div class="flex flex-1 flex-col gap-[clamp(2rem,3.7vw,64px)]">
                    <p class="reveal max-w-[860px] text-[clamp(1rem,1.157vw,20px)] font-medium leading-[1.5] text-ink">{{ $contact['body'] }}</p>

                    {{-- The same form as the card at the foot of every other
                         page, given the width of a column rather than half a
                         card. One implementation, so the two cannot drift. --}}
                    <x-enquiry-form class="flex flex-col gap-[clamp(1.15rem,2vw,34px)]"/>
                </div>
            </div>
        </div>
    </main>

    @include('sections.footer')

    <x-site-menu/>
@endsection
