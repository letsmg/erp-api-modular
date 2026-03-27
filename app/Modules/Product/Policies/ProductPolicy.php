<?php

namespace App\Modules\Product\Policies;

use App\Modules\Product\Models\Product;
use App\Modules\User\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, Product $product): bool
    {
        return $user->isStaff();
    }

    public function create(User $user): bool
    {
        return $user->isStaff();
    }

    public function update(User $user, Product $product): bool
    {
        return $user->isStaff();
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->isAdmin();
    }

    public function toggle(User $user, Product $product): bool
    {
        return $user->isAdmin();
    }
}
