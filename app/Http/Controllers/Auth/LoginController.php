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

    public function login(LoginRequest $request): RedirectResponse
    {
        if ($this->service->login($request->validated(), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

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
