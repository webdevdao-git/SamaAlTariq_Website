<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\ProjectImage;
use App\Services\ProjectFileStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Images Upload and Reports Upload.
 *
 * Both screens share this controller because they are the same operation
 * against two tables — pick a project, upload files, review what is there.
 */
class MediaController extends Controller
{
    public function images(Request $request): View
    {
        $projects = Project::orderBy('title')->get(['id', 'title']);
        $projectId = $request->integer('project') ?: null;

        $query = ProjectImage::with('project:id,title')->latest();
        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        if (filled($term = $request->string('q')->trim()->value())) {
            $like = '%'.addcslashes($term, '%_\\').'%';
            $query->where(fn ($q) => $q->where('caption', 'like', $like)->orWhere('storage_path', 'like', $like));
        }

        return view('admin.media.images', [
            'projects' => $projects,
            'projectId' => $projectId,
            'items' => $query->get(),
            'recent' => ProjectImage::with('project:id,title')->latest()->take(4)->get(),
            'totalCount' => ProjectImage::when($projectId, fn ($q) => $q->where('project_id', $projectId))->count(),
        ]);
    }

    public function reports(Request $request): View
    {
        $projectId = $request->integer('project') ?: null;

        return view('admin.media.reports', [
            'projects' => Project::orderBy('title')->get(['id', 'title']),
            'projectId' => $projectId,
            // Deliberately empty until a project is picked, as in the reference:
            // reports are per-project and a mixed list invites the wrong upload.
            'items' => $projectId
                ? ProjectDocument::where('project_id', $projectId)->latest()->get()
                : collect(),
        ]);
    }

    public function storeImages(Request $request, ProjectFileStorage $storage): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', Rule::exists('projects', 'id')],
            'files' => ['required', 'array', 'max:20'],
            'files.*' => ['file', 'image', 'max:15360'],
            'caption' => ['nullable', 'string', 'max:300'],
        ]);

        foreach ($validated['files'] as $file) {
            ProjectImage::create([
                'project_id' => $validated['project_id'],
                'storage_path' => $storage->store($file, $validated['project_id']),
                // One caption across a multi-file upload would be wrong for all
                // but the first, so it only applies to a single file.
                'caption' => count($validated['files']) === 1 ? ($validated['caption'] ?? null) : null,
            ]);
        }

        return back()->with('status', count($validated['files']).' '.Str::plural('image', count($validated['files'])).' uploaded.');
    }

    public function storeReports(Request $request, ProjectFileStorage $storage): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', Rule::exists('projects', 'id')],
            'files' => ['required', 'array', 'max:20'],
            'files.*' => ['file', 'mimes:pdf,doc,docx,xls,xlsx,txt', 'max:15360'],
        ]);

        foreach ($validated['files'] as $file) {
            ProjectDocument::create([
                'project_id' => $validated['project_id'],
                'storage_path' => $storage->store($file, $validated['project_id'], 'reports'),
                'name' => $file->getClientOriginalName(),
            ]);
        }

        return back()->with('status', count($validated['files']).' '.Str::plural('report', count($validated['files'])).' uploaded.');
    }

    public function destroyImage(ProjectImage $image, ProjectFileStorage $storage): RedirectResponse
    {
        // The file goes with the row; leaving it behind fills the disk with
        // objects nothing references.
        $storage->delete($image->storage_path);
        $image->delete();

        return back()->with('status', 'Image removed.');
    }

    public function destroyReport(ProjectDocument $document, ProjectFileStorage $storage): RedirectResponse
    {
        $storage->delete($document->storage_path);
        $document->delete();

        return back()->with('status', 'Report removed.');
    }
}
