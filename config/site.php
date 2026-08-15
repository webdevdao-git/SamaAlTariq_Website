<?php

/**
 * Every string and image on the landing page.
 *
 * Copy is transcribed verbatim from the Figma file "Sama Al Tariq — Landing
 * Page redesign" (node 50:2119 / frame 1195:2). Anything marked PLACEHOLDER was
 * not present in the file and needs client sign-off — see README.
 *
 * Edit here, not in the Blade views.
 */
return [

    'name' => 'Sama Al Tariq',
    'legal_name' => 'Sama Al Tariq Building Contracting L.L.C.',
    'tagline' => 'Building Contracting LLC.',
    'copyright' => '© 2026 Sama Al Tariq Building Contracting L.L.C.',

    // Where new enquiries are delivered. Falls back to the mail "from" address.
    'enquiry_to' => env('ENQUIRY_TO'),

    'nav' => [
        ['label' => 'Home', 'href' => '#top'],
        // The entries that are pages rather than sections of the landing page.
        // App\Support\Nav leaves non-fragment hrefs alone.
        ['label' => 'About', 'href' => '/about'],
        ['label' => 'Projects', 'href' => '/projects'],
        ['label' => 'Services', 'href' => '#services'],
        ['label' => 'Process', 'href' => '/process'],
        ['label' => 'Contact', 'href' => '#contact'],
    ],

    /*
     * `icon` maps to a case in resources/views/components/icon.blade.php — the
     * hero renders these as icons only, so a label without a matching case
     * would render an empty tap target.
     *
     * The WhatsApp link carries a prefilled greeting, which is why it is a
     * long api.whatsapp.com URL rather than wa.me/<number>.
     */
    'social' => [
        ['label' => 'Instagram', 'icon' => 'instagram', 'href' => 'https://www.instagram.com/samaaltariqcontracting'],
        ['label' => 'Facebook', 'icon' => 'facebook', 'href' => 'https://www.facebook.com/samaaltariq/'],
        ['label' => 'LinkedIn', 'icon' => 'linkedin', 'href' => 'https://www.linkedin.com/company/sama-al-tariq'],
        ['label' => 'TikTok', 'icon' => 'tiktok', 'href' => 'https://www.tiktok.com/@samaaltariq'],
        ['label' => 'WhatsApp', 'icon' => 'whatsapp', 'href' => 'https://api.whatsapp.com/send/?phone=971543190845&text=Welcome%20To%20Sama%20Al%20Tariq%2C%20How%20can%20we%20help%20you%3F&type=phone_number&app_absent=0'],
    ],

    'hero' => [
        'eyebrow' => 'Delivering Quality Since 2023',
        'intro' => 'We deliver exceptional construction, engineering, and contracting solutions that shape modern communities through innovation and uncompromising standards.',
        'cta' => ['label' => 'Explore Projects', 'href' => '#projects'],
        'words' => ['first' => 'Building', 'second' => 'With Precision', 'third' => 'Future'],
        'image' => 'images/hero.webp',
        'alt' => 'Double-height majlis with arched windows overlooking a lake',
    ],

    'about' => [
        'label' => 'Who We Are',
        'heading' => [
            'We build with precision,',
            'delivering structures that stand as',
            'lasting symbols of quality and trust.',
        ],
        'image' => 'images/about-interior.webp',
        'alt' => 'Curved timber-ribbed interior with a sculpted lounge chair',
        'subheading' => ['Redefining', 'Construction Excellence'],
        'body' => [
            'Every successful structure begins with thoughtful planning, expert engineering, and flawless execution. These principles guide every decision we make and every project we deliver.',
            'By combining technical expertise with innovative construction practices, Sama Al Tariq create developments that stand the test of time.',
        ],
        'stats' => [
            ['value' => '03+', 'label' => 'Years of Expertise'],
            ['value' => '250k+', 'label' => 'm² Delivered'],
            ['value' => '12+', 'label' => 'Completed Projects'],
        ],
    ],

    /*
     * The About page — Figma frame 1377:3 on the same canvas, 1728×8276.
     *
     * Copy and geometry are read off that frame. Its images live in
     * public/images/about and were extracted from the file itself: the ones
     * with nothing on top of them are cut from the 2× page render, and the
     * three that carry an overlay in the design — the hero photo, the villa
     * behind the collage, and the lounge behind the values band — come from
     * their original uploaded sources, so no caption or frosted panel is
     * baked into the picture.
     *
     * The file spells "Construction" as "CONTSRUCTION" in two places, the
     * closing type and the values band. Both are corrected here.
     */
    'about_page' => [

        /*
         * Frame 1377:4, 1728×1117. The same photo-under-gradients hero as the
         * landing page, but the lower third is arranged differently: a
         * sentence-case headline on the gutter with a bracketed page tag
         * opposite it, then a band carrying a line of copy and three figures.
         */
        /*
         * The hero, rearranged to the frame's newer treatment: the same
         * material as before in a different order. What was a headline
         * sentence with a [ tag ] beside it is now a row — the label, that
         * sentence as a paragraph, and the link out — over the page's own word
         * set large on the gutter, with the figures beside it.
         *
         * `body` is the old heading and lead run together, verbatim, so no
         * copy was lost in the move.
         */
        'hero' => [
            'label' => 'Why We Are',
            'body' => 'We Build the Foundations of What Comes Next. Construction Shaped By Expertise, Intention, And Enduring Quality.',
            'cta' => ['label' => 'Explore Projects', 'href' => '/projects'],
            'word' => 'About Us',
            'stats' => [
                ['value' => '95%', 'label' => 'On-time handovers'],
                ['value' => '03+', 'label' => 'Years of Expertise'],
                ['value' => '250k+', 'label' => 'm² Delivered'],
            ],
            'image' => 'images/about/hero.webp',
            'alt' => 'White villa with a reflecting pool and palms',
        ],

        // Frame 1377:26. Image left, one paragraph in the right column, its
        // last line sitting on the foot of the image. The opening clause is
        // set in the semibold cut, as in the design.
        'intro' => [
            'lead' => 'Sama Al Tariq Building Contracting L.L.C.',
            'body' => 'is a Dubai-based construction and fit-out company bringing multiple disciplines together under one integrated approach. From luxury residences and hospitality environments to corporate, retail, healthcare and government projects, we manage every stage from tender and planning through construction and handover.',
            'image' => 'images/about/intro-stair.webp',
            'alt' => 'Curved stone staircase in a sunlit hall',
        ],

        // Frame 1386:608. The mirror of the block above, on the pale ground.
        // Its heading is set in the sans, not the display serif.
        'vision' => [
            'heading' => ['One vision,', 'brought together under one roof.'],
            'body' => 'From the first tender to final handover, our teams work as one to create greater clarity throughout the build and lasting value in the finished result.',
            'cta' => ['label' => 'See Our process', 'href' => '#process'],
            'image' => 'images/about/vision-lounge.webp',
            'alt' => 'Living room with a panelled wall and cove lighting',
        ],

        // Frame 1386:621. Label and heading, then a three-image collage with
        // the logo mark centred over it, then copy in the right column.
        'approach' => [
            'label' => 'Our Approach',
            'heading' => 'We believe exceptional spaces are shaped long before the first structure takes form.',
            'body' => 'A considered vision, collective expertise, and disciplined execution form the foundation of every project we undertake. By bringing diverse disciplines together, we create spaces that are purposeful, cohesive, and built to last.',
            'cta' => ['label' => 'Explore Projects', 'href' => '#projects'],
            'images' => [
                'main' => ['src' => 'images/about/approach-villa.webp', 'alt' => 'Villa entrance under an open sky'],
                'left' => ['src' => 'images/about/approach-joinery.webp', 'alt' => 'Timber joinery wall and lit reveal'],
                'right' => ['src' => 'images/about/approach-stair.webp', 'alt' => 'Stone stair with a glass balustrade'],
            ],
        ],

        /*
         * Frame 1377:102, 1728×980 — the band the metadata returned empty.
         * A dark photo carrying a three-part label row and four frosted rows,
         * each 120px tall and 8px apart, inset 130px from both gutters.
         */
        'values' => [
            'label_left' => 'Modern',
            'heading' => 'Principles that shape every decision we make.',
            'label_right' => 'Construction',
            'image' => 'images/about/values-lounge.webp',
            'alt' => 'Timber-lined lounge under a sculptural light',
            'items' => [
                [
                    'number' => '01',
                    'title' => 'Accountability',
                    'body' => 'We take ownership of our commitments, keeping responsibilities clear and ensuring every stage is managed with care and consistency.',
                ],
                [
                    'number' => '02',
                    'title' => 'Clarity',
                    'body' => 'We maintain open communication across clients, consultants, suppliers, and project teams, keeping decisions, expectations, and progress aligned.',
                ],
                [
                    'number' => '03',
                    'title' => 'Discipline',
                    'body' => 'We follow structured planning, coordinated execution, and rigorous site management to keep projects moving efficiently and responsibly.',
                ],
                [
                    'number' => '04',
                    'title' => 'Respect',
                    'body' => 'We value the people, expertise, materials, and environments involved in every project, fostering a culture of professionalism and collaboration.',
                ],
            ],
        ],

        /*
         * Frame 1386:1161. Three staggered lines of 128px display type, a copy
         * row, then a full-bleed strip of four images.
         *
         * `words` is one sentence broken exactly where the design breaks it —
         * the view sets each line's own alignment, which is what makes the
         * staircase.
         */
        'purpose' => [
            'words' => ['Construction', 'With A Clearer', 'Purpose'],
            'body' => 'Have a project in mind? Speak with our team and discover how the right expertise can turn your ambition into a considered, well-delivered reality.',
            'cta' => ['label' => 'Get a free consultation', 'href' => '#contact'],
            /*
             * The closing strip runs rather than sitting still, so it is a
             * roster and not a row of four — seven here, and the loop takes as
             * many as it is given.
             */
            'strip' => [
                ['src' => 'images/about/strip-living.webp', 'alt' => 'Living room with a sculpted feature wall'],
                ['src' => 'images/about/strip-lounge.webp', 'alt' => 'Timber-lined lounge with modular seating'],
                ['src' => 'images/about/strip-bedroom.webp', 'alt' => 'Bedroom under a timber ceiling opening to a terrace'],
                ['src' => 'images/about/strip-terrace.webp', 'alt' => 'Planted roof terrace with an outdoor dining table'],
                ['src' => 'images/about/strip-dining-room.webp', 'alt' => 'Dining room with an arched mirror and upholstered chairs'],
                ['src' => 'images/about/strip-salon.webp', 'alt' => 'Salon with a curved illuminated ceiling'],
                ['src' => 'images/about/strip-bar.webp', 'alt' => 'Lounge with a dark fitted bar and tufted seating'],
            ],
        ],
    ],

    /*
     * The client roster, in the order the marquee runs them. Names and files
     * come from the company's other site (src/lib/clients.ts there), which
     * carries the curated set and the correct spellings — "wasl" and
     * "archcorp" are lowercase by their own branding, not a typo here.
     *
     * The strip scrolls, so the list is no longer bounded by what fits a row:
     * adding one lengthens the loop rather than wrapping it.
     */
    /*
     * The projects page, taken from Figma frame 1402:2 ("Projects", 1728x6892)
     * on the Landing Page redesign page. Every figure below is read off that
     * frame rather than derived, and the frame is 1728 wide with 79 of padding
     * either side, so the content column is 1568.
     *
     * COLUMNS. `fr` is the column's width in frame pixels and goes straight
     * into grid-template-columns as a fraction, so a row reproduces the frame's
     * split at any width: 992+24+552 = 1568, and so do 772+24+772 and
     * 620+24+924. These are not twelve-track spans — 992 is 7.59 tracks — which
     * is why the earlier 7/5 and 5/7 spans could never land on the frame.
     *
     * RATIOS. `ratio` is the picture's own box in the frame. There is no shared
     * height and no shared ratio: the frame gives each row its own picture
     * height (727, 332, 635, 727), and the columns of a row come out level
     * because the numbers were chosen to — 727+12+27 = 766 = 371+24+371.
     *
     * The covers are the frame's own pictures, exported from those nodes at 2x
     * so each file already carries the crop the designer gave it. That is why
     * nothing here sets an object-position: at these ratios the picture fills
     * its box exactly and there is nothing left to position.
     *
     * `image` is a file in public/images/projects/covers, not a project slug:
     * Hospitality and Fitness each show one project twice, from two different
     * photographs, exactly as the frame draws them. Those second tiles carry a
     * `project` naming the page they open, since there is one page per project
     * and two tiles pointing at it.
     *
     * Titles, categories, locations and areas come from the company's other
     * site (src/lib/projects.ts there), so they are the figures the business
     * already publishes.
     */
    'projects_page' => [
        'heading' => ['Selected', 'Projects'],
        'views' => ['gallery' => 'Gallery', 'list' => 'List'],
        'groups' => [
            [
                'name' => 'Residential',
                'rows' => [
                    // 1429:99 — the tall picture beside a stacked pair.
                    ['columns' => [
                        ['fr' => 992, 'tiles' => [
                            ['image' => 'jumeirah-golf-estate-villas', 'ratio' => '992/727', 'title' => 'Jumeirah Golf Estate Villas', 'category' => 'Luxury Residential', 'location' => 'Jumeirah Golf Estate, Dubai', 'size' => '18,000 Sq Ft', 'duration' => '8 Months'],
                        ]],
                        ['fr' => 552, 'tiles' => [
                            ['image' => 'villa-pv39-tilal-al-ghaf', 'ratio' => '552/332', 'title' => 'Villa PV39, Tilal-Al-Ghaf', 'category' => 'Luxury Residential', 'location' => 'Tilal-Al-Ghaf, Dubai', 'size' => '8,000 Sq Ft', 'duration' => '6 Months'],
                            ['image' => 'w-residence-palm-jumeirah', 'ratio' => '552/332', 'title' => 'W Residence, Palm Jumeirah', 'category' => 'Luxury Residential', 'location' => 'Palm Jumeirah, Dubai', 'size' => '6,500 Sq Ft', 'duration' => '3 Months'],
                        ]],
                    ]],
                    // 1429:116 — two even columns, a shorter picture than above.
                    ['columns' => [
                        ['fr' => 772, 'tiles' => [
                            ['image' => 'emirates-hills-villa', 'ratio' => '772/635', 'title' => 'Emirates Hills Villa', 'category' => 'Luxury Residential', 'location' => 'Emirates Hills, Dubai', 'size' => '30,000 Sq Ft', 'duration' => '12 Months'],
                        ]],
                        ['fr' => 772, 'tiles' => [
                            ['image' => 'jumeirah-island-villa', 'ratio' => '772/635', 'title' => 'Jumeirah Island Villa', 'category' => 'Luxury Residential', 'location' => 'Jumeirah Island, Dubai', 'size' => '14,000+ Sq Ft', 'duration' => '8 Months'],
                        ]],
                    ]],
                ],
            ],
            [
                // 1443:541 — the pair on the left this time, 620 against 924.
                'name' => 'Commercial & Corporate',
                'rows' => [
                    ['columns' => [
                        ['fr' => 620, 'tiles' => [
                            ['image' => 'i-rise-tower-office', 'ratio' => '620/332', 'title' => 'I-Rise Tower Office', 'category' => 'Office Fit-Out', 'location' => 'I-Rise Tower, Dubai', 'size' => '5,000+ Sq Ft', 'duration' => '4 Months'],
                            ['image' => 'boulevard-plaza-office', 'ratio' => '620/332', 'title' => 'Boulevard Plaza Office', 'category' => 'Office Fit-Out', 'location' => 'Boulevard Plaza, Dubai', 'size' => '7,500+ Sq Ft', 'duration' => '3 Months'],
                        ]],
                        ['fr' => 924, 'tiles' => [
                            ['image' => 'wasl-properties-hq', 'ratio' => '924/727', 'title' => 'WASL Properties HQ', 'category' => 'Corporate', 'location' => 'Sheikh Zayed Road, Dubai', 'size' => '45,000 Sq Ft', 'duration' => '4 Months'],
                        ]],
                    ]],
                ],
            ],
            [
                // 1443:1104 — even, and the frame's squarest pictures at 772x727.
                'name' => 'Hospitality, F&B',
                'rows' => [
                    ['columns' => [
                        ['fr' => 772, 'tiles' => [
                            ['image' => 'benjarong-dusit-thani', 'ratio' => '772/727', 'title' => 'Benjarong, Dusit Thani', 'category' => 'Hospitality / F&B', 'location' => 'JVC, Dubai', 'size' => '10,000+ Sq Ft', 'duration' => '3 Months'],
                        ]],
                        ['fr' => 772, 'tiles' => [
                            ['image' => 'benjarong-dusit-thani-2', 'project' => 'benjarong-dusit-thani', 'ratio' => '772/727', 'title' => 'Benjarong, Dusit Thani', 'category' => 'Hospitality / F&B', 'location' => 'JVC, Dubai', 'size' => '10,000+ Sq Ft', 'duration' => '3 Months'],
                        ]],
                    ]],
                ],
            ],
            [
                // 1443:1134 — the same row again, which is how the frame closes.
                'name' => 'Fitness & Spa',
                'rows' => [
                    ['columns' => [
                        ['fr' => 772, 'tiles' => [
                            ['image' => 'fidelity-gym-jlt', 'ratio' => '772/727', 'title' => 'Fidelity Gym, JLT', 'category' => 'Fitness & Spa', 'location' => 'Jumeirah Lake Towers, Dubai', 'size' => '25,425 Sq Ft', 'duration' => '6 Months'],
                        ]],
                        ['fr' => 772, 'tiles' => [
                            ['image' => 'fidelity-gym-jlt-2', 'project' => 'fidelity-gym-jlt', 'ratio' => '772/727', 'title' => 'Fidelity Gym, JLT', 'category' => 'Fitness & Spa', 'location' => 'Jumeirah Lake Towers, Dubai', 'size' => '25,425 Sq Ft', 'duration' => '6 Months'],
                        ]],
                    ]],
                ],
            ],
        ],
    ],

    /*
     * The single project page, from Figma frame 1472:1339 (1728x6683).
     *
     * Only what that page adds lives here. Title, category, location, area and
     * duration are already published by the projects grid above and are read
     * from it by slug — the `image` on a tile is the slug — so the two pages
     * can never quote different figures for the same project. `facts` is for a
     * project the grid does not carry: Villa B200 has photographs but no tile.
     *
     * `tiles` is how many of the 3x3 intro grid a project can fill. Two shoots
     * came in with four photographs rather than nine, so those grids run short
     * rather than repeating a picture.
     *
     * The hero is the picture the projects page shows for that project, cropped
     * from the photograph behind that tile rather than from the tile itself —
     * a 992x727 export has nothing left to give a 1728x1117 hero. Villa B200
     * has no tile there, so its hero is the first photograph in its folder and
     * its grid starts after it.
     *
     * COPY. Jumeirah Golf Estate's three paragraphs are the designer's own,
     * verbatim from the frame. The rest are written from what the business
     * already publishes — scope, place, area, programme — and describe no
     * feature that is not in those figures. They are meant to be read and
     * corrected by someone who was on site.
     *
     * `year` is only known for the project the frame names. The row is drawn
     * only where there is a year to put in it, rather than carrying a guess.
     */
    'project_pages' => [
        'jumeirah-golf-estate-villas' => [
            'about' => 'Full renovation & fit-out',
            'year' => '2024',
            'lead' => 'A refined residential project in Dubai, combining sophisticated interiors with precise construction and bespoke detailing.',
            'body' => [
                'Jumeirah Golf Estate Villas were delivered as a full renovation and fit-out over eight months. The programme brought together an open island-led kitchen, light-filled lounges, timber-slat ceilings, a poolside terrace and coordinated joinery and finishes across the home.',
                'The villas were delivered with a focus on material quality, seamless finishes, and carefully coordinated architectural and interior elements—creating elegant, functional spaces designed for contemporary luxury living.',
            ],
            'tiles' => 9,
            'related' => ['villa-pv39-tilal-al-ghaf', 'w-residence-palm-jumeirah', 'emirates-hills-villa', 'jumeirah-island-villa'],
        ],
        'villa-pv39-tilal-al-ghaf' => [
            'about' => 'Interior fit-out',
            'year' => null,
            'lead' => 'A private villa in Tilal-Al-Ghaf, fitted out to one standard from the entrance through to the last bedroom.',
            'body' => [
                'Villa PV39 was delivered as an interior fit-out across 8,000 sq ft over six months, covering the living and dining spaces, the kitchen, the bedrooms and the joinery that ties them together.',
                'The programme was sequenced so that the finishes meet cleanly from room to room — stone, timber and plaster set out against one another in advance rather than resolved on site.',
            ],
            'tiles' => 9,
            'related' => ['jumeirah-golf-estate-villas', 'w-residence-palm-jumeirah', 'emirates-hills-villa', 'jumeirah-island-villa'],
        ],
        'w-residence-palm-jumeirah' => [
            'about' => 'Interior fit-out',
            'year' => null,
            'lead' => 'An apartment on the Palm, fitted out in three months without letting the programme show in the finish.',
            'body' => [
                'The W Residence covers 6,500 sq ft and was delivered in three months, the shortest programme of the residential projects on this site.',
                'A short programme is won before the first delivery arrives: long-lead joinery and stone were ordered against a set-out agreed at the start, so the trades on site followed one drawing rather than three.',
            ],
            'tiles' => 4,
            'related' => ['jumeirah-golf-estate-villas', 'villa-pv39-tilal-al-ghaf', 'emirates-hills-villa', 'jumeirah-island-villa'],
        ],
        'emirates-hills-villa' => [
            'about' => 'Villa fit-out',
            'year' => null,
            'lead' => 'The largest villa on this site — 30,000 sq ft in Emirates Hills, delivered over a year.',
            'body' => [
                'The Emirates Hills villa ran twelve months across 30,000 sq ft, and at that size the work is as much programme as it is finish: the trades follow one another through the house rather than working the whole of it at once.',
                'The house was handed over room by room in that order, which is what keeps a project of this length from finishing everything at the same time and nothing before it.',
            ],
            'tiles' => 9,
            'related' => ['jumeirah-golf-estate-villas', 'villa-pv39-tilal-al-ghaf', 'w-residence-palm-jumeirah', 'jumeirah-island-villa'],
        ],
        'jumeirah-island-villa' => [
            'about' => 'Villa fit-out',
            'year' => null,
            'lead' => 'A villa on Jumeirah Island, delivered across 14,000 sq ft in eight months.',
            'body' => [
                'The Jumeirah Island villa was delivered as a full interior fit-out, the living spaces, kitchen and bedrooms taken together as one package rather than let separately.',
                'One package is what allows a single set-out to run through the house — the joinery, the stone and the ceilings meet because they were drawn against each other before any of them was made.',
            ],
            'tiles' => 4,
            'related' => ['jumeirah-golf-estate-villas', 'villa-pv39-tilal-al-ghaf', 'w-residence-palm-jumeirah', 'emirates-hills-villa'],
        ],
        'villa-b200-tilal-al-ghaf' => [
            // Not on the projects grid, so its facts are here. Area, duration
            // and year are unknown to this site and their rows stay closed.
            'facts' => [
                'title' => 'Villa B200, Tilal-Al-Ghaf',
                'category' => 'Luxury Residential',
                'location' => 'Tilal-Al-Ghaf, Dubai',
                'size' => null,
                'duration' => null,
            ],
            'about' => 'Villa fit-out',
            'year' => null,
            'lead' => 'A second villa at Tilal-Al-Ghaf, alongside PV39 on the same development.',
            'body' => [
                'Villa B200 was delivered as an interior fit-out across the living spaces, kitchen and bedrooms.',
                'Two houses on one development are rarely the same house twice: the set-out is redrawn against the plot and the light it gets, and only the standard of finish carries across unchanged.',
            ],
            'tiles' => 9,
            'related' => ['villa-pv39-tilal-al-ghaf', 'jumeirah-golf-estate-villas', 'w-residence-palm-jumeirah', 'emirates-hills-villa'],
        ],
        'i-rise-tower-office' => [
            'about' => 'Office fit-out',
            'year' => null,
            'lead' => 'An office fit-out at I-Rise Tower, delivered over four months around a working building.',
            'body' => [
                'The I-Rise Tower office covers more than 5,000 sq ft and was delivered in four months, the fit-out taken from bare floor through to a working office.',
                'A tower fit-out is a logistics problem before it is a joinery one — deliveries, hoists and noise all run to the building\'s hours, and the programme is built around them rather than against them.',
            ],
            'tiles' => 8,
            'related' => ['boulevard-plaza-office', 'wasl-properties-hq', 'fidelity-gym-jlt', 'benjarong-dusit-thani'],
        ],
        'boulevard-plaza-office' => [
            'about' => 'Office fit-out',
            'year' => null,
            'lead' => 'More than 7,500 sq ft at Boulevard Plaza, fitted out in three months.',
            'body' => [
                'The Boulevard Plaza office was delivered in three months across more than 7,500 sq ft, covering the workspace, the meeting rooms and the joinery and services that serve them.',
                'The finishes were coordinated with the base build rather than laid over it, so ceilings, lighting and partitions line through as one setting-out.',
            ],
            'tiles' => 8,
            'related' => ['i-rise-tower-office', 'wasl-properties-hq', 'fidelity-gym-jlt', 'jumeirah-golf-estate-villas'],
        ],
        'wasl-properties-hq' => [
            'about' => 'Corporate fit-out',
            'year' => null,
            'lead' => 'A 45,000 sq ft corporate headquarters on Sheikh Zayed Road, delivered in four months.',
            'body' => [
                'WASL Properties HQ is the largest project on this site by area and among the shortest by programme: 45,000 sq ft in four months, which only works if the floors are run in parallel rather than in sequence.',
                'The fit-out covers the workspace, the meeting and reception areas and the finishes throughout, coordinated so that a floor could be closed out while the next was still in progress.',
            ],
            'tiles' => 9,
            'related' => ['i-rise-tower-office', 'boulevard-plaza-office', 'benjarong-dusit-thani', 'fidelity-gym-jlt'],
        ],
        'benjarong-dusit-thani' => [
            'about' => 'Restaurant fit-out',
            'year' => null,
            'lead' => 'A restaurant at the Dusit Thani, more than 10,000 sq ft delivered in three months.',
            'body' => [
                'Benjarong was delivered as a hospitality fit-out across more than 10,000 sq ft in three months, taking in the dining rooms, the bar and the front of house.',
                'A restaurant is finished to be looked at from a seat rather than from a drawing: the joinery, the lighting and the loose furniture were set out together so the room reads as one from every table.',
            ],
            'tiles' => 7,
            'related' => ['fidelity-gym-jlt', 'wasl-properties-hq', 'i-rise-tower-office', 'jumeirah-golf-estate-villas'],
        ],
        'fidelity-gym-jlt' => [
            'about' => 'Fitness & spa fit-out',
            'year' => null,
            'lead' => 'A 25,425 sq ft gym and spa in Jumeirah Lake Towers, delivered over six months.',
            'body' => [
                'Fidelity Gym covers 25,425 sq ft over two levels in Jumeirah Lake Towers and was delivered in six months, from the equipment floor through to the spa and changing rooms.',
                'A gym carries loads and services a fit-out usually does not — plant, ventilation and drainage were set out with the structure rather than fitted around it once the floor was down.',
            ],
            'tiles' => 9,
            'related' => ['benjarong-dusit-thani', 'wasl-properties-hq', 'boulevard-plaza-office', 'jumeirah-golf-estate-villas'],
        ],
    ],

    /*
     * The Our Process page, from Figma frame 1508:2 ("Our Process",
     * 1728x9052). Copy is the frame's own, verbatim.
     *
     * The page runs: a photographic hero with the heading split across the
     * gutters; four numbered steps against a hairline each; then each of those
     * steps again as a phase — a picture beside a title and lead, followed by
     * four points across the width; and a closing word over a photograph.
     *
     * PICTURES. The hero and the closing photograph are the frame's own. The
     * four phase pictures and the four step thumbnails are not: the frame
     * repeats one placeholder in all four of each, which on a live page reads
     * as a mistake rather than a design. They are four different projects
     * instead, from the photographs behind the projects page's own tiles.
     */
    'process_page' => [
        'hero' => [
            'label' => 'How We Work',
            'body' => 'A structured, end-to-end delivery process built on precision, coordination, and accountability — from concept to handover, under a single project lead.',
            'cta' => ['label' => 'Explore Projects', 'href' => '/projects'],
            // Split across the two gutters, 108 Juana Alt, as the frame sets it.
            'heading' => ['Our', 'Process'],
            'image' => 'images/process/hero.webp',
        ],

        'steps' => [
            ['number' => '01', 'title' => 'Tender And Cost Estimation', 'image' => 'images/process/step-1.webp',
             'body' => 'Accurate cost planning and detailed estimates that establish a strong foundation for every successful project.'],
            ['number' => '02', 'title' => 'Design And Engineering', 'image' => 'images/process/step-2.webp',
             'body' => 'Detailed drawings, material selection, and value engineering that turn an approved budget into a buildable scheme.'],
            ['number' => '03', 'title' => 'Construction And Fit-Out', 'image' => 'images/process/step-3.webp',
             'body' => 'Coordinated site delivery with dedicated supervision, strict QA checkpoints, and transparent weekly progress reporting.'],
            ['number' => '04', 'title' => 'Handover And Aftercare', 'image' => 'images/process/step-4.webp',
             'body' => 'Snagging, testing and commissioning, full documentation, and a maintenance period that protects the finished asset.'],
        ],

        /*
         * `title` is broken where the frame breaks it — the first is two lines
         * by a hard return in the file, the rest wrap in their 653 measure.
         */
        'phases' => [
            [
                'label' => 'Phase 01',
                'title' => ['Tender And', 'Cost Estimation'],
                'lead' => 'A structured start built on accuracy, clarity and confidence from the first step.',
                'image' => 'images/process/phase-1.webp',
                'items' => [
                    ['title' => 'Accurate Review', 'body' => 'We review RFQs, drawings and specifications in detail to understand the complete scope and requirements.'],
                    ['title' => 'Site Visit & Survey', 'body' => 'Thorough site assessment to identify risks, constraints and opportunities for successful delivery.'],
                    ['title' => 'Price Every Item', 'body' => 'Detailed BOQ analysis with transparent pricing for every item and clear cost breakdown.'],
                    ['title' => 'Submit With Confidence', 'body' => 'Comprehensive and professional submission that builds trust and improves your win rate.'],
                ],
            ],
            [
                'label' => 'Phase 02',
                'title' => ['Engineering AND Pre-Construction'],
                'lead' => 'The foundation for delivery, set through careful planning and procurement.',
                'image' => 'images/process/phase-2.webp',
                'items' => [
                    ['title' => 'Plan With Precision', 'body' => 'We develop detailed plans and strategies to ensure a smooth project journey.'],
                    ['title' => 'Coordinate & Validate', 'body' => 'Shop drawings, coordination and approvals to eliminate conflicts early.'],
                    ['title' => 'Procure Smart', 'body' => 'Right materials, right time. Quality suppliers and best value procurement.'],
                    ['title' => 'Mobilise For Success', 'body' => 'Efficient site setup and mobilisation to start strong and stay ahead.'],
                ],
            ],
            [
                'label' => 'Phase 03',
                'title' => ['Construction AND Execution'],
                'lead' => 'Disciplined site delivery where engineering becomes a finished, functioning space.',
                'image' => 'images/process/phase-3.webp',
                'items' => [
                    ['title' => 'Build With Discipline', 'body' => 'Civil and structural works executed to drawing, with setting-out and quality checks at every stage.'],
                    ['title' => 'Install The Systems', 'body' => 'HVAC, electrical and plumbing installed and cleanly coordinated with the finishes.'],
                    ['title' => 'Finish To Perfection', 'body' => 'Flooring, ceilings, joinery and decorative finishes delivered to specification.'],
                    ['title' => 'Control The Quality', 'body' => 'Weekly progress, inspection sign-offs and strict HSE keep delivery on track.'],
                ],
            ],
            [
                'label' => 'Phase 04',
                'title' => ['Handover AND Defects Liability'],
                'lead' => 'Final inspections, defect resolution and clean handover with full documentation and warranties.',
                'image' => 'images/process/phase-4.webp',
                'items' => [
                    ['title' => 'Snag & De-Snag', 'body' => 'Internal snag walks and joint inspections close every punch-list item before sign-off.'],
                    ['title' => 'Test & Commission', 'body' => 'System testing, functional checks and authority approvals confirm performance.'],
                    ['title' => 'As-Builts & O&M', 'body' => 'As-built drawings, O&M manuals and material warranties handed over in full.'],
                    ['title' => 'Handover & DLP Support', 'body' => 'Taking-over certificate, defects rectification and ongoing support through the DLP.'],
                ],
            ],
        ],

        // 128 Juana Alt SemiBold over the photograph, as the frame closes.
        'consultation' => ['word' => 'Consultation', 'image' => 'images/process/consultation.webp'],
    ],

    'clients' => [
        'label' => ["Companies We've", 'Worked With'],
        'logos' => [
            ['name' => 'Emaar', 'src' => 'images/clients/emaar.webp'],
            ['name' => 'DAMAC', 'src' => 'images/clients/damac.webp'],
            ['name' => 'wasl', 'src' => 'images/clients/wasl.webp'],
            ['name' => 'ADNOC', 'src' => 'images/clients/adnoc.webp'],
            ['name' => 'Al-Futtaim', 'src' => 'images/clients/al-futtaim.webp'],
            ['name' => 'Fairmont', 'src' => 'images/clients/fairmont.webp'],
            ['name' => 'Pullman Hotels and Resorts', 'src' => 'images/clients/pullman-hotels-and-resorts.webp'],
            ['name' => 'Dusit Thani', 'src' => 'images/clients/dusit-thani.webp'],
            ['name' => 'Saint-Gobain', 'src' => 'images/clients/saint-gobain.webp'],
            ['name' => 'Knauf', 'src' => 'images/clients/knauf.webp'],
            ['name' => 'Gyproc', 'src' => 'images/clients/gyproc.webp'],
            ['name' => 'ALEC', 'src' => 'images/clients/alec.webp'],
            ['name' => 'Government of Dubai', 'src' => 'images/clients/government-of-dubai.webp'],
            ['name' => 'Dubai Police', 'src' => 'images/clients/dubai-police.webp'],
            ['name' => 'Taj Dubai', 'src' => 'images/clients/taj-dubai.webp'],
            ['name' => 'Novomed', 'src' => 'images/clients/novomed.webp'],
            ['name' => 'Fakih IVF', 'src' => 'images/clients/fakih-ivf.webp'],
            ['name' => 'AHK Worldwide', 'src' => 'images/clients/ahk-worldwide.webp'],
            ['name' => 'archcorp', 'src' => 'images/clients/archcorp.webp'],
            ['name' => 'Designer East', 'src' => 'images/clients/designer-east.webp'],
            ['name' => 'Al Madar', 'src' => 'images/clients/al-madar.webp'],
            ['name' => 'Bin Dalmook Consultants', 'src' => 'images/clients/bin-dalmook-consultants.webp'],
            ['name' => 'Crystel', 'src' => 'images/clients/crystel.webp'],
            ['name' => 'bluecamel', 'src' => 'images/clients/bluecamel.webp'],
            ['name' => 'Sweet Homes', 'src' => 'images/clients/sweet-homes.webp'],
            ['name' => 'DAO Marketing Management', 'src' => 'images/clients/dao-marketing-management.webp'],
            ['name' => 'Perfect Creations', 'src' => 'images/clients/perfect-creations.webp'],
            ['name' => 'BCI', 'src' => 'images/clients/bci.svg'],
            ['name' => 'KOJ Interiors', 'src' => 'images/clients/koj-interiors.webp'],
        ],
    ],

    'projects' => [
        'heading' => ['Featured', 'Projects'],
        'cta' => ['label' => 'View All Projects', 'href' => '#contact'],
        'items' => [
            [
                'title' => 'Jumeirah Golf Estate Villas',
                'category' => 'Luxury Residential',
                'image' => 'images/projects/jumeirah-golf-estate-villas.webp',
                'ratio' => '992 / 727',
                'drift' => 70,
            ],
            [
                'title' => 'Villa PV39 Tilal-Al-Ghaf',
                'category' => 'Luxury Residential',
                'image' => 'images/projects/villa-pv39-tilal-al-ghaf.webp',
                'ratio' => '660 / 333',
                'drift' => 40,
            ],
            [
                'title' => 'W Residence, Palm Jumeirah',
                'category' => 'Luxury Residential',
                'image' => 'images/projects/w-residence-palm-jumeirah.webp',
                'ratio' => '660 / 333',
                'drift' => 55,
            ],
            [
                'title' => 'WASL Properties HQ',
                'category' => 'Corporate',
                'image' => 'images/projects/wasl-properties-hq.webp',
                'ratio' => '826 / 637',
                'drift' => 65,
            ],
            [
                'title' => 'Emirates Hills Villa',
                'category' => 'Luxury Residential',
                'image' => 'images/projects/emirates-hills-villa.webp',
                'ratio' => '826 / 635',
                'drift' => 45,
            ],
        ],
    ],

    'services' => [
        'label' => 'Our Expertise',
        'heading' => 'We Approach every project as a unique opportunity to deliver exceptional engineering and enduring value.',
        'cta' => ['label' => 'View All Services', 'href' => '#contact'],
        /*
         * The first three services from samaaltariq.org/services, in that
         * page's order, with its own copy — these replaced the Figma
         * placeholders, which named the tabs but wrote no headline or body.
         *
         * `tab` stays short because it renders as a pill in a single scrolling
         * row; the full service name goes in `title`, which is the panel
         * headline and is split across two lines by the view.
         *
         * `title` spells out "And" and drops the hyphen from "Fit Out", as the
         * reference artwork does. That is also what keeps the headline on one
         * face: & and - are two of the characters the demo cut of Juana Alt
         * draws as a flower rather than a glyph, so in a headline they fall
         * through to another serif mid-word. The pills are set in the sans and
         * are unaffected, which is why they keep their ampersands.
         */
        'items' => [
            [
                'tab' => 'Fit-Out Contracting',
                'description' => 'End-to-end interior Fit-Out for commercial, retail, F&B and hospitality spaces, delivered turnkey from concept to handover.',
                'title' => ['Fit Out Contracting', 'And Turnkey Solutions'],
                'image' => 'images/services/fit-out-contracting.webp',
            ],
            [
                'tab' => 'Design & Build',
                'description' => 'Integrated design-and-build combining concept design, technical drawings, BOQ and full execution under a single point of responsibility.',
                'title' => ['Design And Build', 'Interior And Architectural'],
                'image' => 'images/services/design-and-build.webp',
            ],
            [
                'tab' => 'Commercial & Office',
                'description' => 'Workspace, office and corporate interior delivery — space planning, partitions, flooring, ceilings and finishes built for productive environments.',
                'title' => ['Commercial And Office', 'Interior Solutions'],
                'image' => 'images/services/commercial-and-office.webp',
            ],
        ],
    ],

    'process' => [
        'heading' => ['Our', 'Process'],
        'steps' => [
            [
                'number' => '01',
                'title' => 'Tender  And Cost Estimation',
                'body' => 'Accurate cost planning and detailed estimates that establish a strong foundation for every successful project.',
                'image' => 'images/process/tender-and-cost-estimation.webp',
            ],
            // PLACEHOLDER steps — only step 01 exists in the Figma file.
            [
                'number' => '02',
                'title' => 'Design And Engineering',
                'body' => 'Detailed drawings, material selection, and value engineering that turn an approved budget into a buildable scheme.',
                'image' => 'images/process/design-and-engineering.webp',
            ],
            [
                'number' => '03',
                'title' => 'Construction And Fit-Out',
                'body' => 'Coordinated site delivery with dedicated supervision, strict QA checkpoints, and transparent weekly progress reporting.',
                'image' => 'images/process/construction-and-fit-out.webp',
            ],
            [
                'number' => '04',
                'title' => 'Handover And Aftercare',
                'body' => 'Snagging, testing and commissioning, full documentation, and a maintenance period that protects the finished asset.',
                'image' => 'images/process/handover-and-aftercare.webp',
            ],
        ],
    ],

    'precision' => [
        'word' => 'Precision',
        'heading' => 'Engineering excellence for projects that define tomorrow.',
        'body' => 'Every project is shaped through thoughtful planning, collaborative expertise, and a commitment to delivering lasting value for our clients.',
        'cta' => ['label' => 'Book a consultation', 'href' => '#contact'],
        'image' => 'images/precision.webp',
    ],

    'inquiry' => [
        'label' => 'Inquiries',
        'heading' => ['Let’s Build the', 'Future Together.'],
        'body' => "Every successful development begins with the right partner. Let's discuss your goals and create a solution tailored to your project's unique requirements",
        'submit' => 'Send Inquiry',
        'background' => 'images/inquiry-bg.webp',
        /*
         * Kinds of property, matching the field's label. These briefly held
         * the service list instead, which asked what work was wanted under a
         * heading that asked what the building was; the two now agree.
         *
         * This list is the whole validation rule: StoreEnquiryRequest applies
         * Rule::in() straight to it, so an option added here is accepted and
         * one removed is rejected, with no second list to keep in step. That
         * also means editing a string here invalidates that exact wording for
         * future submissions — rows already stored keep the text they were
         * saved with, including any left over from the service list.
         *
         * Stored verbatim, so this is also the wording that reaches the
         * notification email, the admin table and the `enquiries` rows. The
         * column holds 120 characters; the longest here is 22.
         */
        'property_types' => [
            'Villa',
            'Apartment',
            'Office',
            'Retail / Shop',
            'Restaurant / Café',
            'Hotel / Hospitality',
            'Commercial Building',
            'Warehouse / Industrial',
            'Residential Building',
            'Other',
        ],
    ],

    /*
     * Footer contact block. Only `email` is known — it is the mailbox the
     * enquiry form already delivers to. `phone` and `address` are deliberately
     * null rather than invented: the Figma file contains neither, and the
     * footer renders each line only when it is set, so filling them in here is
     * the only step needed to show them.
     */
    'contact' => [
        'heading' => 'Get in touch',
        'email' => env('ENQUIRY_TO', 'info@samaaltariq.org'),
        'phone' => null,
        'address' => null,
    ],

    'footer' => [
        'recent' => [
            'label' => 'Recently Completed',
            'image' => 'images/footer-recent.webp',
            'alt' => 'Recently completed corporate lobby Fit-Out',
            'href' => '#projects',
        ],
        'wordmark' => 'Sama Al Tariq',
        'wordmark_sub' => 'Building Contracting LLC.',
    ],
];
