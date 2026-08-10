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
        ['label' => 'About', 'href' => '#about'],
        ['label' => 'Projects', 'href' => '#projects'],
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

    'clients' => [
        'label' => ["Companies We've", 'Worked With'],
        'logos' => [
            ['name' => 'Dusit Thani Abu Dhabi', 'src' => 'images/clients/dusit-thani.webp'],
            ['name' => 'archcorp', 'src' => 'images/clients/archcorp.webp'],
            ['name' => 'ALEC', 'src' => 'images/clients/alec.webp'],
            ['name' => 'bluecamel', 'src' => 'images/clients/bluecamel.webp'],
            ['name' => 'novomed', 'src' => 'images/clients/novomed.webp'],
            ['name' => 'Taj Dubai', 'src' => 'images/clients/taj-dubai.webp'],
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
         */
        'items' => [
            [
                'tab' => 'Fit-Out Contracting',
                'description' => 'End-to-end interior fit-out for commercial, retail, F&B and hospitality spaces, delivered turnkey from concept to handover.',
                'title' => ['Fit-Out Contracting', '& Turnkey Solutions'],
                'image' => 'images/services/fit-out-contracting.webp',
            ],
            [
                'tab' => 'Design & Build',
                'description' => 'Integrated design-and-build combining concept design, technical drawings, BOQ and full execution under a single point of responsibility.',
                'title' => ['Design & Build', 'Interior & Architectural'],
                'image' => 'images/services/design-and-build.webp',
            ],
            [
                'tab' => 'Commercial & Office',
                'description' => 'Workspace, office and corporate interior delivery — space planning, partitions, flooring, ceilings and finishes built for productive environments.',
                'title' => ['Commercial & Office', 'Interior Solutions'],
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
        'property_types' => [
            'Villa',
            'Apartment',
            'Commercial / Office',
            'Retail',
            'Hospitality',
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
            'alt' => 'Recently completed corporate lobby fit-out',
            'href' => '#projects',
        ],
        'wordmark' => 'Sama Al Tariq',
        'wordmark_sub' => 'Building Contracting LLC.',
    ],
];
