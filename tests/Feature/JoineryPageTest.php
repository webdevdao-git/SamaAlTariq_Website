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
     * The frame has no slot of its own for the partner's mark, so it takes the
     * label line above the two words — the line that names the company. While
     * no artwork exists that line falls back to the label as type, and the one
     * thing it must never do is draw an <img> at a path with no file behind it.
     */
    public function test_the_partner_mark_is_drawn(): void
    {
        $logo = config('site.joinery_page.partner.logo');
        $html = $this->get('/joinery')->assertOk()->getContent();

        if ($logo === null) {
            $this->assertStringNotContainsString('images/partners/', $html, 'no mark is drawn while there is no file');
            $this->assertStringContainsString(e(config('site.joinery_page.wordmark.label')), $html, 'the label stands in its place');

            return;
        }

        $this->assertFileExists(public_path($logo), 'the configured mark exists on disk');
        $this->assertStringContainsString($logo, $html, 'the mark is drawn');
    }

    /** The bands run in the frame's order. */
    public function test_the_bands_run_in_order(): void
    {
        // From <body> on. The page's own description is the hero summary, so
        // "Bespoke" appears in a meta tag 300 bytes in — ahead of every band,
        // which made the first comparison fail on a page that was in order.
        $html = substr(strstr($this->get('/joinery')->assertOk()->getContent(), '<body'), 0);

        /*
         * One mark per band, each unique to it. The obvious choices are not:
         * the hero's summary opens with "Bespoke", which is also the wordmark
         * band's first word, so that pair compared a band against itself.
         */
        $marks = [
            config('site.joinery_page.hero.words.1'),
            config('site.joinery_page.wordmark.images.0.alt'),
            config('site.joinery_page.ecosystem.statement'),
            config('site.joinery_page.capabilities.heading.1'),
            config('site.joinery_page.detail.words.0'),
            config('site.joinery_page.process.heading'),
            config('site.joinery_page.faqs.items.0.q'),
        ];

        $at = -1;
        foreach ($marks as $i => $mark) {
            // A renamed config key returns null, and strpos(…, '') is 0 —
            // which reads as "found, at the very top" and fails the next
            // comparison with nothing to say about why. This is that message.
            $this->assertNotEmpty($mark, "mark {$i} still resolves to a config value");

            $found = strpos($html, e($mark));
            $this->assertNotFalse($found, "{$mark} is on the page");
            $this->assertGreaterThan($at, $found, "{$mark} follows the band before it");
            $at = $found;
        }
    }

    /**
     * The first two capabilities are the services page's own joinery entries,
     * filtered by number. If a number in the config stops matching a service —
     * renumbered, removed — the band would quietly render two rows instead of
     * three rather than fail, so it is asserted.
     */
    public function test_the_capabilities_quote_the_services_page(): void
    {
        $numbers = config('site.joinery_page.capabilities.numbers');
        $html = $this->get('/joinery')->assertOk()->getContent();

        $matched = array_filter(
            config('site.services_page.services'),
            fn (array $service) => in_array($service['number'], $numbers, true),
        );

        $this->assertCount(count($numbers), $matched, 'every number in the config matches a service');

        foreach ($matched as $service) {
            $this->assertStringContainsString(e($service['body']), $html, "service {$service['number']} is quoted");
        }

        // And the frame's third row, which the services page does not carry.
        $this->assertStringContainsString(e(config('site.joinery_page.capabilities.third.title')), $html);

        // Numbered by position in this band, not by position on that page.
        foreach (['01', '02', '03'] as $n) {
            $this->assertStringContainsString(">{$n}<", $html, "the band numbers its own rows: {$n}");
        }
    }

    /** Every question on the page carries an answer. */
    public function test_each_question_has_an_answer(): void
    {
        $html = $this->get('/joinery')->assertOk()->getContent();

        foreach (config('site.joinery_page.faqs.items') as $faq) {
            $this->assertStringContainsString(e($faq['q']), $html);
            $this->assertStringContainsString(e($faq['a']), $html);
        }
    }

    /**
     * Nothing on this page may claim anything about the partner that was not
     * given. The three unknown facts are null in the config, and nothing that
     * would draw them may appear while they are.
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
