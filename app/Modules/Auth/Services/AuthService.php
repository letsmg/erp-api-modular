<?php

namespace App\Modules\Auth\Services;

use App\Enums\AccessLevel;
use App\Mail\RecuperarSenhaMail;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthService
{
    private function validateGeographicAccess(): void
    {
        $ip = request()->ip();

        if ($ip === '127.0.0.1' || $ip === '::1') {
            return;
        }

        try {
            $response = Http::timeout(2)->get("http://ip-api.com/json/{$ip}?fields=status,countryCode,message");

            if ($response->successful() && $response->json('status') === 'success') {
                $countryCode = $response->json('countryCode');

                if ($countryCode !== 'BR') {
                    throw ValidationException::withMessages([
                        'email' => ['Acesso negado: Este sistema nao aceita logins fora do Brasil.'],
                    ]);
                }
            }
        } catch (\Exception $e) {
            logger()->error('Falha no servico de verificacao de IP: '.$e->getMessage());
        }
    }

    public function login(array $credentials, bool $remember = false): bool
    {
        $this->validateGeographicAccess();

        $user = User::where('email', $credentials['email'])->first();

        if (! $user) {
            return false;
        }

        if (! Hash::check($credentials['password'], $user->password)) {
            return false;
        }

        if (! $user->is_active) {
            return false;
        }

        if ($user->access_level === AccessLevel::CLIENT) {
            return false;
        }

        Auth::login($user, $remember);

        $user->update(['last_login_ip' => request()->ip()]);

        return true;
    }

    public function logout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function sendResetLink(string $email): void
    {
        $url = route('login');
        Mail::to($email)->send(new RecuperarSenhaMail($url));
    }
}
