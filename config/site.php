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
        ['label' => 'Services', 'href' => '/services'],
        ['label' => 'Joinery', 'href' => '/joinery'],
        ['label' => 'Process', 'href' => '/process'],
        ['label' => 'Contact', 'href' => '/contact'],
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

    /*
     * The company profile, offered from a button fixed on every marketing
     * page — the same one samaaltariq.org carries, and deliberately at the
     * same URL: the file has been handed out and linked to under that name,
     * so anything already pointing at it keeps working when this site takes
     * the domain over.
     *
     * It sits at the root of public/, not under images/ — the redeploy copies
     * public's own top-level files into the web root, so a file there is one
     * `git pull` away from being live.
     */
    'profile' => [
        'file' => 'sama-al-tariq-business-profile-2026.pdf',
        'label' => 'Download Profile',
        'aria' => 'Download the Sama Al Tariq business profile',
    ],

    'hero' => [
        // Broken by hand, as the frame breaks it: its eyebrow is a 170 box two
        // lines deep, and "Delivering Quality" is the only pair of words that
        // fits one of them. Left to the browser at the width the line needs,
        // the year falls to a third line on its own.
        'eyebrow' => ['Delivering Quality', 'Since 2023'],
        'intro' => 'We deliver exceptional construction, engineering, and contracting solutions that shape modern communities through innovation and uncompromising standards.',
        'cta' => ['label' => 'Explore Projects', 'href' => '#projects'],
        'words' => ['first' => 'Building', 'second' => 'With Precision', 'third' => 'Future'],
        'image' => 'images/hero.webp',
        // The frame's own photograph, taken from the hero's image fill.
        'alt' => 'Double-height living room opening onto a beach and the Dubai skyline',
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
            // 'Who', not 'Why' — the same label the landing page's about
            // band carries, and the only place the two disagreed.
            'label' => 'Who We Are',
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
            'alt' => 'Living room with a red and black artwork spanning the panelled wall',
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
            /*
             * Residential, redrawn again: the frame no longer runs three even
             * rows of two. It opens and closes on the 772x635 pair and puts
             * the Commercial group's own arrangement in the middle — two
             * 620x332 tiles stacked against one 924x727 — which is what gives
             * the block its break in rhythm.
             *
             *   772 Jumeirah Golf Estate Villas   772 Villa B200
             *   620 W Residence                   924 WASL Properties HQ
             *   620 Villa PV39
             *   772 Jumeirah Island Villa         772 Emirates Hills Villa
             *
             * Two things moved. WASL Properties HQ is here on the dark lounge
             * rather than the staircase — the same project as the Corporate
             * tile below, labelled Corporate here too, which is what the file
             * draws; `project` sends both to the one page. And JUMEIRAH ISLAND
             * VILLA IS BACK ON THE GRID, on the dressing-room joinery rather
             * than the exterior its old cover carried. Its page kept answering
             * on facts of its own while it was off the grid (see
             * project_pages); those facts and these now say the same thing.
             *
             * The two wide tiles are their own files rather than the covers
             * they were cut beside: 620x332 is a different crop from 772x635,
             * and the related rows on the project pages still draw the square
             * one.
             */
            [
                'name' => 'Residential',
                'rows' => [
                    ['columns' => [
                        ['fr' => 772, 'tiles' => [
                            ['image' => 'jumeirah-golf-estate-villas', 'ratio' => '772/635', 'title' => 'Jumeirah Golf Estate Villas', 'category' => 'Luxury Residential', 'location' => 'Jumeirah Golf Estate, Dubai', 'size' => '18,000 Sq Ft', 'duration' => '8 Months'],
                        ]],
                        ['fr' => 772, 'tiles' => [
                            ['image' => 'villa-b200-tilal-al-ghaf', 'ratio' => '772/635', 'title' => 'Villa B200, Tilal-Al-Ghaf', 'category' => 'Luxury Residential', 'location' => 'Tilal-Al-Ghaf, Dubai'],
                        ]],
                    ]],
                    ['columns' => [
                        ['fr' => 620, 'tiles' => [
                            ['image' => 'w-residence-palm-jumeirah-wide', 'project' => 'w-residence-palm-jumeirah', 'ratio' => '620/332', 'title' => 'W Residence, Palm Jumeirah', 'category' => 'Luxury Residential', 'location' => 'Palm Jumeirah, Dubai', 'size' => '6,500 Sq Ft', 'duration' => '3 Months'],
                            ['image' => 'villa-pv39-tilal-al-ghaf-wide', 'project' => 'villa-pv39-tilal-al-ghaf', 'ratio' => '620/332', 'title' => 'Villa PV39, Tilal-Al-Ghaf', 'category' => 'Luxury Residential', 'location' => 'Tilal-Al-Ghaf, Dubai', 'size' => '8,000 Sq Ft', 'duration' => '6 Months'],
                        ]],
                        ['fr' => 924, 'tiles' => [
                            ['image' => 'wasl-properties-hq-2', 'project' => 'wasl-properties-hq', 'ratio' => '924/727', 'title' => 'WASL Properties HQ', 'category' => 'Corporate', 'location' => 'Sheikh Zayed Road, Dubai', 'size' => '45,000 Sq Ft', 'duration' => '4 Months'],
                        ]],
                    ]],
                    ['columns' => [
                        ['fr' => 772, 'tiles' => [
                            ['image' => 'jumeirah-island-villa', 'ratio' => '772/635', 'title' => 'Jumeirah Island Villa', 'category' => 'Luxury Residential', 'location' => 'Jumeirah Island, Dubai', 'size' => '14,000+ Sq Ft', 'duration' => '8 Months'],
                        ]],
                        ['fr' => 772, 'tiles' => [
                            ['image' => 'emirates-hills-villa', 'ratio' => '772/635', 'title' => 'Emirates Hills Villa', 'category' => 'Luxury Residential', 'location' => 'Emirates Hills, Dubai', 'size' => '30,000 Sq Ft', 'duration' => '12 Months'],
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
                /*
                 * 1443:1134 — the frame draws this row twice, the same project
                 * on two photographs, and it read as two gyms: one caption
                 * under each, both saying Fidelity Gym, JLT. It is named once
                 * now.
                 *
                 * The second column stays, holding no tile. The row's tracks
                 * are built from these `fr` figures, so dropping the column
                 * would leave one track and stretch the picture across the
                 * whole measure — 1568 wide on a 772x727 crop, half again as
                 * tall as any other tile on the page. Empty, it keeps the
                 * photograph at the size the frame draws it.
                 */
                'name' => 'Fitness & Spa',
                'rows' => [
                    ['columns' => [
                        ['fr' => 772, 'tiles' => [
                            ['image' => 'fidelity-gym-jlt', 'ratio' => '772/727', 'title' => 'Fidelity Gym, JLT', 'category' => 'Fitness & Spa', 'location' => 'Jumeirah Lake Towers, Dubai', 'size' => '25,425 Sq Ft', 'duration' => '6 Months'],
                        ]],
                        ['fr' => 772, 'tiles' => []],
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
        /*
         * Off the projects grid since the frame's Residential group was
         * redrawn, so its facts live here — without them this page would have
         * nowhere to read a title from and would 404, and the four projects
         * that list it as related would quietly drop it.
         */
        'jumeirah-island-villa' => [
            'facts' => [
                'title' => 'Jumeirah Island Villa',
                'category' => 'Luxury Residential',
                'location' => 'Jumeirah Island, Dubai',
                'size' => '14,000+ Sq Ft',
                'duration' => '8 Months',
            ],
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
        /*
         * Villa B200. This entry was written before the photographs existed:
         * the page answered, and every picture on it 404'd. It has its twelve
         * now, and a pair of tiles on the Residential grid above.
         *
         * Twelve, not the thirteen handed over. The one left out carries
         * another company's watermark across the terrace wall — ARC IN SPACE,
         * bottom left — which is not something to publish here. A clean frame
         * dropped in as l13/g13 is picked up by the grid with no edit to this
         * file.
         */
        'villa-b200-tilal-al-ghaf' => [
            // Kept here as well as on the tile: area, duration and year are
            // unknown to this site and their rows stay closed.
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

        /*
         * `title` is broken where the frame breaks it — the first is two lines
         * by a hard return in the file, the rest wrap in their 653 measure.
         */
        'phases' => [
            [
                'label' => 'Phase 01',
                'title' => ['Tender And', 'Cost Estimation'],
                'lead' => 'A structured start built on accuracy, clarity and confidence from the first step.',
                'image' => 'images/process/phase-1.webp', 'ratio' => '1200 / 867',
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
                'image' => 'images/process/phase-2.webp', 'ratio' => '1200 / 900',
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
                'image' => 'images/process/phase-3.webp', 'ratio' => '1200 / 867',
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
                'image' => 'images/process/phase-4.webp', 'ratio' => '1200 / 867',
                'items' => [
                    ['title' => 'Snag & De-Snag', 'body' => 'Internal snag walks and joint inspections close every punch-list item before sign-off.'],
                    ['title' => 'Test & Commission', 'body' => 'System testing, functional checks and authority approvals confirm performance.'],
                    ['title' => 'As-Builts & O&M', 'body' => 'As-built drawings, O&M manuals and material warranties handed over in full.'],
                    ['title' => 'Handover & DLP Support', 'body' => 'Taking-over certificate, defects rectification and ongoing support through the DLP.'],
                ],
            ],
        ],

        /*
         * 128 Juana Alt SemiBold over the photograph, as the frame closes —
         * and, once the picture has opened out to the screen, the invitation
         * that stands on it.
         */
        'consultation' => [
            'word' => 'Consultation',
            'image' => 'images/process/consultation.webp',
            'cta' => [
                'lines' => ['Have a vision to build?', "We're here to help."],
                'label' => "Let's Talk",
                'href' => '#contact',
            ],
        ],
    ],

    /*
     * The Joinery page.
     *
     * Laid out after homekode.com/pages/interior-design-services, which the
     * client asked this page to follow. What was taken is that page's
     * ARRANGEMENT, not its brand: a centred serif slab over centred copy, a
     * half-and-half split of picture against a solid panel, a row of three
     * upright pictures under a ruled heading, a card floating on a dark band
     * carrying a list and one button, a picture beside a block of copy, a
     * centred question-and-answer stack ruled between each pair, and two
     * large pictures to close. Their cream and forest green are theirs; this
     * page is drawn in the palette the other eight pages use, because a
     * ninth in somebody else's colours reads as a different site.
     *
     * The content rule from the first build still holds. Everything here is
     * either the relationship the client stated, or the joinery scope this
     * site already sets out on the services page. Nothing claims anything
     * about Alwan as a company: their site answered 404 on every variant and
     * no public record confirmed a founding year, an address or a workshop,
     * so those slots are null and the page omits them. Fill one in and it
     * draws.
     */
    'joinery_page' => [
        'partner' => [
            'name' => 'Alwan Design',
            'descriptor' => 'Interior Design & Decor',

            /*
             * THE FIRST PICTURE ON THE PAGE, which is what was asked for: the
             * split under the title gives its left half to this mark.
             *
             * The artwork is 700x256 — the file that was already sitting
             * untracked in public/images. There is no white margin to trim;
             * it fills its own frame. That is not much resolution for a mark
             * this prominent, so the half draws it at 374 rather than the 461
             * it had room for: 1.87 source pixels per CSS pixel instead of
             * 1.52, which is the difference between soft and acceptable on a
             * retina screen. A vector or a 1400-wide export would let it run
             * at the larger size.
             *
             * If it is ever replaced, both this path and the file change
             * together — SiteAssetsTest fails a config path with no file
             * behind it, which is the guard that keeps a 404 off the host.
             */
            'logo' => 'images/partners/alwan-design.webp',

            // 404 on every variant at the time of writing. A link to a dead
            // page is worse than no link, so it draws only once filled in.
            'website' => null,

            // Unknown rather than absent — see the note above.
            'established' => null,
            'location' => null,
            'workshop' => null,
        ],

        'hero' => [
            'heading' => 'Joinery',
            'lead' => 'The interiors and joinery on a Sama Al Tariq project are made and fitted by Alwan Design.',
            'body' => 'One partner carries the interior package from the set-out drawings through to the last fitted element — so the joinery, the stone and the ceilings are drawn against each other before any of them is made, rather than resolved on site between three trades.',
            'panel' => [
                'heading' => 'One Interior, One Maker',
                'body' => 'They make and fit the work. We hold the programme, the site and the client. One party is responsible for the interior from first drawing to handover.',
            ],
        ],

        /*
         * The ruled heading and the three upright pictures. Two of them are
         * the services page's own joinery entries, quoted by number rather
         * than described again, so editing a service edits this too.
         */
        'scope' => [
            'heading' => 'Made in a workshop, finished on site',
            'body' => 'Two of the ten services on this site are joinery, and both are delivered through Alwan — the bespoke work, and the carpentry and millwork that installs it.',
            'numbers' => ['05', '06'],
            'third' => ['src' => 'images/about/approach-joinery.webp', 'alt' => 'A finished timber joinery wall with a lit reveal'],
        ],

        /*
         * The card on the dark band. Their card carries a price; this one
         * carries scope, because what a joinery package costs is a function
         * of the drawings and is quoted per project — a figure here would be
         * one nobody could stand behind.
         */
        'package' => [
            'title' => 'The Joinery Package',
            'meta' => 'Drawing → workshop → site',
            'items' => [
                'Set-out drawings, agreed before anything is cut',
                'Material and finish schedule',
                'Bespoke joinery — feature elements and fitted furniture',
                'Fully integrated interior components',
                'Carpentry and millwork installation',
                'Coordination against stone, ceilings and services',
                'Snagging and handover',
            ],
            'summary' => 'Quoted per project',
            'note' => 'Scope is set against the drawings for each project rather than sold by the metre, so the package is priced once the set-out is agreed.',
            'cta' => ['label' => 'Start An Enquiry', 'href' => '/contact'],
        ],

        // Picture beside copy, as their studio band is arranged.
        'studio' => [
            'heading' => 'Why one package rather than three trades',
            'body' => [
                'A finish that meets cleanly was set out that way in advance. Where the joinery, the stone and the ceilings are drawn by different parties, the seams between them get resolved on site — late, and in whatever order the trades arrive.',
                'Held as one package, the set-out is agreed once and everything after it is made to that drawing. It is the same reason a short programme runs: the long-lead work is ordered against a set-out nobody has to renegotiate.',
            ],
            // A project rather than the workshop frame above it: service-5
            // is already the first of the three upright pictures, and the
            // same photograph twice on one page reads as a shortage of them.
            // This is the fitted kitchen at Jumeirah Golf Estate — the thing
            // the paragraph beside it is describing.
            'image' => ['src' => 'images/projects/jumeirah-golf-estate-villas/l10.webp', 'alt' => 'A fitted kitchen with an island and lit joinery reveals'],
            'cta' => ['label' => 'Our Process', 'href' => '/process'],
        ],

        'faqs' => [
            [
                'q' => 'Who makes the joinery?',
                'a' => 'Alwan Design. Every interior and joinery package on this site is made and fitted by them, working to the set-out agreed at the start of the project.',
            ],
            [
                'q' => 'Who is responsible on site?',
                'a' => 'Sama Al Tariq. We hold the programme, the site and the client, so there is one party accountable for the interior rather than a chain of subcontracts to follow.',
            ],
            [
                'q' => 'What does the joinery package cover?',
                'a' => 'The bespoke work and the carpentry and millwork that installs it — services 05 and 06 on the services page — from the set-out drawings through to snagging and handover.',
            ],
            [
                'q' => 'Can I see the work?',
                'a' => 'Yes. The projects below carry it, and every project on the projects page was delivered with the same interior package.',
            ],
        ],

        /*
         * Two large pictures to close, as their inspiration band does. Slugs
         * only: the cover and the title are the projects grid's, looked up
         * rather than copied, so a project that is re-shot does not leave
         * this page quoting the old one.
         */
        'gallery' => [
            'heading' => 'Where it shows',
            'projects' => ['jumeirah-golf-estate-villas', 'benjarong-dusit-thani'],
        ],

        'cta' => [
            ['label' => 'All Services', 'href' => '/services'],
            ['label' => 'Explore Projects', 'href' => '/projects'],
        ],
    ],

    /*
     * The Services page, from Figma frame 1545:2 ("Services", 1728x10847).
     * Copy is the frame's own, verbatim.
     *
     * The page runs: a photographic hero with its sentence broken across two
     * lines set at different indents; an opening block of label, statement and
     * a two-column note; ten services, each a number, a 600x640 picture and a
     * block of title, lead and copy; then the same consultation band the
     * process page closes with, which is the same frame in both files.
     *
     * `title` is broken where the frame breaks it. Several of them wrap in
     * their own 535 measure rather than at a hard return, so only the ones the
     * file actually splits carry two entries.
     */
    'services_page' => [
        'hero' => [
            // Two lines at different indents, 108 Juana Alt over the picture.
            'lines' => ['From vision', 'To spaces built to endure'],
            'image' => 'images/services/hero.webp',
            // The drawing the photograph is revealed over. Made from the
            // photograph itself, the frame having only the one picture.
            'outline' => 'images/services/hero-outline.webp',
        ],

        'intro' => [
            'label' => 'Our Expertise',
            'statement' => 'Expertise that brings every part of the build together.',
            'note' => ['Multiple', 'disciplines, one vision.'],
            'body' => 'From construction and fit-out to specialist joinery and MEP, we bring the people, disciplines and capabilities required to deliver complex environments as one coordinated team.',
        ],

        'services' => [
            [
                'number' => '01', 'title' => ['Fit-Out Contracting'], 'image' => 'images/services/service-1.webp',
                'lead' => 'From shell to finished environment.',
                'body' => 'Complete interior fit-out solutions for commercial, retail, F&B, hospitality and residential environments, coordinated from preparation through final delivery.',
            ],
            [
                'number' => '02', 'title' => ['Design', 'AND Build'], 'image' => 'images/services/service-2.webp',
                'lead' => 'One vision. One coordinated delivery.',
                'body' => 'Bringing design development and construction expertise together to create practical, cohesive environments aligned with the project\'s objectives and requirements.',
            ],
            [
                'number' => '03', 'title' => ['Commercial AND Office Interiors'], 'image' => 'images/services/service-3.webp',
                'lead' => 'Workspaces shaped around modern business.',
                'body' => 'Bringing design development and construction expertise together to create practical, cohesive environments aligned with the project\'s objectives and requirements.',
            ],
            [
                'number' => '04', 'title' => ['Villa Renovation AND Refurbishment'], 'image' => 'images/services/service-4.webp',
                'lead' => 'Reimagining spaces with considered detail.',
                'body' => 'Comprehensive renovation and refurbishment for villas and private residences, transforming existing environments while respecting their character and improving their performance.',
            ],
            [
                'number' => '05', 'title' => ['Bespoke Joinery'], 'image' => 'images/services/service-5.webp',
                'lead' => 'Details made specifically for the space.',
                'body' => 'Custom joinery created to complement architectural intent, material palettes and functional requirements—from feature elements to fully integrated interior components.',
            ],
            [
                'number' => '06', 'title' => ['Joinery, Carpentry AND Millwork'], 'image' => 'images/services/service-6.webp',
                'lead' => 'Crafted elements that complete the environment.',
                'body' => 'Specialist carpentry and millwork installation delivered with careful coordination across architectural details, finishes and site requirements.',
            ],
            [
                'number' => '07', 'title' => ['Gypsum, Ceilings AND Partitions'], 'image' => 'images/services/service-7.webp',
                'lead' => 'The framework behind the finished interior.',
                'body' => 'Precision installation of ceilings, bulkheads and partitions to establish the structure, proportions and technical requirements of each interior environment.',
            ],
            [
                'number' => '08', 'title' => ['MEP Services'], 'image' => 'images/services/service-8.webp',
                'lead' => 'The systems that make spaces perform.',
                'body' => 'Integrated mechanical, electrical and plumbing services coordinated with the wider build to support comfort, functionality, efficiency and long-term performance.',
            ],
            [
                'number' => '09', 'title' => ['Authority AND Departmental Approvals'], 'image' => 'images/services/service-9.webp',
                'lead' => 'Keeping complexity under control.',
                'body' => 'Managing submissions, permits, NOCs, and regulatory approvals with the relevant Dubai and UAE authorities.',
            ],
            [
                // The one band whose title box the frame widens: 589 of the 602
                // column rather than the 535 the other nine share, which is
                // what keeps this title on two lines instead of three.
                'number' => '10', 'title' => ['Project Management AND Coordination'], 'title_box' => '97.84%',
                'image' => 'images/services/service-10.webp',
                'lead' => 'Keeping complexity under control.',
                'body' => 'Dedicated project leadership connecting consultants, suppliers, trades and site teams around a clear programme, coordinated decisions and defined responsibilities.',
            ],
        ],
    ],

    /*
     * The Contact page, from Figma frame 1594:1608.
     *
     * The page the enquiry card has always been a shorter version of: the same
     * form, given room, with the ways of reaching the office listed beside it.
     * The form itself is the shared component rather than a second copy —
     * see resources/views/components/enquiry-form.blade.php.
     *
     * `details` are rows on their own hairlines at the foot of the left
     * column. Phone and address are published here for the first time; the
     * email is the one the site already carries.
     */
    'contact_page' => [
        'heading' => ["Let's Shape", 'Your Vision'],
        'label' => 'Speak to our team',
        'body' => "Have a project in mind? Tell us what you're building and let's explore how our expertise can bring it to life.",
        'details' => [
            ['label' => 'Telephone', 'value' => '+971 54 319 0845', 'href' => 'tel:+971543190845'],
            ['label' => 'Email', 'value' => 'info@samaaltariq.org', 'href' => 'mailto:info@samaaltariq.org'],
            ['label' => 'Office', 'value' => 'Office 804, Sapphire Tower Dubai, United Arab Emirates', 'href' => null],
        ],
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

            /*
             * The materials and consultancy marks, added from the client's own
             * folder. Two of them — GBM and Jotun — are drawn on a coloured
             * tile rather than clear, so they arrive with their own background
             * where the rest are transparent; the row greyscales everything at
             * rest, which is what keeps them from shouting over the others.
             *
             * GYPSEMNA as the mark spells it, not "Gypsumna" as the file was
             * named.
             */
            ['name' => 'ArchSmith', 'src' => 'images/clients/archsmith.webp'],
            ['name' => 'Caparol', 'src' => 'images/clients/caparol.webp'],
            ['name' => 'DATCO', 'src' => 'images/clients/datco.webp'],
            ['name' => 'GBM', 'src' => 'images/clients/gbm.webp'],
            ['name' => 'GYPSEMNA', 'src' => 'images/clients/gypsemna.webp'],
            ['name' => 'Jotun', 'src' => 'images/clients/jotun.webp'],
        ],
    ],

    'projects' => [
        'heading' => ['Featured', 'Projects'],
        // The projects page, not the enquiry form: the button says what it
        // does and had been pointing at #contact, so it scrolled a visitor
        // past the projects it was offering to show them.
        'cta' => ['label' => 'View All Projects', 'href' => '/projects'],
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
        // The services page, for the same reason the projects band's button
        // goes to its own: the label offers the rest of the set, and #contact
        // took the visitor to the enquiry form instead.
        'cta' => ['label' => 'View All Services', 'href' => '/services'],
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

    /*
     * The four steps carry their pictures' own proportions rather than a frame
     * of their own. Cut to a frame, a photograph of a site loses the half of it
     * that says where the work is — the drawings on the table, or the house
     * behind them — so the frames are the pictures here, and the two ratios the
     * set has (1200x1110 and 1200x900) are what the blocks take.
     */
    'process' => [
        'heading' => ['Our', 'Process'],
        // The way out of the section, as the frame draws it: the same pill the
        // projects and services bands close with, pointing at the page that
        // carries the four phases in full.
        'cta' => ['label' => 'See Our Process', 'href' => '/process'],
        'steps' => [
            [
                'number' => '01',
                'title' => 'Tender And Cost Estimation',
                'body' => 'Accurate cost planning and detailed estimates that establish a strong foundation for every successful project.',
                'image' => 'images/process/tender-and-cost-estimation.webp', 'ratio' => '1200 / 867',
            ],
            // PLACEHOLDER steps — only step 01 exists in the Figma file.
            [
                'number' => '02',
                'title' => 'Design And Engineering',
                'body' => 'Detailed drawings, material selection, and value engineering that turn an approved budget into a buildable scheme.',
                'image' => 'images/process/design-and-engineering.webp', 'ratio' => '1200 / 900',
            ],
            [
                'number' => '03',
                'title' => 'Construction And Fit-Out',
                'body' => 'Coordinated site delivery with dedicated supervision, strict QA checkpoints, and transparent weekly progress reporting.',
                'image' => 'images/process/construction-and-fit-out.webp', 'ratio' => '1200 / 867',
            ],
            [
                'number' => '04',
                'title' => 'Handover And Aftercare',
                'body' => 'Snagging, testing and commissioning, full documentation, and a maintenance period that protects the finished asset.',
                'image' => 'images/process/handover-and-aftercare.webp', 'ratio' => '1200 / 867',
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
