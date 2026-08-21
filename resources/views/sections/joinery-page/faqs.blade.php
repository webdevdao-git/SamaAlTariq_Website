@php($faqs = config('site.joinery_page.faqs'))

{{--
    The question-and-answer stack, set as the reference sets it: each question
    centred and bold, the answer centred under it, and a short centred rule
    between one pair and the next.

    Written as <details>, not as paragraphs. The reference renders its pairs
    open, and open is how these read too — `open` is on every one of them — but
    the element is what makes each pair a control rather than a wall of text:
    it collapses on a phone under a thumb, it is what a screen reader announces
    as a disclosure, and it needs no JavaScript to do either. The marker is
    hidden because the design has no chevron.
--}}
<section class="bg-mist py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">

        <h2 class="reveal editorial-heading text-center text-fluid-h2 uppercase text-ink">Frequently Asked Questions</h2>

        <div class="mx-auto mt-[clamp(2rem,3.7vw,64px)] flex max-w-[52em] flex-col">
            @foreach ($faqs as $faq)
                <details open class="reveal group" style="transition-delay:{{ $loop->index * 90 }}ms">
                    <summary class="cursor-pointer list-none text-center text-fluid-lead font-semibold text-ink marker:hidden [&::-webkit-details-marker]:hidden">
                        {{ $faq['q'] }}
                    </summary>
                    <p class="mx-auto mt-[clamp(0.75rem,1.16vw,20px)] max-w-[46em] text-center text-fluid-body font-medium text-ink-muted">
                        {{ $faq['a'] }}
                    </p>
                </details>

                {{-- The short centred rule between pairs, and not after the
                     last one — there it would read as the start of something
                     the section does not have. --}}
                @unless ($loop->last)
                    <span aria-hidden="true" class="mx-auto my-[clamp(1.5rem,2.31vw,40px)] block h-px w-[clamp(8rem,11.5vw,200px)] bg-black/20"></span>
                @endunless
            @endforeach
        </div>
    </div>
</section>
