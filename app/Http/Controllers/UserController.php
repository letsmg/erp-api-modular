<?php

namespace App\Http\Controllers;

use App\Modules\User\Models\User;
use App\Modules\User\Requests\StoreUserRequest;
use App\Modules\User\Requests\UpdateUserRequest;
use App\Modules\User\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly UserService $service) {}

    public function index()
    {
        $this->authorize('viewAny', User::class);
        return Inertia::render('Users/Index');
    }

    public function create()
    {
        $this->authorize('create', User::class);
        return Inertia::render('Users/Create');
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);
        $this->service->create($request->validated());
        return redirect()->route('users.index')->with('message', 'Usuario criado com sucesso!');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return Inertia::render('Users/Edit', ['userId' => $user->id]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $this->service->update($user, $request->validated());
        return redirect()->route('users.index')->with('message', 'Usuario atualizado!');
    }

    public function toggleStatus(User $user)
    {
        $this->authorize('toggleStatus', $user);
        $this->service->toggleStatus($user, auth()->user());
        return back()->with('message', 'Status atualizado!');
    }

    public function resetPassword(User $user)
    {
        $this->authorize('resetPassword', $user);
        $this->service->resetPassword($user);
        return back()->with('message', 'Senha resetada para: Mudar@123');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $this->service->delete($user, auth()->user());
        return redirect()->route('users.index')->with('message', 'Usuario excluido!');
    }
}
