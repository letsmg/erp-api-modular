<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;

class UserController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $users = $this->service->list(auth()->user());
        return Inertia::render('Users/Index', compact('users'));
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $this->service->create($request->validated());
            return redirect()->route('users.index')
                ->with('message', 'Usuário criado com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors([
                'code' => $e->getCode() ?: 500,
                'message' => 'Falha ao criar usuário: ' . $e->getMessage(),
            ]);
        }
    }

    public function edit(User $user)
    {
        if (auth()->user()->access_level !== 1 && auth()->id() !== $user->id) {
            return back()->withErrors([
                'code' => 403,
                'message' => 'Você só pode editar seu próprio perfil.'
            ]);
        }

        return Inertia::render('Users/Edit', ['user' => $user]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $this->service->update($user, $request->validated());
            return redirect()->route('users.index')
                ->with('message', 'Usuário atualizado!');
        } catch (\Exception $e) {
            return back()->withErrors([
                'code' => $e->getCode() ?: 500,
                'message' => 'Falha ao atualizar usuário: ' . $e->getMessage(),
            ]);
        }
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        try {
            $this->service->toggleStatus($user, auth()->user());
            return back()->with('message', 'Status atualizado!');
        } catch (\Exception $e) {
            return back()->withErrors([
                'code' => $e->getCode() ?: 403,
                'message' => 'Não foi possível alterar o status: ' . $e->getMessage(),
            ]);
        }
    }

    public function resetPassword(User $user): RedirectResponse
    {
        try {
            $this->service->resetPassword($user);
            return back()->with('message', 'Senha resetada para: Mudar@123');
        } catch (\Exception $e) {
            return back()->withErrors([
                'code' => $e->getCode() ?: 500,
                'message' => 'Não foi possível resetar a senha: ' . $e->getMessage(),
            ]);
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        $currentUser = auth()->user();

        if ($currentUser->access_level === 0) {
            return back()->withErrors([
                'code' => 403,
                'message' => 'Você não tem permissão para deletar este usuário.'
            ]);
        }

        try {
            $this->service->delete($user, $currentUser);
            return redirect()->route('users.index')
                ->with('message', 'Usuário excluído!');
        } catch (\Exception $e) {
            return back()->withErrors([
                'code' => $e->getCode() ?: 500,
                'message' => 'Falha ao deletar usuário: ' . $e->getMessage(),
            ]);
        }
    }
}