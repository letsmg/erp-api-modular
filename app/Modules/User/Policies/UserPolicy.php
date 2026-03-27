<?php

namespace App\Modules\User\Policies;

use App\Modules\User\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
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
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }

        return $user->isAdmin();
    }

    public function toggleStatus(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }

        return $user->isAdmin();
    }

    public function resetPassword(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}
