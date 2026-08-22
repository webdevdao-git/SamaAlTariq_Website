@php($band = config('site.joinery_page.process'))

{{--
    The five steps, staggered either side of a rule — the frame's own device:
    a vertical line down the middle with the steps alternating left and right
    of it, so the eye walks down the line rather than down a list.

    The stagger is `lg:` only. Below that the rule runs down the left and every
    step sits to the right of it: alternating on a phone leaves each step about
    140 wide, and a two-word title breaks across three lines in it.
--}}
<section class="bg-white py-[clamp(3.5rem,5.79vw,100px)]">
    <div class="shell">

        <div class="reveal flex flex-col gap-[clamp(0.75rem,1.16vw,20px)]">
            <p class="text-fluid-sm font-medium text-teal">{{ $band['label'] }}</p>
            <h2 class="display max-w-[16em] text-fluid-h2 leading-[1.3] text-ink">{{ $band['heading'] }}</h2>
        </div>

        {{-- The rule: one element behind the steps rather than a border on
             each, so it runs unbroken through the gaps between them. --}}
        <div class="relative mt-[clamp(2.5rem,4.63vw,80px)]">
            <span aria-hidden="true"
                  class="absolute inset-y-0 left-0 w-px bg-black/15 lg:left-1/2 lg:-translate-x-1/2"></span>

            <div class="flex flex-col gap-[clamp(2rem,3.7vw,64px)]">
                @foreach ($band['steps'] as $i => $step)
                    {{-- Odd steps left, even steps right. The dot sits on the
                         rule at the head of each step, which is what ties the
                         block to the line rather than leaving it floating
                         beside it. --}}
                    <div class="relative pl-[clamp(1.5rem,2.31vw,40px)] lg:w-1/2 lg:pl-0 {{ $i % 2 ? 'lg:self-end lg:pl-[clamp(2rem,3.24vw,56px)]' : 'lg:self-start lg:pr-[clamp(2rem,3.24vw,56px)] lg:text-right' }}"
                         style="transition-delay:{{ $i * 90 }}ms">
                        <span aria-hidden="true"
                              class="absolute top-[0.6em] left-0 size-[9px] -translate-x-1/2 rounded-full bg-teal {{ $i % 2 ? 'lg:left-0' : 'lg:left-auto lg:right-0 lg:translate-x-1/2' }}"></span>

                        <div class="reveal flex flex-col gap-[clamp(0.375rem,0.58vw,10px)]">
                            <h3 class="text-[clamp(1.0625rem,1.25vw,22px)] font-semibold text-teal">{{ $step['title'] }}</h3>
                            <p class="max-w-[38ch] text-fluid-sm font-medium text-ink-muted {{ $i % 2 ? '' : 'lg:ml-auto' }}">{{ $step['body'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
