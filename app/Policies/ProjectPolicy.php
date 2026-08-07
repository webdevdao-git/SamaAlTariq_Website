<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

/**
 * The authorization rules that used to be Postgres Row Level Security.
 *
 * Original policies and their equivalents here:
 *   projects_select    → view / viewAny  (admin sees all; client sees own, live)
 *   projects_admin_all → every write method, admin only
 *
 * `before()` short-circuits for admins so each method only has to express the
 * client rule — the place where mistakes actually happen.
 */
class ProjectPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return true; // scoped by Project::visibleTo() in the query
    }

    public function view(User $user, Project $project): bool
    {
        // A soft-deleted project is invisible to its client but still readable
        // by an admin (handled by before()).
        return $project->client_id === $user->id && $project->deleted_at === null;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Project $project): bool
    {
        return false;
    }

    public function delete(User $user, Project $project): bool
    {
        return false;
    }

    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Whether the user may pull an actual file, as opposed to viewing a
     * thumbnail. Kept separate from view() so the can_download flag stays a
     * single decision made in one place.
     */
    public function download(User $user, Project $project): bool
    {
        return $this->view($user, $project) && $user->canDownloadFiles();
    }
}
