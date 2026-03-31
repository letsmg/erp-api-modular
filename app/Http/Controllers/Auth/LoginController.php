<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Requests\ForgotPasswordRequest;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function __construct(private readonly AuthService $service) {}

    public function showLogin()
    {
        return Inertia::render('Auth/Login', [
            'userIp' => request()->ip(),
            'status' => session('status'),
        ]);
    }

    public function login(LoginRequest $request)
    {
        if ($this->service->login($request->validated(), $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Se for requisição AJAX/API, retorna JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login realizado com sucesso.',
                    'redirect' => route('dashboard')
                ]);
            }
            
            // Se for requisição web normal (Inertia), redireciona
            return redirect()->intended('/dashboard');
        }

        // Se for requisição AJAX/API, retorna erro JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciais invalidas ou conta bloqueada.',
            ], 422);
        }

        // Se for requisição web normal, retorna com erros
        return back()->withErrors([
            'email' => 'Credenciais invalidas ou conta bloqueada.',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->service->logout($request);
        return redirect('/login');
    }

    public function showForgotPassword()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request): RedirectResponse
    {
        $this->service->sendResetLink($request->validated('email'));
        return back()->with('success', 'Link enviado com sucesso!');
    }
}
