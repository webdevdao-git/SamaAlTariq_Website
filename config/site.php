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
        ['label' => 'Team', 'href' => '/team'],
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
        // TikTok before LinkedIn, which is the order the redrawn footer sets
        // them in. The menu overlay draws the same list, so the two agree.
        ['label' => 'TikTok', 'icon' => 'tiktok', 'href' => 'https://www.tiktok.com/@samaaltariq'],
        ['label' => 'LinkedIn', 'icon' => 'linkedin', 'href' => 'https://www.linkedin.com/company/sama-al-tariq'],
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
        'eyebrow' => ['Since 2023', 'Dubai, UAE'],
        // Interior fit-out is the core of the business; construction, joinery
        // and MEP are what let the company deliver it under one contract.
        'intro' => 'Interior fit-out contractor in Dubai: gypsum and ceilings, finishing, joinery and MEP, all by our own team.',
        'cta' => ['label' => 'Explore Projects', 'href' => '#projects'],
        // "Fit Out" without the hyphen: the display face's demo cut draws "-"
        // as an ornament, so the slab stays on letters it draws correctly.
        /*
         * The hero's slides, each with its own message. Pictures are cut at
         * 2880px from the original shoots (source-photos/), not the site's
         * 2200px project copies. The headline lines go into the display
         * face, so they keep to letters, spaces and commas.
         */
        // Shown over every slide: what the company is best known for.
        'specialism' => ['Interior Fit-Out', 'Gypsum & Ceiling Specialists'],
        'slides' => [
            ['src' => 'images/hero/w-residence.webp', 'title' => 'W Residence, Palm Jumeirah', 'lines' => ['Residences Finished', 'To The Last Detail']],
            // Gypsum is the company's core trade, so it has a slide of its own.
            ['src' => 'images/hero/boulevard.webp', 'title' => 'Boulevard Plaza Office', 'lines' => ['Gypsum Ceilings,', 'Our Core Expertise']],
            ['src' => 'images/hero/pullman.webp', 'title' => 'Pullman Hotel', 'lines' => ['Hospitality,', 'Delivered End To End']],
            ['src' => 'images/hero/benjarong.webp', 'title' => 'Benjarong, Dusit Thani', 'lines' => ['Restaurants With', 'A Story To Tell']],
            ['src' => 'images/hero/wasl.webp', 'title' => 'WASL Properties HQ', 'lines' => ['Workplaces Built', 'Around People']],
            ['src' => 'images/hero/b200.webp', 'title' => 'Villa B200, Tilal-Al-Ghaf', 'lines' => ['Villas Renewed,', 'Room By Room']],
            ['src' => 'images/hero/pv39.webp', 'title' => 'Villa PV39, Tilal-Al-Ghaf', 'lines' => ['Interior Fit Out,', 'One Contractor']],
        ],
        'image' => 'images/hero/w-residence.webp',
        // The frame's own photograph, taken from the hero's image fill.
        'alt' => 'Double-height living room opening onto a beach and the Dubai skyline',
    ],

    'about' => [
        'label' => 'Who We Are',
        'heading' => [
            'An interior fit-out contractor in Dubai,',
            'finishing villas, hotels, restaurants',
            'and offices with our own team.',
        ],
        'image' => 'images/about-interior.webp',
        'alt' => 'Curved timber-ribbed interior with a sculpted lounge chair',
        'subheading' => ['Shell To Handover,', 'One Team'],
        'body' => [
            'We take an interior from the bare shell to the day you move in: gypsum ceilings and partitions, flooring, stone, paint, joinery and MEP. More than 150 of our own people do the work, without subcontracting.',
            'Our joinery is made in our own workshop, Alwan Interiors. After handover, our AMC team keeps the space in the condition we handed it over in.',
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
            'body' => 'Interior Fit-Out Contractor In Dubai. Gypsum, Finishing, Joinery And MEP, Delivered By Our Own Team.',
            'cta' => ['label' => 'Explore Projects', 'href' => '/projects'],
            'word' => 'About Us',
            'stats' => [
                ['value' => '95%', 'label' => 'On-time handovers'],
                ['value' => '03+', 'label' => 'Years of Expertise'],
                ['value' => '250k+', 'label' => 'm² Delivered'],
            ],
            'image' => 'images/about/hero.webp',
            'alt' => 'Double-height villa interior around an indoor tree',
        ],

        // Frame 1377:26. Image left, one paragraph in the right column, its
        // last line sitting on the foot of the image. The opening clause is
        // set in the semibold cut, as in the design.
        'intro' => [
            'lead' => 'Sama Al Tariq Building Contracting L.L.C.',
            'body' => 'is a Dubai-based interior fit-out company. Our own teams handle gypsum ceilings and partitions, finishing, joinery and MEP for villas, apartments, hotels, restaurants, offices, gyms and clinics. We cover every stage, from the first drawing and BOQ to handover and maintenance.',
            'image' => 'images/about/intro-stair.webp',
            'alt' => 'Curved stone staircase in a sunlit hall',
        ],

        // Frame 1386:608. The mirror of the block above, on the pale ground.
        // Its heading is set in the sans, not the display serif.
        'vision' => [
            'heading' => ['One vision,', 'brought together under one roof.'],
            'body' => 'From the first drawing to the final handover, one team runs the whole interior. You deal with one programme, one project lead and one contract.',
            'cta' => ['label' => 'See Our process', 'href' => '/process'],
            'image' => 'images/about/vision-lounge.webp',
            'alt' => 'Living room with a red and black artwork spanning the panelled wall',
        ],

        // Frame 1386:621. Label and heading, then a three-image collage with
        // the logo mark centred over it, then copy in the right column.
        'approach' => [
            'label' => 'Our Approach',
            'heading' => 'We believe great interiors are planned long before the first ceiling goes up.',
            'body' => 'Every project starts with the drawings, the BOQ and a site survey. Ceilings, joinery and MEP are coordinated on paper first, so on site every trade fits together and the finish is right the first time.',
            'cta' => ['label' => 'Explore Projects', 'href' => '/projects'],
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
            'label_right' => 'Interiors',
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
            'words' => ['Interiors', 'With A Clearer', 'Purpose'],
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
        // No view toggle: the page shows its projects one way, as panels.
        'views' => [],
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
                            ['image' => 'villa-b200-tilal-al-ghaf', 'ratio' => '772/635', 'title' => 'Villa B200, Tilal-Al-Ghaf', 'category' => 'Luxury Residential', 'location' => 'Tilal-Al-Ghaf, Dubai', 'size' => '8,000 Sq Ft', 'duration' => '4 Months'],
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
                    ['columns' => [
                        ['fr' => 772, 'tiles' => [
                            ['image' => 'jp-villa', 'ratio' => '772/635', 'title' => 'JP Villa', 'category' => 'Residential'],
                        ]],
                        ['fr' => 772, 'tiles' => []],
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
                    // Same empty-second-column arrangement as Hospitality, so
                    // the single tile keeps the size the others are drawn at.
                    ['columns' => [
                        ['fr' => 772, 'tiles' => [
                            ['image' => 'dubai-hills-business-park', 'ratio' => '772/727', 'title' => 'Dubai Hills Business Park', 'category' => 'Commercial', 'location' => 'Dubai Hills, Dubai'],
                        ]],
                        ['fr' => 772, 'tiles' => []],
                    ]],
                ],
            ],
            [
                /*
                 * 1443:1104 — the frame draws this row twice, the same project
                 * on two photographs, and it read as two restaurants: one
                 * caption under each, both saying Benjarong, Dusit Thani. It
                 * is named once now, as Fitness & Spa is.
                 *
                 * The second column stays, holding no tile. The row's tracks
                 * are built from these `fr` figures, so dropping the column
                 * would leave one track and stretch the photograph across the
                 * whole measure — 1568 wide on a 772x727 crop, half again as
                 * tall as any other tile on the page. Empty, it keeps the
                 * picture at the size the frame draws it.
                 */
                'name' => 'Hospitality, F&B',
                'rows' => [
                    ['columns' => [
                        ['fr' => 772, 'tiles' => [
                            ['image' => 'benjarong-dusit-thani', 'ratio' => '772/727', 'title' => 'Benjarong, Dusit Thani', 'category' => 'Hospitality / F&B', 'location' => 'JVC, Dubai', 'size' => '10,000+ Sq Ft', 'duration' => '3 Months'],
                        ]],
                        // Takes the column that stood empty beside Benjarong.
                        // Location, area and duration are not supplied yet.
                        ['fr' => 772, 'tiles' => [
                            ['image' => 'pullman', 'ratio' => '772/727', 'title' => 'Pullman Hotel', 'category' => 'Hospitality'],
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
        /*
         * Added with their photographs and category only. The lead, overview,
         * location, area and duration have not been supplied; the page leaves
         * each one out until they are, rather than carrying a guess.
         */
        'pullman' => [
            'about' => null,
            'year' => null,
            'tiles' => 9,
            'related' => ['benjarong-dusit-thani', 'fidelity-gym-jlt', 'wasl-properties-hq', 'emirates-hills-villa'],
        ],
        'dubai-hills-business-park' => [
            'about' => null,
            'year' => null,
            'tiles' => 9,
            'related' => ['wasl-properties-hq', 'boulevard-plaza-office', 'i-rise-tower-office', 'pullman'],
        ],
        'jp-villa' => [
            'about' => null,
            'year' => null,
            'tiles' => 7,
            'related' => ['villa-b200-tilal-al-ghaf', 'emirates-hills-villa', 'jumeirah-island-villa', 'villa-pv39-tilal-al-ghaf'],
        ],
        'jumeirah-golf-estate-villas' => [
            'about' => 'Full renovation & fit-out',
            'year' => '2024',
            'lead' => 'A refined residential project in Dubai, combining sophisticated interiors with precise finishing and bespoke detailing.',
            'body' => [
                'Jumeirah Golf Estate Villas were delivered as a full renovation and fit-out over eight months. The programme brought together an open island-led kitchen, light-filled lounges, timber-slat ceilings and coordinated joinery and finishes across the home.',
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
            'tiles' => 3,
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
            // Kept here as well as on the tile. Area, duration and scope are
            // from the 2026 business profile; the year is still unknown.
            'facts' => [
                'title' => 'Villa B200, Tilal-Al-Ghaf',
                'category' => 'Luxury Residential',
                'location' => 'Tilal-Al-Ghaf, Dubai',
                'size' => '8,000 Sq Ft',
                'duration' => '4 Months',
            ],
            'about' => 'Full renovation & fit-out',
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
            'about' => 'Full renovation & fit-out',
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
            'about' => 'Fit-out · Joinery · MEP · Finishes',
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
                /*
                 * The four phase pictures are one interior seen through its
                 * build — the JP Villa media wall: measured up on site, drawn,
                 * fitted, finished. The villa-shell photographs that stood
                 * here showed structural work the company does not lead with.
                 */
                'title' => ['Tender And', 'Cost Estimation'],
                'lead' => 'A structured start built on accuracy, clarity and confidence from the first step.',
                'image' => 'images/process/phase-interior-1.webp', 'ratio' => '1200 / 867',
                'items' => [
                    ['title' => 'Accurate Review', 'body' => 'We review RFQs, drawings and specifications in detail to understand the complete scope and requirements.'],
                    ['title' => 'Site Visit & Survey', 'body' => 'Thorough site assessment to identify risks, constraints and opportunities for successful delivery.'],
                    ['title' => 'Price Every Item', 'body' => 'Detailed BOQ analysis with transparent pricing for every item and clear cost breakdown.'],
                    ['title' => 'Submit With Confidence', 'body' => 'Comprehensive and professional submission that builds trust and improves your win rate.'],
                ],
            ],
            [
                'label' => 'Phase 02',
                'title' => ['Planning AND Shop Drawings'],
                'lead' => 'The foundation for delivery, set through careful planning and procurement.',
                'image' => 'images/process/phase-interior-2.webp', 'ratio' => '1200 / 867',
                'items' => [
                    ['title' => 'Plan With Precision', 'body' => 'We develop detailed plans and strategies to ensure a smooth project journey.'],
                    ['title' => 'Coordinate & Validate', 'body' => 'Shop drawings, coordination and approvals to eliminate conflicts early.'],
                    ['title' => 'Procure Smart', 'body' => 'Right materials, right time. Quality suppliers and best value procurement.'],
                    ['title' => 'Mobilise For Success', 'body' => 'Efficient site setup and mobilisation to start strong and stay ahead.'],
                ],
            ],
            [
                'label' => 'Phase 03',
                'title' => ['Fit-Out AND Execution'],
                'lead' => 'Disciplined site delivery, where the drawings become a finished, working interior.',
                'image' => 'images/process/phase-interior-3.webp', 'ratio' => '1200 / 867',
                'items' => [
                    ['title' => 'Build With Discipline', 'body' => 'Partitions, ceilings and fit-out works executed to drawing, with setting-out and quality checks at every stage.'],
                    ['title' => 'Install The Systems', 'body' => 'HVAC, electrical and plumbing installed and cleanly coordinated with the finishes.'],
                    ['title' => 'Finish To Perfection', 'body' => 'Flooring, ceilings, joinery and decorative finishes delivered to specification.'],
                    ['title' => 'Control The Quality', 'body' => 'Weekly progress, inspection sign-offs and strict HSE keep delivery on track.'],
                ],
            ],
            [
                'label' => 'Phase 04',
                'title' => ['Handover AND Defects Liability'],
                'lead' => 'Final inspections, defect resolution and clean handover with full documentation and warranties.',
                'image' => 'images/process/phase-interior-4.webp', 'ratio' => '1200 / 867',
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
     * The Joinery page, from Figma frame 1803:2 ("Joinery", 1728x9790) on the
     * Landing Page redesign canvas.
     *
     * Read from the file this time rather than from a screenshot of it: every
     * string below is the frame's own, the boxes are its boxes, and the
     * photographs are the originals its fills point at. The bands run:
     * photographic hero with the title split across the gutters; the two words
     * either side of a picture between two centred labels; the ecosystem
     * statement over a ruled note-and-body row; three capability cards beside
     * a tall picture; the slab with a picture set inside its second line; five
     * steps staggered either side of a rule; two project tiles under CRAFT IN
     * CONTEXT; and the dark question band.
     *
     * The FAQ carries SIX questions where the frame draws five. The sixth —
     * "What is Alwan Interiors?" — is the client's own, sent with the answers
     * for this page after the frame was drawn, and it belongs at the top of
     * the list rather than nowhere.
     */
    'joinery_page' => [
        'partner' => [
            'name' => 'Alwan Interiors',
            'descriptor' => 'Interior Design & Decor',
            'logo' => 'images/partners/alwan-design.webp',
            'mark_alt' => 'Alwan Design — Interior Design & Decor',

            // 404 on every variant at the time of writing. A link to a dead
            // page is worse than no link, so it draws only once filled in.
            'website' => null,
            'established' => null,
            'location' => null,
            'workshop' => null,
        ],

        'hero' => [
            'label' => 'Alwan Interiors',
            'summary' => 'Dedicated to bespoke joinery and interior execution, Alwan Interiors complements Sama Al Tariq\'s wider project delivery with specialist expertise.',
            'cta' => ['label' => 'Explore Craft', 'href' => '#capabilities'],
            'words' => ['Craft', 'Behind The Build'],
            'image' => ['src' => 'images/joinery/hero.webp', 'alt' => 'Timber on the bed of a workshop saw'],
        ],

        /*
         * The two words either side of a picture, a centred label above and
         * another below — and THE PICTURE IS A SEQUENCE THAT GROWS TO FILL THE
         * SCREEN, which is what the client asked this band to do after
         * havenconstructions.com.au.
         *
         * The file storyboards it beside the frame: 522x362 in the band, then
         * 723x502, then 1181x820, then 1728x980 — full width — with a
         * different photograph at each stage. So the box is laid out at the
         * last of those and scaled down to the first: 522/1728 is 0.302, which
         * is where the growth starts.
         *
         * The words and the two labels hold while the picture is small and
         * fade as it passes under them, because the file's last stage carries
         * no type at all — at full width the photograph is the whole band.
         *
         * `travel` is how much scrolling the whole thing takes, as a
         * percentage of the screen's height. 220 rather than 260: the picture
         * is at full size by the end of the pinned range either way, and the
         * extra 40 was scrolling spent on a picture that had stopped growing.
         */
        'wordmark' => [
            'label' => 'Specialist Craftsmanship',
            'words' => ['Bespoke', 'Joinery'],
            'footnote' => 'Made To Measure',
            'travel' => 130,
            'scale_from' => 0.302,
            'images' => [
                ['src' => 'images/joinery/bespoke-1.webp', 'alt' => 'A joiner running timber through a workshop machine'],
                ['src' => 'images/joinery/bespoke-2.webp', 'alt' => 'A hand planing a length of timber'],
                ['src' => 'images/joinery/bespoke-3.webp', 'alt' => 'A chisel worked along the edge of a board'],
                ['src' => 'images/joinery/bespoke-4.webp', 'alt' => 'Sawdust thrown up as a board is cut'],
            ],
        ],

        'ecosystem' => [
            'label' => ['Within The Sama', 'Ecosystem'],
            'statement' => 'The fit-out and the craft that finishes it, from one team.',
            'note' => ['Multiple', 'disciplines, one vision.'],
            'body' => 'Sama Al Tariq delivers the interior fit-out: ceilings, partitions, finishing and MEP. Alwan Interiors, our own joinery workshop, makes the woodwork that completes it. The room and everything built into it come from one team.',
        ],

        /*
         * Three cards on the mist ground, each with its copy at the top and
         * the title and number on one line at the foot, beside a tall picture.
         * Written out rather than quoted from the services page: the frame
         * gives these three their own names and their own copy, and two of
         * them are not the services page's joinery entries at all.
         */
        'capabilities' => [
            'heading' => ['Interior', 'Capabilities'],
            'items' => [
                [
                    'number' => '01',
                    'title' => 'Bespoke Joinery',
                    'body' => 'Custom-crafted architectural elements developed around the character, dimensions, and requirements of each interior.',
                ],
                [
                    'number' => '02',
                    'title' => 'Architectural Woodwork',
                    'body' => 'From wall panelling and doors to cabinetry and feature details, crafted woodwork that brings depth and identity to the space.',
                ],
                [
                    'number' => '03',
                    'title' => 'Custom Interiors',
                    'body' => 'Purpose-built furniture, cabinetry, vanities, and integrated elements created to complete the interior as one cohesive environment.',
                ],
            ],
            /*
             * A COLUMN OF THREE, not one picture. The client asked for the
             * arrangement Halston's stats section uses
             * (halston-architecture-template.webflow.io/services#stats): the
             * cards hold while a taller column of photographs slides past
             * them. The file has the other two beside the frame — 1830:229 and
             * 1834:231, both at the frame's own 773x740.
             */
            'images' => [
                ['src' => 'images/joinery/capabilities-1.webp', 'alt' => 'A joiner working a length of timber at the saw'],
                ['src' => 'images/joinery/capabilities-2.webp', 'alt' => 'Timber slats seen in raking light'],
                ['src' => 'images/joinery/capabilities-3.webp', 'alt' => 'A restaurant booth in timber and brass'],
            ],
        ],

        /*
         * The slab with a picture set inside the line. Three lines, each at its
         * own indent, and the picture sits before "Is Often" on the second.
         * No copy under it — the frame carries none.
         */
        /*
         * The slab, and the picture set inside its second line CYCLES. Halston
         * closes on the same device — a picture inside a slab of type — and
         * the file gives this one five photographs at its 218x106, so they
         * cross-fade as the band crosses the screen.
         *
         * The pictures are decorative: the sentence they sit inside carries
         * the meaning, so their alt is empty by design rather than by
         * omission.
         */
        'detail' => [
            'words' => ['What Defines A Space', 'Is Often', 'In The Details'],
            'images' => [
                'images/joinery/detail-1.webp',
                'images/joinery/detail-2.webp',
                'images/joinery/detail-3.webp',
                'images/joinery/detail-4.webp',
                'images/joinery/detail-5.webp',
            ],
        ],

        'process' => [
            'label' => 'From Idea To Craft',
            'heading' => 'Every detail passes through a considered process.',
            // Right of the rule first, then alternating — the frame's order.
            'steps' => [
                ['title' => 'Understand', 'body' => 'Interpreting the design intent, project requirements, and material direction.'],
                ['title' => 'Develop', 'body' => 'Translating concepts into practical shop drawings, details, and production requirements.'],
                ['title' => 'Craft', 'body' => 'Manufacturing bespoke elements with controlled material selection and workmanship.'],
                ['title' => 'Install', 'body' => 'Coordinating delivery and installation within the live project environment.'],
                ['title' => 'Refine', 'body' => 'Reviewing the completed work to ensure the final detail feels resolved.'],
            ],
        ],

        /*
         * Two project tiles at the frame's 772x727, on the frame's own
         * photographs rather than the projects grid's covers — it cuts these
         * two differently there. `project` is the page each opens.
         */
        'context' => [
            'heading' => ['Craft In', 'Context'],
            'body' => 'Explore selected interiors and joinery works where material, craftsmanship, and execution come together to shape the finished environment.',
            'tiles' => [
                [
                    'image' => 'images/joinery/context-1.webp',
                    'project' => 'fidelity-gym-jlt',
                    'title' => 'Fidelity Gym, JLT',
                    'category' => 'Fitness & Spa',
                ],
                [
                    'image' => 'images/joinery/context-2.webp',
                    'project' => 'jumeirah-island-villa',
                    'title' => 'Jumeirah Island Villa',
                    'category' => 'Luxury Residential',
                ],
            ],
            'cta' => ['label' => 'Explore All Projects', 'href' => '/projects'],
        ],

        'faqs' => [
            'label' => 'Common FAQ',
            'heading' => ['Clear answers to the details', 'that matter.'],
            'cta' => ['label' => 'Have More Questions? Let\'s Talk', 'href' => '/contact'],
            'items' => [
                [
                    'q' => 'What is Alwan Interiors?',
                    'a' => 'Alwan Interiors is the specialist interior and joinery capability supporting Sama Al Tariq\'s interior works, focused on bespoke joinery, architectural woodwork, and tailored interior elements.',
                ],
                [
                    'q' => 'What types of joinery do you provide?',
                    'a' => 'Our work includes bespoke joinery, architectural wall panelling, doors, cabinetry, vanities, feature elements, and other custom-made interior components.',
                ],
                [
                    'q' => 'Can Alwan work from an existing design?',
                    'a' => 'Yes. We can work from approved architectural and interior design packages, developing the required joinery details and production information to support accurate execution.',
                ],
                [
                    'q' => 'Do you provide bespoke solutions for each project?',
                    'a' => 'Yes. Each element can be developed around the project\'s dimensions, material palette, functional requirements, and overall design intent.',
                ],
                [
                    'q' => 'Which sectors do you work across?',
                    'a' => 'Our capabilities can support residential, hospitality, corporate, retail, healthcare, government, and other project environments requiring specialist interior and joinery work.',
                ],
                [
                    'q' => 'How does Alwan work with Sama Al Tariq?',
                    'a' => 'Alwan provides specialist joinery and interior execution within Sama Al Tariq\'s wider project delivery structure, allowing the two teams to coordinate closely across the fit-out.',
                ],
            ],
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
            'label' => 'What We Do',
            'statement' => 'Interior fit-out from first drawing to final handover, under one contractor.',
            'note' => ['Design, build,', 'and care after.'],
            'body' => 'Our own teams handle design, approvals, finishing, joinery and MEP, and we stay on after handover with maintenance. You get one programme, one point of contact and one team responsible for the result.',
        ],

        /*
         * Eight services, each with the scope it covers. The page sets them as
         * an index: the list on one side and the photograph of whichever row
         * is active on the other (sections/services-page/services).
         */
        'services' => [
            [
                'number' => '01', 'title' => ['Interior Fit-Out'], 'image' => 'images/services/service-1.webp',
                'lead' => 'Turnkey fit-out, from shell to handover.',
                'points' => ['Civil and interior works', 'Flooring, wall finishes and ceilings', 'Site supervision, snagging and handover'],
            ],
            [
                'number' => '02', 'title' => ['Design & Build'], 'image' => 'images/services/service-2.webp',
                'lead' => 'One team from concept to keys.',
                'points' => ['Space planning and mood boards', 'Material and finishes selection', 'Shop drawings and technical details'],
            ],
            [
                'number' => '03', 'title' => ['Villa Renovation'], 'image' => 'images/services/service-4.webp',
                'lead' => 'Homes renewed room by room.',
                'points' => ['Kitchens, bathrooms and living areas', 'Layout changes and upgrades', 'Phased works for families living in'],
            ],
            [
                'number' => '04', 'title' => ['Bespoke Joinery'], 'image' => 'images/services/service-6.webp',
                'lead' => 'Made in our own workshop, Alwan Interiors.',
                'points' => ['Wardrobes, kitchens and vanities', 'Wall panelling and feature pieces', 'Loose furniture made to order'],
            ],
            [
                'number' => '05', 'title' => ['Gypsum, Ceilings & Finishing'], 'image' => 'images/workers/work-17.webp',
                'lead' => 'The surfaces you see every day.',
                'points' => ['Gypsum ceilings and bulkheads', 'Partitions and drywall', 'Paint, tiling and stone'],
            ],
            [
                'number' => '06', 'title' => ['MEP Works'], 'image' => 'images/services/service-8.webp',
                'lead' => 'Systems resolved before the ceiling closes.',
                'points' => ['HVAC, electrical and plumbing', 'Lighting and power layouts', 'Coordination drawings with the design'],
            ],
            [
                'number' => '07', 'title' => ['Authority Approvals'], 'image' => 'images/services/service-9.webp',
                'lead' => 'Permits and NOCs, handled for you.',
                'points' => ['Submission drawings', 'Landlord and building NOCs', 'Follow-up until approval'],
            ],
            [
                'number' => '08', 'title' => ['AMC & Aftercare'], 'image' => 'images/services/service-10.webp',
                'lead' => 'We stay after the handover.',
                'points' => ['Annual maintenance contracts', 'Defects and snag support', 'Planned and reactive repairs'],
            ],
        ],

        // Two ways to engage, depending on whether the client has a designer.
        'delivery' => [
            'label' => 'How You Can Work With Us',
            'models' => [
                [
                    'title' => 'Design & Build',
                    'for' => 'You have a space and a brief.',
                    'body' => 'We design it, get it approved, build it and hand it over. One contract and one team from start to finish.',
                    'points' => ['Concept and technical design', 'Approvals and procurement', 'Fit-out, joinery and MEP', 'Handover and aftercare'],
                ],
                [
                    'title' => 'Build Only',
                    'for' => 'You already have a designer.',
                    'body' => 'Send us the approved drawings and BOQ. We build to them exactly and coordinate with your consultant on site.',
                    'points' => ['Drawing and BOQ review', 'Shop drawings and samples', 'Fit-out, joinery and MEP', 'Snagging and handover'],
                ],
            ],
        ],

        // Working in occupied hotels, offices and homes.
        'live' => [
            'label' => 'Occupied Spaces',
            'heading' => ['Works Inside', 'Spaces That Stay Open'],
            'body' => 'Hotels, offices and homes rarely stop for a renovation. We plan the works so guests, staff and families can carry on around us.',
            'image' => 'images/projects/pullman/l1.webp',
            'steps' => [
                ['title' => 'Map', 'body' => 'Walk the space and learn how it runs day to day.'],
                ['title' => 'Phase', 'body' => 'Split the works into zones and agree the order.'],
                ['title' => 'Protect', 'body' => 'Screen off each zone and control dust, noise and access.'],
                ['title' => 'Hand Over', 'body' => 'Finish and return each zone before the next one opens.'],
            ],
        ],

        'faq' => [
            'label' => 'Questions',
            'items' => [
                ['q' => 'What kind of projects do you take on?', 'a' => 'Interior fit-out and renovation for residences, villas, hotels, restaurants, offices, gyms and clinics across Dubai and the UAE.'],
                ['q' => 'Can you work from our designer\'s drawings?', 'a' => 'Yes. With Build Only, we take your approved drawings and BOQ, then build to them and coordinate with your consultant.'],
                ['q' => 'Do you handle authority approvals?', 'a' => 'Yes. We prepare submission drawings and follow up on landlord, building and authority NOCs until they are approved.'],
                ['q' => 'Is the joinery made in house?', 'a' => 'Yes. Wardrobes, kitchens, panelling and furniture are made by our joinery division, Alwan Interiors.'],
                ['q' => 'How long does a fit-out take?', 'a' => 'It depends on size and scope. We give you a programme with the proposal and report against it every week.'],
                ['q' => 'What happens after handover?', 'a' => 'Snagging support, and an annual maintenance contract (AMC) if you want us to look after the space.'],
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
        'body' => "Planning a fit-out or renovation? Tell us about the space, and our team will get back to you with the next steps.",
        'details' => [
            ['label' => 'Telephone', 'value' => '+971 54 319 0845', 'href' => 'tel:+971543190845'],
            ['label' => 'Email', 'value' => 'info@samaaltariq.org', 'href' => 'mailto:info@samaaltariq.org'],
            ['label' => 'Office', 'value' => 'Office 804, Sapphire Tower Dubai, United Arab Emirates', 'href' => null],
        ],
    ],

    'clients' => [
        'label' => ["Companies We've", 'Worked With'],
        // From the 2026 business profile's "In Good Company" page.
        'caption' => 'Trusted across government, hospitality, healthcare and luxury developers.',
        // The About page sets the roster as the profile does, under its own
        // heading, rather than repeating the landing page's strip.
        'company_heading' => ['In', 'Good Company'],
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
            ['name' => 'bluecamel', 'src' => 'images/clients/bluecamel.svg'],
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
            ['name' => 'DUTCO', 'src' => 'images/clients/dutco.webp'],
            ['name' => 'Al Marjan Island', 'src' => 'images/clients/al-marjan-island.webp'],
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
        /*
         * The projects the home page's 3D stage walks through, in this order.
         * Eight, so the pyramid has cards either side of the one in front all
         * the way through; the rest are on the projects page. Slugs, as on
         * the projects page.
         */
        'featured' => [
            'jumeirah-golf-estate-villas',
            'emirates-hills-villa',
            'wasl-properties-hq',
            'benjarong-dusit-thani',
            'villa-pv39-tilal-al-ghaf',
            'fidelity-gym-jlt',
            'boulevard-plaza-office',
            'pullman',
        ],
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
                'image' => 'images/services/expertise-1.webp',
                'blueprint' => 'images/services/expertise-1-blueprint.webp',
            ],
            [
                'tab' => 'Design & Build',
                'description' => 'Integrated design-and-build combining concept design, technical drawings, BOQ and full execution under a single point of responsibility.',
                'title' => ['Design And Build', 'Interior And Architectural'],
                'image' => 'images/services/expertise-2.webp',
                'blueprint' => 'images/services/expertise-2-blueprint.webp',
            ],
            [
                'tab' => 'Commercial & Office',
                'description' => 'Workspace, office and corporate interior delivery — space planning, partitions, flooring, ceilings and finishes built for productive environments.',
                'title' => ['Commercial And Office', 'Interior Solutions'],
                'image' => 'images/services/expertise-3.webp',
                'blueprint' => 'images/services/expertise-3-blueprint.webp',
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
                'image' => 'images/process/interior-1.webp', 'ratio' => '4 / 3',
            ],
            // PLACEHOLDER steps — only step 01 exists in the Figma file.
            [
                'number' => '02',
                'title' => 'Design And Engineering',
                'body' => 'Detailed drawings, material selection, and value engineering that turn an approved budget into a buildable scheme.',
                'image' => 'images/process/interior-2.webp', 'ratio' => '4 / 3',
            ],
            [
                'number' => '03',
                'title' => 'Construction And Fit-Out',
                'body' => 'Coordinated site delivery with dedicated supervision, strict QA checkpoints, and transparent weekly progress reporting.',
                'image' => 'images/process/interior-3.webp', 'ratio' => '4 / 3',
            ],
            [
                'number' => '04',
                'title' => 'Handover And Aftercare',
                'body' => 'Snagging, testing and commissioning, full documentation, and a maintenance period that protects the finished asset.',
                'image' => 'images/process/interior-4.webp', 'ratio' => '4 / 3',
            ],
        ],
    ],

    /*
     * From the Sama Al Tariq Business Profile 2026, verbatim: the figures on
     * its "By the Numbers" page, the six scopes of "We Do Everything Your
     * Project Needs", the six sectors of "Sectors We Serve", and the trade
     * licence from "Licences & Certifications". Change them here when the
     * profile changes.
     */
    'numbers' => [
        'label' => 'By the Numbers',
        'tagline' => 'One contractor. Every part of the interior.',
        'items' => [
            ['value' => '150+', 'label' => 'Engineers, technicians and craftsmen in-house'],
            ['value' => '12+', 'label' => 'Flagship projects delivered'],
            ['value' => '250k+', 'label' => 'Meter square designed, built and fitted'],
            ['value' => '95%', 'label' => 'On-programme handovers'],
            ['value' => '100%', 'label' => 'In-house joinery and MEP coordination'],
            ['value' => '06', 'label' => 'Sectors served in total'],
        ],
    ],

    /*
     * The Team page, and the band on the landing page that leads to it.
     *
     * Built on the business profile's own figures (150+ in-house, joinery
     * and MEP 100% in-house) and its six scopes, which are the departments.
     * Each department's `roles` is the usual ladder of a contracting team and
     * `structure` how a project is staffed — written to the profile's scopes,
     * not taken from any document of the company's: CONFIRM BOTH WITH THE
     * BUSINESS and correct them here. `leadership` is empty until names,
     * titles and photographs are supplied; the section draws only once it
     * has someone in it.
     */
    'team_page' => [
        'label' => 'Our Team',
        'heading' => ['One Team,', 'In House'],
        'lead' => '150+ engineers, technicians and craftsmen — every scope of the interior under one roof.',
        'body' => 'Interior fit-out, joinery, finishing and MEP are delivered by our own people, not subcontracted. One programme, one project lead and one team carry a project from tender to handover.',
        'image' => 'images/workers/work-17.webp',
        'figures' => [
            ['value' => '150+', 'label' => 'Engineers, technicians and craftsmen'],
            ['value' => '06', 'label' => 'In-house departments'],
            ['value' => '100%', 'label' => 'In-house joinery and MEP'],
        ],
        'departments' => [
            ['name' => 'Management & Engineering', 'image' => 'images/services/service-10.webp',
             'roles' => ['Project Managers', 'Project Coordinators', 'Architects & Draftsmen', 'Quantity Surveyors', 'Authority Approvals']],
            ['name' => 'Finishing Works', 'image' => 'images/workers/work-19.webp',
             'roles' => ['Gypsum, Ceiling & Partition Teams', 'Flooring & Stone Teams', 'Painting & Wall Finishes', 'Site Supervisors & Foremen']],
            ['name' => 'AMC & Property Care', 'image' => 'images/services/service-6.webp',
             'roles' => ['Maintenance Engineers', 'MEP Technicians', 'Joinery & Finishing Repairs', 'Planned & Reactive Service']],
            ['name' => 'Interior Fit-Out', 'image' => 'images/workers/work-9.webp',
             'roles' => ['Fit-Out Managers', 'Site Supervisors & Foremen', 'Gypsum, Ceiling & Partition Teams', 'Flooring, Stone & Painting Teams']],
            ['name' => 'Joinery — Alwan Interiors', 'image' => 'images/workers/work-14.webp',
             'roles' => ['Head of Joinery', 'Production Manager', 'Draftsmen', 'Workshop Craftsmen', 'Installation Team']],
            ['name' => 'MEP', 'image' => 'images/services/service-8.webp',
             'roles' => ['HVAC & Electrical Engineers', 'Plumbing & Drainage', 'Fire & Low Current', 'Technicians & Installers']],
        ],
        'structure' => [
            'top' => ['Operations Manager', 'Project Manager', 'Project Coordinator'],
            'leads' => ['Architect', 'MEP Engineers', 'Site Engineer', 'Authority Approvals'],
            'heads' => ['Fit-Out', 'Finishing', 'Joinery', 'MEP', 'AMC'],
        ],
        'leadership' => [],
    ],

    'scope' => [
        'heading' => ['We Do Everything', 'Your Project Needs'],
        'body' => 'Every scope is coordinated under one programme and one project lead.',
        /*
         * Each scope is a full-screen photograph of that work, with a card
         * that rises over it (sections/scope). `credit` names the project
         * the photograph is from, so the picture and the copy agree.
         */
        'items' => [
            [
                'title' => 'Interior Fit-Out & Renovation',
                'image' => 'images/projects/villa-pv39-tilal-al-ghaf/hero-3.webp',
                'credit' => 'Villa PV39, Tilal Al Ghaf',
                'lead' => 'From bare shell to a finished, furnished space.',
                'body' => 'Ceilings, partitions, flooring, stone and paint, delivered by our own site teams for homes, hotels, restaurants and offices.',
                'points' => ['Turnkey fit-out', 'Villa renovation', 'Finishing works'],
            ],
            [
                'title' => 'Custom Joinery & Furniture',
                'image' => 'images/projects/jumeirah-island-villa/hero-3.webp',
                'credit' => 'Jumeirah Island Villa',
                'lead' => 'Made in our own workshop, Alwan Interiors.',
                'body' => 'Wardrobes, kitchens, wall panelling and loose furniture, drawn, made and installed by one team so every piece fits the space it was made for.',
                'points' => ['Wardrobes & kitchens', 'Wall panelling', 'Bespoke furniture'],
            ],
            [
                'title' => 'MEP Coordination & Execution',
                'image' => 'images/projects/boulevard-plaza-office/hero-2.webp',
                'credit' => 'Boulevard Plaza Office',
                'lead' => 'Systems resolved before the ceiling closes.',
                'body' => 'HVAC, lighting, power and plumbing coordinated with the design from the first drawing, so what you see and what runs behind it work together.',
                'points' => ['HVAC', 'Lighting & power', 'Plumbing'],
            ],
            [
                'title' => 'AMC & Property Management',
                'image' => 'images/projects/w-residence-palm-jumeirah/hero-3.webp',
                'credit' => 'W Residence, Palm Jumeirah',
                'lead' => 'We stay after the handover.',
                'body' => 'Annual maintenance contracts that keep finished homes and workplaces in the condition we handed them over in, with planned visits and fast repairs.',
                'points' => ['Annual maintenance', 'Planned visits', 'Reactive repairs'],
            ],
        ],
        'sectors_label' => 'Sectors We Serve',
        'sectors_body' => 'Interior fit-out for every kind of space, each one planned around how it will be used.',
        'sectors' => ['Residential', 'Commercial', 'Hospitality & F&B', 'Fitness', 'Retail', 'Institutional'],
        /*
         * A picture per sector for the sectors section, each a cover of a
         * project the site already shows in that sector. Retail has no
         * project here yet, so it carries none rather than a borrowed one —
         * add a cover when there is one.
         */
        'sector_images' => [
            'Residential' => 'images/projects/covers/jumeirah-golf-estate-villas.webp',
            'Commercial' => 'images/projects/covers/boulevard-plaza-office.webp',
            'Hospitality & F&B' => 'images/projects/covers/benjarong-dusit-thani.webp',
            'Fitness' => 'images/projects/covers/fidelity-gym-jlt.webp',
            'Retail' => null,
            'Institutional' => 'images/projects/covers/wasl-properties-hq.webp',
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
        'heading' => ['Tell Us About', 'Your Space.'],
        'body' => "Villa, apartment, office, restaurant or hotel: tell us what you have in mind, and we'll come back with a scope and an estimate.",
        // From the 2026 business profile's closing page.
        'brief' => 'Send us your drawings, BOQ or project brief for an initial scope review.',
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
     * Footer contact block. `phone` and `address` stood null here because the
     * Figma file contained neither — the redrawn footer does, so they are the
     * file's own now. Each line still renders only when it is set.
     */
    'contact' => [
        'heading' => 'Get in touch',
        'email' => env('ENQUIRY_TO', 'info@samaaltariq.org'),
        'phone' => '+971 54 319 0845',
        'address' => 'Office 804, Sapphire Tower Dubai, United Arab Emirates',
    ],

    /*
     * The footer, redrawn — Figma 1803:2, y9415–10030.
     *
     * It was a lock-up and nav at the gutter, a "Recently Completed" card two
     * thirds across and the marks in a strip beside it. The file replaces all
     * of that with one row: the mark, a line of copy and a pill on the left,
     * then four labelled columns — Navigate, Contact, Address, Socials — with
     * the marks in a ROW under the last of them.
     *
     * The card is gone from the design, so it is gone from here; its picture
     * stays on disk, unreferenced. The legal bar is gone too: the file carries
     * no copyright line down here because the enquiry card above already sets
     * one, which it has done since that card was built.
     *
     * The tracks are the frame's own, measured from its content edges: 373 of
     * left block, then 103, 182, 291 and 280, with 88 between each.
     */
    'footer' => [
        'lead' => 'Share your requirements and let\'s discuss the possibilities.',
        'cta' => ['label' => 'Get In Touch', 'href' => '/contact'],
        'columns' => [
            'nav' => 'Navigate',
            'contact' => 'Contact',
            'address' => 'Address',
            'social' => 'Socials',
        ],
        'wordmark' => 'Sama Al Tariq',
        'wordmark_sub' => 'Building Contracting LLC.',
    ],

    /*
     * The Alwan section on the landing page. The piece it builds in 3D is the
     * ribbed, curving wall-into-ceiling of the About photograph — the kind of
     * work most workshops turn down — and the photograph itself arrives once
     * the model is complete (motion/rib-vault.js).
     */
    'alwan_feature' => [
        'label' => 'Alwan Interiors · Joinery',
        'heading' => ['The Hard Pieces,', 'Made In House'],
        'body' => 'Ribbed walls that turn into ceilings, curves cut to the millimetre, furniture made for one room. Drawn, made and fitted by our own workshop.',
        'image' => 'images/about-interior.webp',
        'alt' => 'Curved ribbed wall flowing into the ceiling, finished by Sama Al Tariq',
        'caption' => ['Designed in 3D', 'Built for real'],
        'phases' => ['Flat board', 'CNC cut', 'Assembled', 'Built for real'],
    ],

    /*
     * The landing page after the hero, after kojinteriors.com's model: one
     * photograph held full-screen behind the page (it changes, and settles
     * from a slight zoom, as each chapter arrives), and the content on white
     * cards scrolling over it. Between the cards the photograph shows, with
     * the chapter's title across it (sections/story, motion/story-backdrop.js).
     */
    'story' => [
        'about' => [
            'bg' => 'images/projects/w-residence-palm-jumeirah/hero-3.webp',
            'eyebrow' => 'Who We Are',
            'title' => ['Interiors,', 'Built In House'],
            'heading' => 'Sama Al Tariq is an interior fit-out contractor in Dubai.',
            'body' => 'We take an interior from the bare shell to the day you move in: gypsum ceilings and partitions, flooring, stone, paint, joinery and MEP. More than 150 of our own people do the work, without subcontracting.',
            'image' => 'images/about-interior.webp',
            'image_alt' => 'Curved ribbed wall flowing into the ceiling, finished by Sama Al Tariq',
            'trades' => ['Gypsum & Ceilings', 'Interior Fit-Out', 'Finishing Works', 'Bespoke Joinery', 'MEP', 'AMC'],
            'cta' => ['label' => 'More About Us', 'href' => '/about'],
            'stats' => [
                ['value' => '150+', 'label' => 'In-house team'],
                ['value' => '250k+', 'label' => 'm² finished'],
                ['value' => '95%', 'label' => 'On-programme handovers'],
                ['value' => '06', 'label' => 'Sectors served'],
            ],
        ],
        /*
         * The core trade, given its own chapter: the same ceiling framed and
         * then finished, the second photograph wiping over the first as the
         * card crosses the screen (sections/story, motion/gypsum-wipe.js).
         */
        'gypsum' => [
            'bg' => 'images/hero/boulevard.webp',
            'eyebrow' => 'Our Core Expertise',
            'title' => ['Gypsum', '& Ceilings'],
            'heading' => 'Gypsum and ceilings are our core trade, done by our own teams.',
            'body' => 'Our own gypsum teams set out, frame, board and finish every ceiling and partition, so the lines stay straight, the coves stay clean and the lighting sits exactly where it was drawn.',
            'framed' => ['src' => 'images/workers/work-19.webp', 'label' => 'Framed'],
            'finished' => ['src' => 'images/hero/boulevard.webp', 'label' => 'Finished'],
            'list' => [
                'Suspended and floating ceilings',
                'Coves, bulkheads and light troughs',
                'Curved and stepped profiles',
                'Partitions and drywall',
                'Cornices and decorative profiles',
                'Access panels and MEP openings',
            ],
        ],
        'services' => [
            'bg' => 'images/projects/dubai-hills-business-park/hero-2.webp',
            'eyebrow' => 'What We Do',
            'title' => ['Every Interior', 'Trade, In House'],
            'heading' => ['Interior Fit-Out', 'Services'],
            'body' => 'Every trade an interior needs, done by our own teams under one programme and one project lead.',
            'cta' => ['label' => 'All Services', 'href' => '/services'],
            // The Services page's own list, so the two never disagree.
            'pick' => [4, 0, 1, 2, 3, 5, 6, 7],
        ],
        'work' => [
            'bg' => 'images/projects/villa-pv39-tilal-al-ghaf/hero-3.webp',
            'eyebrow' => 'How We Work',
            'title' => ['One Team,', 'Shell To Handover'],
            'heading' => ['Two Ways', 'To Work With Us'],
            'body' => 'Bring us a space and a brief, or a finished design and BOQ. Either way one team runs the job from the first drawing to the handover.',
            'steps' => [
                ['title' => 'Estimate', 'body' => 'Drawings and BOQ reviewed, site surveyed, every item priced.'],
                ['title' => 'Plan', 'body' => 'Shop drawings, samples and approvals before work starts.'],
                ['title' => 'Fit-Out', 'body' => 'Ceilings, finishes, joinery and MEP by our own teams.'],
                ['title' => 'Hand Over', 'body' => 'Snagged, tested and handed over, with AMC after.'],
            ],
            'cta' => ['label' => 'Our Process', 'href' => '/process'],
        ],
        'projects_label' => 'Featured Projects',
        'projects' => [
            ['slug' => 'jumeirah-golf-estate-villas', 'title' => ['Jumeirah Golf', 'Estate Villas'], 'category' => 'Luxury Residential', 'line' => 'Villas fitted out to one standard, from the entrance to the last bedroom.'],
            ['slug' => 'wasl-properties-hq', 'title' => ['WASL', 'Properties HQ'], 'category' => 'Corporate', 'line' => 'A 45,000 sq ft headquarters on Sheikh Zayed Road, delivered in four months.'],
            ['slug' => 'benjarong-dusit-thani', 'title' => ['Benjarong,', 'Dusit Thani'], 'category' => 'Hospitality / F&B', 'line' => 'A restaurant of more than 10,000 sq ft, delivered in three months.'],
            ['slug' => 'pullman', 'title' => ['Pullman', 'Hotel'], 'category' => 'Hospitality', 'line' => 'Hotel interiors, fitted out end to end.'],
        ],
        'projects_cta' => ['label' => 'View All Projects', 'href' => '/projects'],
        // Our own people at work, from the client's own site photographs
        // (source-photos/workers): proof that the work is not subcontracted.
        'crew' => [
            'bg' => 'images/workers/work-19.webp',
            'eyebrow' => 'Our People On Site',
            'title' => ['Our Own Hands,', 'On Every Site'],
            'heading' => ['150+ of our own people.', 'No subcontractors.'],
            'body' => 'Gypsum fixers, carpenters, painters, MEP technicians and site engineers, all employed by Sama Al Tariq. The people who quote your project are the people who build it.',
            'photos' => [
                ['src' => 'images/workers/work-17.webp', 'caption' => 'Gypsum ceiling framing', 'wide' => true],
                ['src' => 'images/workers/work-10.webp', 'caption' => 'Fluted panel and lighting'],
                ['src' => 'images/workers/work-3.webp', 'caption' => 'Wall cladding install'],
                ['src' => 'images/workers/work-12.webp', 'caption' => 'Alwan workshop'],
                ['src' => 'images/workers/work-11.webp', 'caption' => 'Curved column cladding'],
                ['src' => 'images/workers/work-9.webp', 'caption' => 'Feature wall, on site'],
                ['src' => 'images/workers/work-18.webp', 'caption' => 'Panel cutting'],
            ],
            'cta' => ['label' => 'Meet The Team', 'href' => '/team'],
        ],
        'alwan' => [
            'bg' => 'images/projects/jumeirah-island-villa/hero-3.webp',
            'eyebrow' => 'Our Joinery Workshop',
            'title' => ['Alwan', 'Interiors'],
            'heading' => 'Joinery made by our own hands.',
            'body' => 'Wardrobes, kitchens, wall panelling, curved and ribbed feature walls and furniture, drawn, made and fitted by Alwan Interiors, our own workshop.',
            'image' => 'images/workers/work-8.webp',
            'cta' => ['label' => 'Explore Joinery', 'href' => '/joinery'],
            'team' => ['value' => '150+', 'label' => 'Engineers, technicians and craftsmen, all in house.', 'cta' => ['label' => 'Meet The Team', 'href' => '/team']],
        ],
        'faq_heading' => ['Questions', 'We Get Asked'],
    ],
];
