<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\ApiController;
use App\Modules\Auth\Requests\ForgotPasswordRequest;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends ApiController
{
    public function __construct(private readonly AuthService $service) {}

    public function showLogin(): JsonResponse
    {
        return $this->success([
            'message' => 'Envie email e password para autenticar.',
            'authenticated' => auth()->check(),
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (! $this->service->login($request->validated(), $request->boolean('remember'))) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciais invalidas ou conta bloqueada.',
            ], 422);
        }

        $request->session()->regenerate();

        return $this->success($request->user(), 'Login realizado com sucesso.');
    }

    public function me(Request $request): JsonResponse
    {
        return $this->success($request->user());
    }

    public function logout(Request $request): JsonResponse
    {
        $this->service->logout($request);

        return $this->success(null, 'Logout realizado com sucesso.');
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $this->service->sendResetLink($request->validated('email'));

        return $this->success(null, 'Link de recuperacao enviado com sucesso.');
    }
}
