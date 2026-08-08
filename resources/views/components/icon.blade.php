@props(['name', 'size' => null])

{{--
    Icons transcribed verbatim from the SVGs exported out of the Figma file, so
    the geometry matches the design exactly. Each keeps its own viewBox and its
    designed pixel size; `currentColor` replaces the baked-in fill/stroke so one
    icon serves both light and dark sections.

    Rotations are baked into a <g transform> rather than applied as a CSS class,
    matching how Figma composed them (e.g. mdi-light:arrow-up rotated 90°).
--}}

@switch($name)

    {{-- hugeicons:menu-02 — 28×28 --}}
    @case('menu')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 28 }}" height="{{ $size ?? 28 }}"
             viewBox="0 0 28 28" fill="none" aria-hidden="true">
            <path d="M4.66667 5.83333H18.6667M4.66667 14H23.3333M4.66667 22.1667H14"
                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break

    {{-- mdi-light:arrow-up rotated 90° — drawn already pointing right. 28×28 --}}
    @case('arrow-right')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 28 }}" height="{{ $size ?? 28 }}"
             viewBox="0 0 28 28" fill="none" aria-hidden="true">
            <g transform="rotate(90 14 14)">
                <path d="M12.8333 23.3333V9.04167L6.70833 15.1667L5.83333 14.3967L13.4167 6.81333L21 14.3967L20.125 15.1667L14 9.04167V23.3333H12.8333Z"
                      fill="currentColor"/>
            </g>
        </svg>
        @break

    {{-- mdi-light:arrow-up rotated -45° — the outbound link arrow. 24×24 --}}
    @case('arrow-outward')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 24 }}" height="{{ $size ?? 24 }}"
             viewBox="0 0 28 28" fill="none" aria-hidden="true">
            <g transform="rotate(45 14 14)">
                <path d="M12.8333 23.3333V9.04167L6.70833 15.1667L5.83333 14.3967L13.4167 6.81333L21 14.3967L20.125 15.1667L14 9.04167V23.3333H12.8333Z"
                      fill="currentColor"/>
            </g>
        </svg>
        @break

    {{-- fluent:arrow-up-20-regular rotated 90° — the arrow inside pill buttons. 16×16 --}}
    @case('arrow-pill')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 16 }}" height="{{ $size ?? 16 }}"
             viewBox="0 0 16 16" fill="none" aria-hidden="true">
            <g transform="rotate(90 8 8)">
                <path d="M2.5064 7.32806C2.47095 7.36683 2.44347 7.4122 2.42555 7.46158C2.40763 7.51097 2.39961 7.5634 2.40195 7.61588C2.40429 7.66836 2.41694 7.71987 2.43919 7.76747C2.46144 7.81506 2.49284 7.8578 2.5316 7.89326C2.6099 7.96487 2.71343 8.00244 2.81942 7.99772C2.87191 7.99538 2.92342 7.98272 2.97101 7.96048C3.0186 7.93823 3.06135 7.90683 3.0968 7.86806L7.6016 2.93366V13.9985C7.6016 14.1045 7.64375 14.2063 7.71876 14.2813C7.79378 14.3563 7.89552 14.3985 8.0016 14.3985C8.10769 14.3985 8.20943 14.3563 8.28445 14.2813C8.35946 14.2063 8.4016 14.1045 8.4016 13.9985V2.93606L12.904 7.86806C12.9394 7.90688 12.9821 7.93835 13.0297 7.96066C13.0772 7.98298 13.1287 7.9957 13.1812 7.99812C13.2337 8.00053 13.2861 7.99259 13.3356 7.97473C13.385 7.95688 13.4304 7.92947 13.4692 7.89406C13.508 7.85866 13.5395 7.81595 13.5618 7.76839C13.5841 7.72082 13.5968 7.66933 13.5993 7.61685C13.6017 7.56436 13.5937 7.51192 13.5759 7.4625C13.558 7.41309 13.5306 7.36768 13.4952 7.32886L8.444 1.79606C8.38064 1.72671 8.30188 1.67321 8.21406 1.63986C8.12625 1.60652 8.03183 1.59425 7.9384 1.60406C7.79224 1.61927 7.65674 1.68759 7.5576 1.79606L2.5064 7.32806Z"
                      fill="currentColor"/>
            </g>
        </svg>
        @break

    {{-- iconamoon:arrow-up-2-light flipped vertically — the select chevron --}}
    @case('chevron-down')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 22 }}" height="{{ $size ?? 22 }}"
             viewBox="0 0 22.2366 22.2366" fill="none" aria-hidden="true">
            <g transform="scale(1 -1) translate(0 -22.2366)">
                <path d="M15.754 12.9726L11.1202 8.3388L6.48636 12.9726" stroke="currentColor"
                      stroke-width="1.38977" stroke-linecap="round" stroke-linejoin="round"/>
            </g>
        </svg>
        @break

    {{-- subway:up-arrow rotated 42.95° — the large arrow on the footer card --}}
    @case('diagonal-arrow')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 51 }}" height="{{ $size ?? 51 }}"
             viewBox="0 0 51 51" fill="none" aria-hidden="true">
            <g transform="rotate(42.95 25.5 25.5)">
                <path d="M24.6533 0L3.40664 21.2467V33.9967L20.4 17.0033V51H28.9066V17.0033L45.9 33.9967V21.2467L24.6533 0Z"
                      fill="currentColor"/>
            </g>
        </svg>
        @break

    {{-- "Vector 1" — the small mark beside "Recently Completed" --}}
    @case('dot')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 7 }}" height="{{ ($size ?? 7) * 7.125 / 7 }}"
             viewBox="0 0 7 7.125" fill="none" aria-hidden="true">
            <path d="M1.0625 3.125H0V7.125H1.0625L4.875 6.8125L7 7.125V3.125L4.875 0L1.0625 3.125Z" fill="currentColor"/>
        </svg>
        @break

    {{-- Social marks. Drawn at 24×24 on `currentColor` so they inherit the
         surrounding text colour and its hover transition. --}}
    @case('instagram')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 24 }}" height="{{ $size ?? 24 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <rect x="2.5" y="2.5" width="19" height="19" rx="5.5" stroke="currentColor" stroke-width="1.6"/>
            <circle cx="12" cy="12" r="4.2" stroke="currentColor" stroke-width="1.6"/>
            <circle cx="17.4" cy="6.6" r="1.25" fill="currentColor"/>
        </svg>
        @break

    @case('facebook')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 24 }}" height="{{ $size ?? 24 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M13.5 21.5V13H16.2L16.7 9.8H13.5V7.8C13.5 6.9 13.8 6.3 15.1 6.3H16.8V3.4C16.2 3.3 15.3 3.2 14.3 3.2C12.1 3.2 10.5 4.6 10.5 7.2V9.8H7.8V13H10.5V21.5H13.5Z"
                  fill="currentColor"/>
        </svg>
        @break

    @case('linkedin')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 24 }}" height="{{ $size ?? 24 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <rect x="2.6" y="2.6" width="18.8" height="18.8" rx="3" stroke="currentColor" stroke-width="1.6"/>
            <path d="M7.2 10.2V17M7.2 7.3V7.31" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
            <path d="M11.3 17V12.9C11.3 11.6 12.2 10.6 13.5 10.6C14.8 10.6 15.7 11.6 15.7 12.9V17"
                  stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M11.3 10.4V17" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
        @break

@endswitch
