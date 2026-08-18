<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_feed_shows_five_and_folds_the_rest(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        foreach (range(1, 9) as $i) {
            Project::create([
                'title' => "Villa {$i}",
                'user_id' => $client->id,
                'status' => 'Planning',
            ]);
        }

        $html = $this->actingAs($admin)->get('/admin/projects')->assertOk()->getContent();

        $panel = substr($html, strpos($html, 'RECENT ACTIVITY'));
        $panel = substr($panel, 0, strpos($panel, 'PROJECT OVERVIEW'));

        $before = substr($panel, 0, strpos($panel, '<details') ?: strlen($panel));
        $inside = strpos($panel, '<details') !== false ? substr($panel, strpos($panel, '<details')) : '';

        $this->assertSame(5, substr_count($before, '<li class="flex gap-3">'), 'five entries are shown up front');
        $this->assertNotSame('', $inside, 'the rest are folded away');
        $this->assertGreaterThan(0, substr_count($inside, '<li class="flex gap-3">'), 'the fold carries the remainder');
        $this->assertStringContainsString('Show all', $inside);
        $this->assertStringContainsString('Show less', $inside);
    }

    public function test_a_short_feed_has_no_toggle(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);
        Project::create(['title' => 'Villa 1', 'user_id' => $client->id, 'status' => 'Planning']);

        $html = $this->actingAs($admin)->get('/admin/projects')->assertOk()->getContent();
        $panel = substr($html, strpos($html, 'RECENT ACTIVITY'));
        $panel = substr($panel, 0, strpos($panel, 'PROJECT OVERVIEW'));

        $this->assertStringNotContainsString('<details', $panel);
        $this->assertStringNotContainsString('Show all', $panel);
    }
}
