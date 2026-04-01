<?php

namespace App\Http\Controllers;

use App\Modules\User\Models\User;
use App\Modules\User\Requests\StoreUserRequest;
use App\Modules\User\Requests\UpdateUserRequest;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly UserService $service,
        private readonly UserRepository $repository
    ) {}

    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        $filters = request()->all(['search']);
        $users = $this->repository->getPaginated($filters);
        
        return Inertia::render('Users/Index', [
            'initialFilters' => $filters,
            'users' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
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
        
        // Verifica se é requisição Inertia (headers específicos ou accept header)
        $isInertia = $request->header('x-inertia') || 
                    $request->header('x-inertia-version') ||
                    $request->hasHeader('X-Inertia') ||
                    str_contains($request->header('accept', ''), 'text/html');
        
        // Se for requisição Inertia, redireciona com flash message
        if ($isInertia) {
            return redirect()->route('users.index')->with('message', 'Usuario criado com sucesso!');
        }
        
        // Se for requisição API, retorna JSON
        return response()->json([
            'success' => true,
            'message' => 'Usuario criado com sucesso!',
            'data' => null
        ], 201);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        // Se for requisição Inertia, renderiza a página
        if (request()->inertia()) {
            return Inertia::render('Users/Show', ['user' => $user]);
        }
        
        // Se for requisição API, retorna JSON
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
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
        
        // Verifica se é requisição Inertia (headers específicos ou accept header)
        $isInertia = $request->header('x-inertia') || 
                    $request->header('x-inertia-version') ||
                    $request->hasHeader('X-Inertia') ||
                    str_contains($request->header('accept', ''), 'text/html');
        
        // Se for requisição Inertia, redireciona com flash message
        if ($isInertia) {
            return redirect()->route('users.index')->with('message', 'Usuario atualizado!');
        }
        
        // Se for requisição API, retorna JSON
        return response()->json([
            'success' => true,
            'message' => 'Usuario atualizado com sucesso!',
            'data' => $user->refresh()
        ]);
    }

    public function toggleStatus(User $user)
    {
        $this->authorize('toggleStatus', $user);
        $updatedUser = $this->service->toggleStatus($user, auth()->user());
        
        return request()->inertia()
            ? back()->with([
                'message' => 'Status atualizado!',
                'user' => $updatedUser,
            ])
            : response()->json([
                'message' => 'Status atualizado!',
                'data' => $updatedUser,
            ]);
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
        
        // Verifica se é requisição Inertia (headers específicos)
        $isInertia = $request->header('x-inertia') || 
                    $request->header('x-inertia-version') ||
                    $request->hasHeader('X-Inertia');
        
        // Se for requisição Inertia, redireciona
        if ($isInertia) {
            return redirect()->route('users.index')->with('message', 'Usuario excluido!');
        }
        
        // Se for requisição API, retorna JSON
        return $this->deleted('Usuario excluido com sucesso!');
    }

    /**
     * Retorna resposta JSON de recurso excluído (status 204)
     */
    protected function deleted(string $message = 'Resource deleted successfully')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => null
        ], 204);
    }
}
