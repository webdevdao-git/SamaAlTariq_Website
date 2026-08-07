<?php

namespace App\Policies;

use App\Models\User;

/**
 * Account management.
 *
 * Original policies: `profiles_select` (read own, or anything as admin) and
 * `profiles_admin_write` (all writes admin-only).
 */
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, User $model): bool
    {
        // An admin deleting their own account would lock the last one out.
        return $user->isAdmin() && $user->id !== $model->id;
    }
}
