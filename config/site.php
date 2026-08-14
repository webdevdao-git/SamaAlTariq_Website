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
        // The one entry that is a page rather than a section of the landing
        // page. App\Support\Nav leaves non-fragment hrefs alone.
        ['label' => 'About', 'href' => '/about'],
        ['label' => 'Projects', 'href' => '/projects'],
        ['label' => 'Services', 'href' => '#services'],
        ['label' => 'Process', 'href' => '#process'],
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
        'hero' => [
            'heading' => ['We Build the Foundations', 'of What Comes Next.'],
            'tag' => 'About Us',
            'lead' => ['Construction Shaped By Expertise,', 'Intention, And Enduring Quality.'],
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
     * The projects page — Figma, and the composition is the point.
     *
     * Each group is a stack of `rows`, and each row a set of `columns` on a
     * twelve-track grid. A column holds one tile or two stacked, and the tiles
     * in a row share its height. That is what produces the design's rhythm: a
     * tall picture beside a pair, then two equal ones, then a pair beside a
     * tall one. A flat list with spans cannot say this — it leaves holes where
     * a group runs out of items.
     *
     * `image` is a file in public/images/projects/covers, not a project slug:
     * Hospitality and Fitness each show one project twice, from two different
     * photographs, exactly as the file draws them.
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
                    ['columns' => [
                        ['cols' => 7, 'tiles' => [
                            ['image' => 'jumeirah-golf-estate-villas', 'title' => 'Jumeirah Golf Estate Villas', 'category' => 'Luxury Residential', 'location' => 'Jumeirah Golf Estate, Dubai', 'size' => '18,000 Sq Ft', 'duration' => '8 Months'],
                        ]],
                        ['cols' => 5, 'tiles' => [
                            ['image' => 'villa-pv39-tilal-al-ghaf', 'title' => 'Villa PV39, Tilal-Al-Ghaf', 'category' => 'Luxury Residential', 'location' => 'Tilal-Al-Ghaf, Dubai', 'size' => '8,000 Sq Ft', 'duration' => '6 Months'],
                            ['image' => 'w-residence-palm-jumeirah', 'title' => 'W Residence, Palm Jumeirah', 'category' => 'Luxury Residential', 'location' => 'Palm Jumeirah, Dubai', 'size' => '6,500 Sq Ft', 'duration' => '3 Months'],
                        ]],
                    ]],
                    ['columns' => [
                        ['cols' => 6, 'tiles' => [
                            ['image' => 'emirates-hills-villa', 'title' => 'Emirates Hills Villa', 'category' => 'Luxury Residential', 'location' => 'Emirates Hills, Dubai', 'size' => '30,000 Sq Ft', 'duration' => '12 Months'],
                        ]],
                        ['cols' => 6, 'tiles' => [
                            ['image' => 'jumeirah-island-villa', 'title' => 'Jumeirah Island Villa', 'category' => 'Luxury Residential', 'location' => 'Jumeirah Island, Dubai', 'size' => '14,000+ Sq Ft', 'duration' => '8 Months'],
                        ]],
                    ]],
                ],
            ],
            [
                'name' => 'Commercial & Corporate',
                'rows' => [
                    ['columns' => [
                        ['cols' => 5, 'tiles' => [
                            ['image' => 'i-rise-tower-office', 'title' => 'I-Rise Tower Office', 'category' => 'Office Fit-Out', 'location' => 'I-Rise Tower, Dubai', 'size' => '5,000+ Sq Ft', 'duration' => '4 Months'],
                            ['image' => 'boulevard-plaza-office', 'title' => 'Boulevard Plaza Office', 'category' => 'Office Fit-Out', 'location' => 'Boulevard Plaza, Dubai', 'size' => '7,500+ Sq Ft', 'duration' => '3 Months'],
                        ]],
                        ['cols' => 7, 'tiles' => [
                            ['image' => 'wasl-properties-hq', 'title' => 'WASL Properties HQ', 'category' => 'Corporate', 'location' => 'Sheikh Zayed Road, Dubai', 'size' => '45,000 Sq Ft', 'duration' => '4 Months'],
                        ]],
                    ]],
                ],
            ],
            [
                // The frame sets this row narrow-then-wide rather than in
                // halves, unlike Fitness & Spa below it, which is even.
                'name' => 'Hospitality, F&B',
                'rows' => [
                    ['columns' => [
                        ['cols' => 5, 'tiles' => [
                            ['image' => 'benjarong-dusit-thani', 'title' => 'Benjarong, Dusit Thani', 'category' => 'Hospitality / F&B', 'location' => 'JVC, Dubai', 'size' => '10,000+ Sq Ft', 'duration' => '3 Months'],
                        ]],
                        ['cols' => 7, 'tiles' => [
                            ['image' => 'benjarong-dusit-thani-2', 'title' => 'Benjarong, Dusit Thani', 'category' => 'Hospitality / F&B', 'location' => 'JVC, Dubai', 'size' => '10,000+ Sq Ft', 'duration' => '3 Months'],
                        ]],
                    ]],
                ],
            ],
            [
                'name' => 'Fitness & Spa',
                'rows' => [
                    ['columns' => [
                        ['cols' => 6, 'tiles' => [
                            ['image' => 'fidelity-gym-jlt', 'title' => 'Fidelity Gym, JLT', 'category' => 'Fitness & Spa', 'location' => 'Jumeirah Lake Towers, Dubai', 'size' => '25,425 Sq Ft', 'duration' => '6 Months'],
                        ]],
                        ['cols' => 6, 'tiles' => [
                            ['image' => 'fidelity-gym-jlt-2', 'title' => 'Fidelity Gym, JLT', 'category' => 'Fitness & Spa', 'location' => 'Jumeirah Lake Towers, Dubai', 'size' => '25,425 Sq Ft', 'duration' => '6 Months'],
                        ]],
                    ]],
                ],
            ],
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
