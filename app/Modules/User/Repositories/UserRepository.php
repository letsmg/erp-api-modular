<?php

namespace App\Modules\User\Repositories;

use App\Helpers\SanitizerHelper;
use App\Modules\User\Models\User;

class UserRepository
{
    public function getAllOrdered()
    {
        return User::orderBy('name')->get();
    }

    public function getNonAdmin()
    {
        return User::where('access_level', 0)
            ->orderBy('name')
            ->get();
    }

    public function create(array $data): User
    {
        $data = SanitizerHelper::sanitize($data);
        return User::create($data);
    }

    public function update(User $user, array $data): void
    {
        $data = SanitizerHelper::sanitize($data);
        $user->update($data);
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
