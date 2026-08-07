<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\ProjectFileStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    /**
     * The only route out of private storage.
     *
     * This is the replacement for the Supabase storage policies: the first path
     * segment is the project id, and the `view` policy applies exactly the
     * predicate `storage.foldername(name)[1] = p.id AND p.client_id = auth.uid()`
     * did.
     *
     * `?download=1` forces an attachment and is refused for clients whose
     * can_download flag is off — the view-only rule from the original schema.
     */
    public function show(Request $request, string $path): StreamedResponse
    {
        $projectId = ProjectFileStorage::projectIdFor($path);
        abort_if($projectId === null, 404);

        $project = Project::withTrashed()->find($projectId);
        abort_if($project === null, 404);

        // Returns 403; a client should not be able to probe which project ids
        // exist by watching for a different status code.
        $this->authorize('view', $project);

        $disk = Storage::disk(ProjectFileStorage::DISK);
        abort_unless($disk->exists($path), 404);

        $wantsDownload = $request->boolean('download');

        if ($wantsDownload) {
            $this->authorize('download', $project);
        }

        return $disk->response(
            $path,
            basename($path),
            [
                // Client-confidential: no shared cache may hold a copy, and the
                // browser must revalidate the session every time.
                'Cache-Control' => 'private, no-store',
            ],
            $wantsDownload ? 'attachment' : 'inline',
        );
    }
}
