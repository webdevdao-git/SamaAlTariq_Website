<?php

namespace App\Console\Commands;

use App\Models\Enquiry;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\ProjectImage;
use App\Models\ProjectStage;
use App\Models\ProjectUpdate;
use App\Models\User;
use App\Services\ProjectFileStorage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Imports the exported Supabase data into MySQL.
 *
 * Supabase keyed everything by UUID; this schema uses auto-increment integers,
 * so the import keeps an in-memory UUID → id map and rewrites every foreign key
 * and every storage path through it. Stored paths begin with the project id
 * (see ProjectFileStorage), so the files have to be re-keyed as they are
 * copied, not just moved.
 *
 * Idempotent: users match on email, projects on title + created_at, and child
 * rows are only written for projects created by this run. Re-running will not
 * duplicate anything.
 *
 * Passwords cannot come across — Supabase keeps its hashes in auth.users, which
 * is not exposed through the REST API. Imported accounts are therefore given a
 * generated password and flagged must_change_password, except any listed in
 * --password (email:password), which lets the known accounts keep working.
 */
class ImportLegacyData extends Command
{
    protected $signature = 'import:legacy
        {--path= : directory holding the exported JSON and files/}
        {--password=* : email:password pairs to set instead of generating}
        {--dry-run : report what would be imported without writing}';

    protected $description = 'Import the exported Supabase data into MySQL';

    /** @var array<string,int> UUID → new integer id */
    private array $userMap = [];
    private array $projectMap = [];

    public function handle(ProjectFileStorage $storage): int
    {
        $path = rtrim($this->option('path') ?: base_path('storage/legacy-export'), '/');

        if (! is_dir($path)) {
            $this->error("No export directory at {$path} — pass --path=");
            return self::FAILURE;
        }

        $dry = (bool) $this->option('dry-run');

        $passwords = [];
        foreach ($this->option('password') as $pair) {
            [$email, $pw] = array_pad(explode(':', $pair, 2), 2, null);
            if ($email && $pw) $passwords[Str::lower(trim($email))] = $pw;
        }

        $read = function (string $table) use ($path): array {
            $file = "{$path}/{$table}.json";
            return is_file($file) ? (json_decode(file_get_contents($file), true) ?: []) : [];
        };

        if ($dry) {
            foreach (['profiles','projects','project_images','project_documents','project_stages','project_updates','enquiries'] as $t) {
                $this->line(sprintf('  %-20s %d rows', $t, count($read($t))));
            }
            return self::SUCCESS;
        }

        DB::transaction(function () use ($read, $passwords, $path, $storage) {
            $this->importUsers($read('profiles'), $passwords);
            $this->importProjects($read('projects'));
            $this->importStages($read('project_stages'));
            $this->importUpdates($read('project_updates'));
            $this->importFiles($read('project_images'), $read('project_documents'), $path, $storage);
            $this->importEnquiries($read('enquiries'));
        });

        $this->newLine();
        $this->info('Import complete.');
        $this->table(['table', 'rows now'], [
            ['users', User::count()],
            ['projects', Project::withTrashed()->count()],
            ['project_images', ProjectImage::count()],
            ['project_documents', ProjectDocument::count()],
            ['project_stages', ProjectStage::count()],
            ['project_updates', ProjectUpdate::count()],
            ['enquiries', Enquiry::count()],
        ]);

        return self::SUCCESS;
    }

    private function importUsers(array $rows, array $passwords): void
    {
        foreach ($rows as $row) {
            $email = Str::lower(trim($row['email'] ?? ''));
            if ($email === '') continue;

            $known = $passwords[$email] ?? null;
            $user = User::firstOrNew(['email' => $email]);

            $user->fill([
                'name' => $row['full_name'] ?: Str::before($email, '@'),
                'username' => $row['username'] ?: null,
                'phone' => $row['phone'] ?: null,
                'job_title' => $row['job_title'] ?: null,
                'can_download' => (bool) ($row['can_download'] ?? false),
                'role' => ($row['role'] ?? 'client') === 'admin' ? 'admin' : 'client',
            ]);

            if (! $user->exists) {
                $user->password = $known ?: Str::password(16, symbols: false);
                // Only accounts whose password we could not carry over are
                // forced to change it.
                $user->must_change_password = $known === null;
            }

            $user->save();
            $this->userMap[$row['id']] = $user->id;

            $this->line(sprintf('  user   %-32s %-7s %s', $email, $user->role,
                $known ? 'password preserved' : 'generated password, must change'));
        }
    }

    private function importProjects(array $rows): void
    {
        foreach ($rows as $row) {
            $project = Project::withTrashed()->firstOrNew([
                'title' => $row['title'],
                'created_at' => $row['created_at'],
            ]);

            $project->fill([
                'client_id' => $this->userMap[$row['client_id'] ?? ''] ?? null,
                'description' => $row['description'] ?: null,
                'location' => $row['location'] ?: null,
                'status' => $row['status'] ?: 'Planning',
                'progress' => (int) ($row['progress'] ?? 0),
                'start_date' => $row['start_date'] ?: null,
                'due_date' => $row['due_date'] ?: null,
                'project_type' => $row['project_type'] ?: null,
            ]);
            $project->created_at = $row['created_at'];
            $project->updated_at = $row['updated_at'] ?? $row['created_at'];
            // Soft deletes carry across, so archived projects stay archived
            // rather than reappearing for their client.
            $project->deleted_at = $row['deleted_at'] ?: null;
            $project->save();

            $this->projectMap[$row['id']] = $project->id;
            $this->line(sprintf('  project %-34s %-12s %3d%%%s', Str::limit($row['title'], 32),
                $project->status, $project->progress, $project->deleted_at ? '  (archived)' : ''));
        }
    }

    private function importStages(array $rows): void
    {
        foreach ($rows as $row) {
            $projectId = $this->projectMap[$row['project_id']] ?? null;
            if (! $projectId) continue;

            ProjectStage::firstOrCreate(
                ['project_id' => $projectId, 'name' => $row['name']],
                [
                    'status' => $row['status'] ?: 'Pending',
                    'target_date' => $row['target_date'] ?: null,
                    'sort_order' => (int) ($row['sort_order'] ?? 0),
                    'created_at' => $row['created_at'],
                ]
            );
        }
        $this->line('  stages  '.count($rows).' imported');
    }

    private function importUpdates(array $rows): void
    {
        foreach ($rows as $row) {
            $projectId = $this->projectMap[$row['project_id']] ?? null;
            if (! $projectId) continue;

            ProjectUpdate::firstOrCreate(
                ['project_id' => $projectId, 'note' => $row['note']],
                ['created_at' => $row['created_at']]
            );
        }
        $this->line('  updates '.count($rows).' imported');
    }

    private function importFiles(array $images, array $documents, string $path, ProjectFileStorage $storage): void
    {
        $disk = Storage::disk(ProjectFileStorage::DISK);
        $copied = $missing = 0;

        $move = function (string $oldPath, int $projectId, string $folder = '') use ($path, $disk, &$copied, &$missing): ?string {
            /*
             * The export flattened "<uuid>/<file>" by replacing the separator.
             * Both spellings are tried because the two exporters that produced
             * these directories disagreed on it, and a silent miss here shows
             * up much later as an empty gallery rather than an error.
             */
            $source = null;
            foreach (['_', '__'] as $separator) {
                $candidate = $path.'/files/'.str_replace('/', $separator, $oldPath);
                if (is_file($candidate)) { $source = $candidate; break; }
            }

            if ($source === null) {
                $this->warn("    missing file for {$oldPath}");
                $missing++;
                return null;
            }

            $name = basename($oldPath);
            $target = trim("{$projectId}/{$folder}", '/').'/'.$name;

            if (! $disk->exists($target)) {
                $disk->put($target, file_get_contents($source));
                $copied++;
            }
            return $target;
        };

        foreach ($images as $row) {
            $projectId = $this->projectMap[$row['project_id']] ?? null;
            if (! $projectId) continue;
            $target = $move($row['storage_path'], $projectId);
            if (! $target) continue;

            ProjectImage::firstOrCreate(
                ['project_id' => $projectId, 'storage_path' => $target],
                ['caption' => $row['caption'] ?: null, 'created_at' => $row['created_at']]
            );
        }

        foreach ($documents as $row) {
            $projectId = $this->projectMap[$row['project_id']] ?? null;
            if (! $projectId) continue;
            $target = $move($row['storage_path'], $projectId, 'reports');
            if (! $target) continue;

            ProjectDocument::firstOrCreate(
                ['project_id' => $projectId, 'storage_path' => $target],
                ['name' => $row['name'] ?: basename($target), 'created_at' => $row['created_at']]
            );
        }

        $this->line("  files   {$copied} copied".($missing ? ", {$missing} MISSING from the export" : ''));
    }

    private function importEnquiries(array $rows): void
    {
        foreach ($rows as $row) {
            Enquiry::firstOrCreate(
                ['email' => $row['email'], 'created_at' => $row['created_at']],
                [
                    'name' => $row['name'],
                    'phone' => $row['phone'] ?: null,
                    'location' => $row['location'] ?: null,
                    'project_type' => $row['project_type'] ?: null,
                    'project_brief' => $row['project_brief'] ?: null,
                    'status' => in_array($row['status'] ?? 'new', ['new','read','archived'], true) ? $row['status'] : 'new',
                ]
            );
        }
        $this->line('  enquiries '.count($rows).' imported');
    }
}
