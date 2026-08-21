<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    /** Every public page, and one project page as a sample of the eleven. */
    private const PAGES = ['/', '/about', '/projects', '/services', '/joinery', '/process', '/contact', '/projects/emirates-hills-villa'];

    public function test_each_page_describes_itself(): void
    {
        $titles = [];

        foreach (self::PAGES as $path) {
            $html = $this->get($path)->assertOk()->getContent();

            preg_match('~<title>(.*?)</title>~s', $html, $title);
            preg_match('~<meta name="description" content="([^"]*)"~', $html, $description);

            $this->assertNotEmpty($title[1] ?? '', "{$path} has a title");
            $this->assertNotEmpty($description[1] ?? '', "{$path} has a description");
            $titles[$path] = $title[1];

            // The sharing tags carry the page's own words rather than the
            // landing page's, which is what they used to do.
            $this->assertStringContainsString('<meta property="og:title" content="'.$title[1].'"', $html, "{$path} shares its own title");
            $this->assertStringContainsString('<meta name="twitter:title" content="'.$title[1].'"', $html);
            $this->assertStringContainsString('<link rel="canonical" href="'.url($path === '/' ? '' : $path).'"', $html, "{$path} is canonical to itself");
        }

        $this->assertSame(count($titles), count(array_unique($titles)), 'no two pages share a title');
    }

    public function test_pages_carry_valid_structured_data(): void
    {
        foreach (['/', '/about'] as $path) {
            $html = $this->get($path)->getContent();
            preg_match('~<script type="application/ld\+json">(.*?)</script>~s', $html, $ld);

            $data = json_decode($ld[1] ?? '', true);
            $this->assertIsArray($data, "{$path} carries parseable JSON-LD");

            $types = array_column($data['@graph'], '@type');
            $this->assertContains('GeneralContractor', $types);
            $this->assertContains($path === '/' ? 'WebSite' : 'BreadcrumbList', $types);
        }
    }

    public function test_the_sitemap_lists_every_public_page(): void
    {
        $xml = $this->get('/sitemap.xml')->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')->getContent();

        foreach (self::PAGES as $path) {
            $this->assertStringContainsString('<loc>'.url($path === '/' ? '' : $path).'</loc>', $xml);
        }

        $this->assertStringNotContainsString('/portal', $xml);
        $this->assertStringNotContainsString('/admin', $xml);
    }

    public function test_robots_keeps_crawlers_out_of_the_private_side(): void
    {
        $robots = $this->get('/robots.txt')->assertOk()->getContent();

        foreach (['/portal', '/admin', '/login'] as $path) {
            $this->assertStringContainsString("Disallow: {$path}", $robots);
        }

        $this->assertStringContainsString('Sitemap: '.url('/sitemap.xml'), $robots);
    }

    public function test_the_private_side_is_not_indexed(): void
    {
        foreach (['/login', '/admin/login'] as $path) {
            $this->assertStringContainsString('noindex', $this->get($path)->getContent(), "{$path} is noindex");
        }
    }
}
