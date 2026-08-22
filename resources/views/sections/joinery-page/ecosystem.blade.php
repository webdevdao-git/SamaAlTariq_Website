@php($band = config('site.joinery_page.ecosystem'))

{{--
    Teal label on the left gutter, a serif statement and a paragraph on the
    right — the frame's own two-track arrangement, with the label set small
    against a statement several times its size.

    The statement is the file's own line, one of the few legible in the
    screenshot of the frame; the paragraph under it is what this page already
    said about the partnership.
--}}
<section class="bg-white pb-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">
        <div class="grid gap-[clamp(1.5rem,3vw,52px)] lg:grid-cols-[222fr_1042fr] lg:gap-[clamp(2rem,4vw,70px)]">

            <p class="reveal text-fluid-sm font-medium leading-[1.4] text-teal">
                @foreach ($band['label'] as $line)
                    <span class="block">{{ $line }}</span>
                @endforeach
            </p>

            <div class="flex flex-col gap-[clamp(1.25rem,2.31vw,40px)]">
                {{-- The statement at the section-intro size the rest of the
                     site uses for a sentence that carries a band on its own. --}}
                <p class="reveal display max-w-[20em] text-fluid-h2 leading-[1.3] text-ink" style="transition-delay:80ms">{{ $band['statement'] }}</p>

                <p class="reveal max-w-[52ch] text-fluid-body font-medium text-ink-muted" style="transition-delay:160ms">{{ $band['body'] }}</p>
            </div>
        </div>
    </div>
</section>
