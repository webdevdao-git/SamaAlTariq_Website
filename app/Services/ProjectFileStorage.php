<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Private file storage for project images and reports.
 *
 * Files live on the `local` disk (storage/app/private), which is outside the
 * document root — the only way out is FileController, which checks project
 * access first. This replaces the Supabase storage bucket and its policies, and
 * keeps the same path convention so stored paths stay meaningful:
 *
 *   <project_id>/<filename>            project images
 *   <project_id>/reports/<filename>    project documents
 */
class ProjectFileStorage
{
    public const DISK = 'local';

    /** Allow-listed by extension AND mime, since either alone is spoofable. */
    private const ALLOWED = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp',
        'avif' => 'image/avif',
        'gif' => 'image/gif',
        'pdf' => 'application/pdf',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'txt' => 'text/plain',
    ];

    public function store(UploadedFile $file, int $projectId, string $folder = ''): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (! array_key_exists($extension, self::ALLOWED)) {
            throw new HttpException(415, "Unsupported file type: .{$extension}");
        }

        // A uuid prefix keeps two uploads of "photo.jpg" from colliding, and
        // strips any path or control characters out of the original name.
        $base = Str::of($file->getClientOriginalName())
            ->beforeLast('.')
            ->slug()
            ->limit(60, '');

        $name = Str::uuid().($base->isNotEmpty() ? "-{$base}" : '').".{$extension}";
        $directory = trim("{$projectId}/{$folder}", '/');

        return $file->storeAs($directory, $name, self::DISK);
    }

    public function delete(?string $path): void
    {
        if ($path) {
            Storage::disk(self::DISK)->delete($path);
        }
    }

    /** Removes every file for a project — used when a project is hard-deleted. */
    public function deleteProject(int $projectId): void
    {
        Storage::disk(self::DISK)->deleteDirectory((string) $projectId);
    }

    /**
     * The project a stored path belongs to. Mirrors the
     * `storage.foldername(name)[1]` check the Supabase policy used.
     */
    public static function projectIdFor(string $path): ?int
    {
        $first = Str::before($path, '/');

        return ctype_digit($first) ? (int) $first : null;
    }
}
