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

    {{-- Portal / admin UI icons. 24×24 on currentColor, 1.6 stroke to match
         the weight of the social marks above. --}}
    @case('user')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 20 }}" height="{{ $size ?? 20 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="12" cy="8" r="3.6" stroke="currentColor" stroke-width="1.6"/>
            <path d="M4.8 20c0-3.4 3.2-5.6 7.2-5.6s7.2 2.2 7.2 5.6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
        @break

    @case('lock')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 20 }}" height="{{ $size ?? 20 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <rect x="4.6" y="10.4" width="14.8" height="9.6" rx="2.2" stroke="currentColor" stroke-width="1.6"/>
            <path d="M8.2 10.2V7.8a3.8 3.8 0 0 1 7.6 0v2.4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
        @break

    @case('eye')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 20 }}" height="{{ $size ?? 20 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M2.6 12S6 5.8 12 5.8 21.4 12 21.4 12 18 18.2 12 18.2 2.6 12 2.6 12Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <circle cx="12" cy="12" r="3.1" stroke="currentColor" stroke-width="1.6"/>
        </svg>
        @break

    @case('eye-off')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 20 }}" height="{{ $size ?? 20 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M2.6 12S6 5.8 12 5.8c1.4 0 2.7.34 3.8.88M19 9.1c1.6 1.6 2.4 2.9 2.4 2.9S18 18.2 12 18.2c-1.7 0-3.2-.5-4.4-1.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M4 4l16 16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
        @break

    @case('shield-check')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 22 }}" height="{{ $size ?? 22 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 2.9 4.8 5.6v5.5c0 4.3 2.9 8.1 7.2 9.9 4.3-1.8 7.2-5.6 7.2-9.9V5.6L12 2.9Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
            <path d="m8.9 11.9 2.2 2.2 4-4.2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break

    @case('chart')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 20 }}" height="{{ $size ?? 20 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5.4 19V11M12 19V5.6M18.6 19v-5.6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
        </svg>
        @break

    @case('document')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 20 }}" height="{{ $size ?? 20 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M13.6 3.2H7a2 2 0 0 0-2 2v13.6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8.6l-5.4-5.4Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M13.4 3.4v5.2h5.2M8.6 13h6.8M8.6 16.4h4.4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        @break

    @case('gallery')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 20 }}" height="{{ $size ?? 20 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <rect x="3.4" y="4.6" width="17.2" height="14.8" rx="2.2" stroke="currentColor" stroke-width="1.6"/>
            <circle cx="9" cy="10" r="1.5" stroke="currentColor" stroke-width="1.5"/>
            <path d="m4.4 17 4.6-4.2 3.4 3 3-2.6 5 4.4" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
        </svg>
        @break

    @case('chevron-right')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 16 }}" height="{{ $size ?? 16 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="m9.4 5.6 6.4 6.4-6.4 6.4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break

    @case('arrow-long-right')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 20 }}" height="{{ $size ?? 20 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4.6 12h14.2m0 0-5-5m5 5-5 5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break

    @case('home')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 22 }}" height="{{ $size ?? 22 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4 10.4 12 4l8 6.4V19a1.6 1.6 0 0 1-1.6 1.6H5.6A1.6 1.6 0 0 1 4 19v-8.6Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M9.6 20.6v-6h4.8v6" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
        </svg>
        @break

    @case('map-pin')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 16 }}" height="{{ $size ?? 16 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 21s6.4-5.1 6.4-10a6.4 6.4 0 1 0-12.8 0c0 4.9 6.4 10 6.4 10Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
            <circle cx="12" cy="10.6" r="2.4" stroke="currentColor" stroke-width="1.7"/>
        </svg>
        @break

    @case('calendar')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 16 }}" height="{{ $size ?? 16 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <rect x="3.6" y="5.4" width="16.8" height="15" rx="2.2" stroke="currentColor" stroke-width="1.6"/>
            <path d="M3.6 10h16.8M8.4 3.4v4M15.6 3.4v4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
        @break

    @case('download')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 20 }}" height="{{ $size ?? 20 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 3.8v11m0 0-4-4m4 4 4-4M4.4 19.4h15.2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break

    @case('logout')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 22 }}" height="{{ $size ?? 22 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M9.4 4.6H5.8A1.8 1.8 0 0 0 4 6.4v11.2a1.8 1.8 0 0 0 1.8 1.8h3.6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
            <path d="M15.4 15.6 19.6 12l-4.2-3.6M19.2 12H9.6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break

    @case('chevron-down-sm')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 18 }}" height="{{ $size ?? 18 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="m6.4 9.4 5.6 5.6 5.6-5.6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break

    @case('search')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 20 }}" height="{{ $size ?? 20 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="10.8" cy="10.8" r="6.6" stroke="currentColor" stroke-width="1.7"/>
            <path d="m15.8 15.8 4 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
        </svg>
        @break

    {{-- Admin navigation and stat-card icons. --}}
    @case('grid')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 22 }}" height="{{ $size ?? 22 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <rect x="3.4" y="3.4" width="7.2" height="7.2" rx="1.8" stroke="currentColor" stroke-width="1.7"/>
            <rect x="13.4" y="3.4" width="7.2" height="7.2" rx="1.8" stroke="currentColor" stroke-width="1.7"/>
            <rect x="3.4" y="13.4" width="7.2" height="7.2" rx="1.8" stroke="currentColor" stroke-width="1.7"/>
            <rect x="13.4" y="13.4" width="7.2" height="7.2" rx="1.8" stroke="currentColor" stroke-width="1.7"/>
        </svg>
        @break

    @case('building')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 22 }}" height="{{ $size ?? 22 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4.4 20.4V6.2l7-2.6v16.8M11.4 20.4h8.2V10l-8.2-2.4" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M7 9.4v1.2M7 13.4v1.2M15 13v1.2M15 16.6v1.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
        @break

    @case('ruler')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 22 }}" height="{{ $size ?? 22 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <rect x="1.6" y="8.4" width="20.8" height="7.2" rx="1.8" transform="rotate(-45 12 12)" stroke="currentColor" stroke-width="1.6"/>
            <path d="M9.2 7.6 10.8 9.2M12 4.8l1.6 1.6M6.4 10.4 8 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        @break

    @case('clock')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 22 }}" height="{{ $size ?? 22 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="12" cy="12" r="8.4" stroke="currentColor" stroke-width="1.7"/>
            <path d="M12 7.4V12l3.2 2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break

    @case('check-circle')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 22 }}" height="{{ $size ?? 22 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="12" cy="12" r="8.4" stroke="currentColor" stroke-width="1.7"/>
            <path d="m8.4 12.2 2.4 2.4 4.8-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        @break

    @case('file-plus')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 22 }}" height="{{ $size ?? 22 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M13.6 3.4H7a2 2 0 0 0-2 2v13.2a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8.8l-5.4-5.4Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M13.4 3.6v5.2h5.2" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
            <path d="M12 12.4v5M9.5 14.9h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
        @break

    @case('image-plus')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 22 }}" height="{{ $size ?? 22 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M20.6 12.6V6.6a2 2 0 0 0-2-2H5.4a2 2 0 0 0-2 2v10.8a2 2 0 0 0 2 2h7.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            <circle cx="8.6" cy="9.6" r="1.5" stroke="currentColor" stroke-width="1.5"/>
            <path d="m4 16.4 4.4-4 3.2 2.8" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
            <path d="M18 15.4v5M15.5 17.9h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
        </svg>
        @break

    @case('user-cog')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 22 }}" height="{{ $size ?? 22 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="9.6" cy="7.6" r="3.4" stroke="currentColor" stroke-width="1.6"/>
            <path d="M3.4 19.4c0-3.1 2.8-5.2 6.2-5.2 1 0 2 .2 2.8.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            <circle cx="17.4" cy="16.6" r="2.6" stroke="currentColor" stroke-width="1.6"/>
            <path d="M17.4 12.6v1M17.4 20.6v1M21.4 16.6h-1M14.4 16.6h-1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        @break

    @case('pencil')
        <svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size ?? 20 }}" height="{{ $size ?? 20 }}"
             viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4.4 19.6h4l10-10a2.3 2.3 0 0 0-3.2-3.2l-10 10v3.2Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="m13.4 7.4 3.2 3.2" stroke="currentColor" stroke-width="1.6"/>
        </svg>
        @break

@endswitch
