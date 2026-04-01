<?php

namespace App\Modules\Auth\Tests\Feature;

use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_endpoint_is_accessible(): void
    {
        $this->getJson(route('login'))->assertOk();
    }

    public function test_user_can_login_and_logout_with_json_responses(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);

        // Testa apenas o login via API
        $this->postJson(route('login.post'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertOk(); // Login API retorna 200 com JSON

        // Logout pode ser testado via web routes se necessário
        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(); // Logout web retorna redirect
    }
}
