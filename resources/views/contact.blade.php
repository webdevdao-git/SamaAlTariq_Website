@extends('layouts.app')

@section('title', 'Contact — ' . config('site.legal_name'))
@section('description', config('site.contact_page.body'))

{{--
    Figma frame 1608:2082 ("Contact Us", 1728x2149). The page opens on type
    rather than a photograph: the title at 247 on the gutter, a hairline at
    529, and the two columns from 569 — 580 against 761, spread across the
    1568 column, which is a 227 gap rather than an even split.

    No hero image and no enquiry card at the foot — this page is the enquiry,
    so closing it with the card that invites you to one would ask twice.
--}}
@section('content')
    @php($contact = config('site.contact_page'))

    <x-site-header tone="dark" :login="false"/>

    <main class="bg-white pt-[clamp(7rem,14.294vw,247px)] pb-[clamp(3rem,5.79vw,100px)]">
        {{-- 79 left and 81 right: this frame pads 79 either side but fixes
             its content at 1568, so the spare two pixels fall right — the same
             arithmetic the projects and process frames use. --}}
        <div class="shell pl-[clamp(1.25rem,4.572vw,79px)] pr-[clamp(1.25rem,4.688vw,81px)]">
            {{-- 108/101 Juana Alt, uppercase, on the left gutter — the same
                 slab the projects page opens with. --}}
            <h1 class="font-display text-[clamp(2.5rem,6.25vw,108px)] font-medium uppercase leading-[0.936] tracking-normal text-ink">
                @foreach ($contact['heading'] as $i => $line)
                    <span data-split data-split-delay="{{ $i * 110 }}" class="block">{{ $line }}</span>
                @endforeach
            </h1>

            {{-- -mb-px: a LINE in Figma has no height, and the columns below
                 are measured from 529 rather than 530. --}}
            <span aria-hidden="true"
                  class="reveal-line -mb-px mt-[clamp(2.5rem,4.63vw,80px)] block h-px w-full bg-black/[0.24]"></span>

            {{-- Left: who to speak to, and at the foot of that column the three
                 ways of doing it. Right: the invitation and the form. --}}
            <div class="mt-[clamp(2rem,2.315vw,40px)] flex flex-col gap-[clamp(2.5rem,4.63vw,80px)] lg:flex-row lg:justify-between lg:gap-0">

                {{-- 580 of the frame's 1568 as a fraction rather than as
                     pixels: 580 and 761 together are 1341, which fits the
                     column at 1728 and overflows it at 1440. --}}
                <div class="flex flex-col justify-between gap-[clamp(2.5rem,10vw,172px)] lg:w-[36.99%] lg:shrink-0">
                    <p class="reveal text-[clamp(1.25rem,1.62vw,28px)] font-medium leading-[1.357] text-teal">{{ $contact['label'] }}</p>

                    {{-- Each row is its line and a rule beneath it — three
                         rules, not four: the frame draws none above the first.
                         40 to the rule, 16 to the next row. --}}
                    <dl class="flex flex-col gap-[clamp(0.75rem,0.926vw,16px)]">
                        @foreach ($contact['details'] as $i => $detail)
                            {{-- -mb-px so the rule costs nothing: the frame's
                                 line has no height and the rows sit 56 apart. --}}
                            <div class="reveal -mb-px border-b border-black/[0.24] pb-[clamp(0.75rem,0.926vw,16px)]"
                                 style="transition-delay:{{ $i * 90 }}ms">
                                <dt class="sr-only">{{ $detail['label'] }}</dt>
                                <dd class="text-[clamp(1rem,1.157vw,20px)] font-medium leading-[1.2] text-ink">
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

                {{-- 761 of the 1568, and 80 between the invitation and the
                     form. --}}
                <div class="flex flex-col gap-[clamp(2rem,4.63vw,80px)] lg:w-[48.53%] lg:shrink-0">
                    <p class="reveal text-[clamp(1.125rem,1.389vw,24px)] font-medium leading-[1.375] text-ink">{{ $contact['body'] }}</p>

                    {{-- The same form as the card at the foot of every other
                         page, given the width of a column rather than half a
                         card. One implementation, so the two cannot drift. --}}
                    <x-enquiry-form gap="clamp(1.5rem,3.24vw,56px)" class="flex flex-col gap-[var(--form-gap)]"/>
                </div>
            </div>
        </div>
    </main>

    @include('sections.footer')

    <x-site-menu/>
@endsection
