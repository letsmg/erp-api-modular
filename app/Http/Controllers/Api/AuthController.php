<?php

namespace App\Http\Controllers\Api;

use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends \App\Http\Controllers\ApiController
{
    /**
     * Login do usuário e geração de token
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required|string|max:255',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        if (!$user->is_active) {
            return $this->error(null, 'Sua conta está inativa.', 403);
        }

        // Revoga tokens anteriores do mesmo dispositivo
        $user->tokens()->where('name', $request->device_name)->delete();

        $token = $user->createToken($request->device_name);

        return $this->success([
            'token' => $token->plainTextToken,
            'user' => $user,
            'expires_at' => $token->accessToken->expires_at,
        ], 'Login realizado com sucesso.');
    }

    /**
     * Logout do usuário (revoga token)
     */
    public function logout(Request $request): JsonResponse
    {
        // Verifica se usuário está autenticado
        $user = $request->user();
        if (!$user) {
            return $this->error(null, 'Usuário não autenticado.', 401);
        }

        // Revoga o token atual
        $token = $user->currentAccessToken();
        if ($token) {
            $token->delete();
        }

        return $this->success(null, 'Logout realizado com sucesso.');
    }

    /**
     * Logout de todos os dispositivos
     */
    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return $this->success(null, 'Logout realizado em todos os dispositivos.');
    }

    /**
     * Obter usuário autenticado
     */
    public function me(Request $request): JsonResponse
    {
        return $this->success($request->user(), 'Dados do usuário.');
    }

    /**
     * Listar tokens ativos
     */
    public function tokens(Request $request): JsonResponse
    {
        $tokens = $request->user()->tokens()->get(['id', 'name', 'created_at', 'last_used_at', 'expires_at']);

        return $this->success($tokens, 'Tokens ativos.');
    }

    /**
     * Revogar token específico
     */
    public function revokeToken(Request $request, $tokenId): JsonResponse
    {
        $token = $request->user()->tokens()->findOrFail($tokenId);
        $token->delete();

        return $this->success(null, 'Token revogado com sucesso.');
    }
}
