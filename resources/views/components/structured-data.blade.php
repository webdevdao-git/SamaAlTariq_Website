@php
    $site = config('site');
    $details = collect($site['contact_page']['details'] ?? []);
    $phone = $details->firstWhere('label', 'Telephone')['value'] ?? null;
    $email = $details->firstWhere('label', 'Email')['value'] ?? null;
    $office = $details->firstWhere('label', 'Office')['value'] ?? null;

    /*
     * One organisation, described once. Everything here is already on the
     * contact page in words — this is the same material in the shape a search
     * engine reads, so the two cannot drift: change the config and both move.
     */
    $organisation = array_filter([
        '@type' => 'GeneralContractor',
        '@id' => url('/').'#organisation',
        'name' => $site['legal_name'],
        'alternateName' => $site['name'],
        'url' => url('/'),
        'logo' => \App\Support\Asset::versioned('images/logo-mark.png'),
        'image' => \App\Support\Asset::versioned('images/hero.webp'),
        'telephone' => $phone,
        'email' => $email,
        'address' => $office ? [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Office 804, Sapphire Tower',
            'addressLocality' => 'Dubai',
            'addressCountry' => 'AE',
        ] : null,
        'areaServed' => ['@type' => 'Country', 'name' => 'United Arab Emirates'],
        'sameAs' => collect($site['social'] ?? [])->pluck('href')->values()->all(),
    ]);

    /*
     * The trail to this page. Home alone says nothing, so it is only drawn
     * from the second level down — and the label is the page's own <title>
     * with the company name trimmed off it, so a page cannot end up with one
     * name in the tab and another in the results.
     */
    $segments = request()->segments();
    $crumbs = [];

    if ($segments !== []) {
        $crumbs[] = ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')];

        $path = '';
        foreach ($segments as $i => $segment) {
            $path .= '/'.$segment;
            $crumbs[] = [
                '@type' => 'ListItem',
                'position' => $i + 2,
                'name' => Str::of($segment)->replace('-', ' ')->title()->toString(),
                'item' => url($path),
            ];
        }
    }

    $graph = [$organisation];

    // The site itself, once, on the page that is the site's root.
    if ($segments === []) {
        $graph[] = [
            '@type' => 'WebSite',
            '@id' => url('/').'#website',
            'url' => url('/'),
            'name' => $site['legal_name'],
            'publisher' => ['@id' => url('/').'#organisation'],
        ];
    }

    if ($crumbs !== []) {
        $graph[] = ['@type' => 'BreadcrumbList', 'itemListElement' => $crumbs];
    }
@endphp

<script type="application/ld+json">@json(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
