@php($intro = config('site.services_page.intro'))

{{--
    Figma 1592:1510, 1728x596 with 100 above and below.

    Two rows. The first is the label against the statement — 179 and 818, 64
    apart, the statement set at 48/62 rather than the page's display size,
    because it is a sentence rather than a title. The second is a short note
    against the paragraph it introduces, 786 and 607 across the column, and the
    frame puts 100 between the two rows.
--}}
<section class="bg-white py-[clamp(3rem,5.79vw,100px)]">
    <div class="shell">
        <div class="flex flex-col gap-[clamp(1.5rem,3.7vw,64px)] lg:flex-row lg:items-start">
            <p class="reveal shrink-0 text-[clamp(1.25rem,1.62vw,28px)] font-medium leading-[1.357] text-teal">{{ $intro['label'] }}</p>

            <h2 class="reveal font-display text-[clamp(1.5rem,2.78vw,48px)] font-medium leading-[1.292] tracking-normal text-ink lg:max-w-[818px]"
                style="transition-delay:120ms">{{ $intro['statement'] }}</h2>
        </div>

        <div class="mt-[clamp(2.5rem,5.79vw,100px)] flex flex-col gap-[clamp(1.5rem,2.31vw,40px)] lg:flex-row lg:justify-between">
            <p class="reveal text-[clamp(1rem,1.157vw,20px)] font-semibold leading-[1.35] text-ink lg:w-[786px]">
                @foreach ($intro['note'] as $line)
                    <span class="block">{{ $line }}</span>
                @endforeach
            </p>

            <p class="reveal text-[clamp(1.125rem,1.389vw,24px)] font-medium leading-[1.375] text-ink lg:w-[607px] lg:shrink-0"
               style="transition-delay:140ms">{{ $intro['body'] }}</p>
        </div>
    </div>
</section>
