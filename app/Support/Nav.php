<?php

namespace App\Support;

/**
 * Site navigation, resolved for the page it is rendered on.
 *
 * config/site.php stores most links as bare fragments — '#projects' — because
 * they point at sections of the landing page. That works while the landing page
 * is the only page; from /about the same string resolves to /about#projects,
 * where no such section exists.
 *
 * So a fragment is made absolute everywhere except on the landing page itself,
 * where it must stay bare: motion/smooth-scroll.js only intercepts
 * a[href^="#"], and a rewritten '/#projects' would reload the page instead of
 * easing to the section.
 */
final class Nav
{
    /**
     * Fragments that mean "this page" rather than "the landing page", and so
     * stay bare wherever they render.
     *
     * Only '#contact' qualifies. Every public page includes
     * sections/inquiry.blade.php, so rewriting it would send a visitor on
     * /about back to the landing page to reach a form already under their
     * thumb. A public page added without an enquiry section has to drop it.
     *
     * '#top' is deliberately NOT here, even though every page has a hero with
     * that id. The two places that route through this helper — the header
     * lock-up and the nav's "Home" entry — both mean the landing page, not the
     * top of whatever you are reading; leaving it bare made "Home" scroll to
     * the top of /about instead of navigating. The footer's "Back to top" does
     * mean the local one, and writes a literal '#top' rather than asking here.
     */
    private const EVERYWHERE = ['#contact'];

    /**
     * The nav from config with every href resolved for the current page.
     *
     * @return array<int, array{label: string, href: string}>
     */
    public static function items(): array
    {
        return array_map(
            fn (array $item) => [...$item, 'href' => self::href($item['href'])],
            config('site.nav'),
        );
    }

    /**
     * One href resolved the same way. Anything that is not a fragment — a path
     * like '/about', or a full URL — is returned untouched.
     */
    public static function href(string $href): string
    {
        if (! str_starts_with($href, '#')) {
            return $href;
        }

        if (request()->routeIs('home') || in_array($href, self::EVERYWHERE, true)) {
            return $href;
        }

        // Root-relative rather than url()->to(): the fragment only has to leave
        // the current path behind, and an absolute URL would bake in whatever
        // host the request arrived on.
        return '/'.$href;
    }
}
