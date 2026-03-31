<?php

namespace Tests\Feature\Api;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        // Usar usuário existente ou criar novo
        $user = User::where('email', 'test@test.com')->first();
        if (!$user) {
            $user = User::factory()->create([
                'password' => Hash::make('password')
            ]);
        }

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'test_device'
        ]);

        // Debug: mostrar resposta em caso de erro
        if ($response->status() !== 200) {
            dump([
                'status' => $response->status(),
                'content' => $response->getContent(),
                'user_email' => $user->email,
                'user_active' => $user->is_active
            ]);
        }

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'token',
                        'user',
                        'expires_at'
                    ]
                ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'invalid@test.com',
            'password' => 'wrongpassword',
            'device_name' => 'test_device'
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/v1/auth/logout');

        $response->assertStatus(200);
    }
}
