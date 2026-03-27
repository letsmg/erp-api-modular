<?php

namespace App\Modules\User\Services;

use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function __construct(private readonly UserRepository $repository) {}

    public function list(User $currentUser)
    {
        if (! $currentUser->isAdmin()) {
            return $this->repository->getNonAdmin();
        }

        return $this->repository->getAllOrdered();
    }

    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return $this->repository->create($data);
    }

    public function update(User $user, array $data): void
    {
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $this->repository->update($user, $data);
    }

    public function toggleStatus(User $user, User $currentUser): void
    {
        if ($user->id === $currentUser->id) {
            throw ValidationException::withMessages([
                'error' => 'Voce nao pode desativar a si mesmo.',
            ]);
        }

        $this->repository->update($user, [
            'is_active' => ! $user->is_active,
        ]);
    }

    public function resetPassword(User $user): void
    {
        $this->repository->update($user, [
            'password' => Hash::make('Mudar@123'),
        ]);
    }

    public function delete(User $user, User $currentUser): void
    {
        if ($user->id === $currentUser->id) {
            throw ValidationException::withMessages([
                'error' => 'Voce nao pode se excluir.',
            ]);
        }

        $this->repository->delete($user);
    }
}
