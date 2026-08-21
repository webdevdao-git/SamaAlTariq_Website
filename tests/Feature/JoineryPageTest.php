<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JoineryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_page_names_the_partner(): void
    {
        $this->get('/joinery')
            ->assertOk()
            ->assertSee(config('site.joinery_page.partner.name'))
            ->assertSee(config('site.joinery_page.partner.descriptor'));
    }

    /**
     * The split under the title gives its left half to the partner's mark —
     * the first picture on the page, which is what the page was asked for.
     * While no artwork exists that half sets the name as type instead, and
     * the one thing it must never do is draw an <img> at a path with no file
     * behind it.
     */
    public function test_the_first_picture_is_the_partner_mark(): void
    {
        $logo = config('site.joinery_page.partner.logo');
        $html = $this->get('/joinery')->assertOk()->getContent();

        if ($logo === null) {
            $this->assertStringNotContainsString('images/partners/', $html, 'no mark is drawn while there is no file');

            return;
        }

        $this->assertFileExists(public_path($logo), 'the configured mark exists on disk');

        /*
         * First of the page's OWN pictures — not first in the markup. Every
         * page carries this company's lock-up twice before any content: the
         * preloader's mark and the header's. Asserting position zero passed
         * only while the partner mark did not exist, and would have failed
         * the moment it did.
         */
        preg_match_all('~<img[^>]+src="([^"?]+)~', $html, $images);
        $content = array_values(array_filter(
            $images[1],
            fn (string $src) => ! str_contains($src, 'images/logo-mark'),
        ));

        $this->assertStringContainsString($logo, $content[0] ?? '', 'the mark is the first picture the page draws');
    }

    /** The bands run in the reference page's order. */
    public function test_the_bands_run_in_order(): void
    {
        $html = $this->get('/joinery')->assertOk()->getContent();

        $marks = [
            config('site.joinery_page.hero.panel.heading'),
            config('site.joinery_page.scope.heading'),
            config('site.joinery_page.package.title'),
            config('site.joinery_page.studio.heading'),
            config('site.joinery_page.faqs.0.q'),
            config('site.joinery_page.gallery.heading'),
        ];

        $at = -1;
        foreach ($marks as $mark) {
            $found = strpos($html, e($mark));
            $this->assertNotFalse($found, "{$mark} is on the page");
            $this->assertGreaterThan($at, $found, "{$mark} follows the band before it");
            $at = $found;
        }
    }

    /**
     * The card on the dark band quotes no price. A joinery package is priced
     * against its drawings, so a figure here would be one nobody could stand
     * behind — the card says so instead.
     */
    public function test_the_package_card_quotes_no_price(): void
    {
        $html = $this->get('/joinery')->assertOk()->getContent();

        $this->assertStringContainsString(e(config('site.joinery_page.package.summary')), $html);
        $this->assertDoesNotMatchRegularExpression('~(AED|USD|\$)\s?[0-9]~', strip_tags($html), 'no figure is quoted');
    }

    /**
     * The scope is the services page's own entries, filtered by number. If a
     * number in the config stops matching a service — renumbered, removed —
     * the section would render empty rather than loudly, so it is asserted.
     */
    public function test_the_scope_is_the_services_pages_own_entries(): void
    {
        $numbers = config('site.joinery_page.scope.numbers');
        $html = $this->get('/joinery')->assertOk()->getContent();

        $matched = array_filter(
            config('site.services_page.services'),
            fn (array $service) => in_array($service['number'], $numbers, true),
        );

        $this->assertCount(count($numbers), $matched, 'every number in the config matches a service');

        foreach ($matched as $service) {
            $this->assertStringContainsString(e($service['lead']), $html, "service {$service['number']} is quoted");
        }
    }

    /** Each closing tile is a real project with a cover on disk. */
    public function test_the_closing_tiles_resolve_to_projects(): void
    {
        $slugs = config('site.joinery_page.gallery.projects');
        $html = $this->get('/joinery')->assertOk()->getContent();

        foreach ($slugs as $slug) {
            $this->assertStringContainsString(route('projects.show', $slug), $html, "{$slug} is linked");
            $this->assertFileExists(public_path("images/projects/covers/{$slug}.webp"), "{$slug} has a cover");
        }
    }

    /**
     * Nothing on this page may claim anything about the partner that was not
     * given. The three unknown facts are null in the config, and the band that
     * would draw them must stay out of the markup while they are.
     */
    public function test_unknown_partner_details_are_not_invented(): void
    {
        $html = $this->get('/joinery')->getContent();

        foreach (['established', 'location', 'workshop'] as $fact) {
            if (config("site.joinery_page.partner.{$fact}") === null) {
                $this->assertStringNotContainsString('>'.ucfirst($fact).'</dt>', $html, "{$fact} is not drawn while it is unknown");
            }
        }
    }

    /** The menu carries the entry, on every page rather than only this one. */
    public function test_the_menu_carries_joinery(): void
    {
        foreach (['/', '/about', '/joinery'] as $path) {
            $this->get($path)
                ->assertOk()
                ->assertSee('>Joinery', false)
                ->assertSee('href="/joinery"', false);
        }
    }

    public function test_the_page_is_in_the_sitemap(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee(url('/joinery'));
    }
}
