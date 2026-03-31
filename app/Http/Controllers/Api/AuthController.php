<?php

namespace App\Http\Controllers\Api;

use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends \App\Http\Controllers\Controller
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
            return response()->json([
                'success' => false,
                'message' => 'Sua conta está inativa.',
                'data' => null
            ], 403);
        }

        // Revoga tokens anteriores do mesmo dispositivo
        $user->tokens()->where('name', $request->device_name)->delete();

        $token = $user->createToken($request->device_name);

        return response()->json([
            'success' => true,
            'message' => 'Login realizado com sucesso.',
            'data' => [
                'token' => $token->plainTextToken,
                'user' => $user,
                'expires_at' => $token->accessToken->expires_at,
            ]
        ]);
    }

    /**
     * Logout do usuário (revoga token)
     */
    public function logout(Request $request): JsonResponse
    {
        // Verifica se usuário está autenticado
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado.',
                'data' => null
            ], 401);
        }

        // Revoga o token atual
        $token = $user->currentAccessToken();
        if ($token) {
            $token->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso.',
            'data' => null
        ]);
    }

    /**
     * Logout de todos os dispositivos
     */
    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado em todos os dispositivos.',
            'data' => null
        ]);
    }

    /**
     * Obter usuário autenticado
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Dados do usuário.',
            'data' => $request->user()
        ]);
    }

    /**
     * Listar tokens ativos
     */
    public function tokens(Request $request): JsonResponse
    {
        $tokens = $request->user()->tokens()->get(['id', 'name', 'created_at', 'last_used_at', 'expires_at']);

        return response()->json([
            'success' => true,
            'message' => 'Tokens ativos.',
            'data' => $tokens
        ]);
    }

    /**
     * Revogar token específico
     */
    public function revokeToken(Request $request, $tokenId): JsonResponse
    {
        $token = $request->user()->tokens()->findOrFail($tokenId);
        $token->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token revogado com sucesso.',
            'data' => null
        ]);
    }
}
