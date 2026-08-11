<?php

namespace Tests\Feature;

use Illuminate\Support\Arr;
use Tests\TestCase;

/*
 * Every image path in config/site.php must resolve to a file that exists with
 * exactly that capitalisation.
 *
 * The plain file_exists() check is not enough on its own: development is on
 * macOS, whose filesystem is case-insensitive, while the Hostinger host is
 * Linux and is not. A path that reads `Fit-Out-contracting.webp` against a file
 * named `fit-out-contracting.webp` therefore passes locally and 404s in
 * production — which is exactly how the services panel shipped with a broken
 * hero image. Comparing against the real directory listing catches it on the
 * machine the copy is written on.
 */
class SiteAssetsTest extends TestCase
{
    /** @return list<string> */
    private function imagePaths(): array
    {
        $paths = [];
        $config = config('site');

        array_walk_recursive(
            $config,
            function ($value) use (&$paths) {
                if (is_string($value) && preg_match('#^(images|videos)/#', $value)) {
                    $paths[] = $value;
                }
            }
        );

        return array_values(array_unique($paths));
    }

    public function test_config_site_references_no_missing_images(): void
    {
        $paths = $this->imagePaths();

        $this->assertNotEmpty($paths, 'No image paths found in config/site.php — has the config been restructured?');

        $missing = array_filter(
            $paths,
            fn (string $path) => ! is_file(public_path($path))
        );

        $this->assertSame([], array_values($missing), 'config/site.php references images that do not exist in public/.');
    }

    public function test_image_paths_match_the_case_on_disk(): void
    {
        $wrongCase = [];

        foreach ($this->imagePaths() as $path) {
            $absolute = public_path($path);
            $listing = @scandir(dirname($absolute));

            if ($listing === false) {
                $wrongCase[] = $path.' (directory missing)';

                continue;
            }

            if (! in_array(basename($absolute), $listing, true)) {
                $actual = Arr::first(
                    $listing,
                    fn (string $entry) => strcasecmp($entry, basename($absolute)) === 0
                );

                $wrongCase[] = $actual === null
                    ? $path.' (no file)'
                    : $path.' (on disk: '.$actual.')';
            }
        }

        $this->assertSame([], $wrongCase, 'Image paths differ in case from the files on disk — these 404 on the Linux host but work on macOS.');
    }
}
