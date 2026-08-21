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
