<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\ApiController;
use App\Modules\User\Models\User;
use App\Modules\User\Requests\StoreUserRequest;
use App\Modules\User\Requests\UpdateUserRequest;
use App\Modules\User\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class UserController extends ApiController
{
    use AuthorizesRequests;

    public function __construct(private readonly UserService $service) {}

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        return $this->success($this->service->list(auth()->user()), 'Usuarios carregados com sucesso.');
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        return $this->created($this->service->create($request->validated()), 'Usuario criado com sucesso.');
    }

    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        return $this->success($user);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $this->service->update($user, $request->validated());

        return $this->success($user->refresh(), 'Usuario atualizado com sucesso.');
    }

    public function toggleStatus(User $user): JsonResponse
    {
        $this->authorize('toggleStatus', $user);

        $this->service->toggleStatus($user, auth()->user());

        return $this->success($user->refresh(), 'Status do usuario atualizado com sucesso.');
    }

    public function resetPassword(User $user): JsonResponse
    {
        $this->authorize('resetPassword', $user);

        $this->service->resetPassword($user);

        return $this->success($user->refresh(), 'Senha resetada com sucesso.');
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $this->service->delete($user, auth()->user());

        return $this->deleted('Usuario removido com sucesso.');
    }
}
