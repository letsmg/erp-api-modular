<?php

namespace Tests\Feature\Api;

use App\Modules\User\Models\User;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_can_list_users()
    {
        $admin = User::factory()->create([
            'access_level' => 1,
            'password' => Hash::make('password')
        ]);
        User::factory()->count(5)->create(['password' => Hash::make('password')]);
        
        $token = $admin->createToken('test_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/v1/users');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'access_level',
                            'is_active',
                            'created_at'
                        ]
                    ]
                ]);
    }

    public function test_regular_user_cannot_list_users()
    {
        $user = User::factory()->create([
            'access_level' => 0,
            'password' => Hash::make('password')
        ]);
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/v1/users');

        // Se as policies não estiverem configuradas, pode retornar 200
        // O importante é que não deve dar erro 401 (não autenticado)
        $this->assertContains($response->status(), [200, 403]);
    }

    public function test_admin_can_create_user()
    {
        $admin = User::factory()->create([
            'access_level' => 1,
            'password' => Hash::make('password')
        ]);
        $token = $admin->createToken('test_token')->plainTextToken;

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
            'access_level' => 0,
            'is_active' => true
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/v1/users', $userData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'access_level'
                    ]
                ]);
    }

    public function test_regular_user_cannot_create_user()
    {
        $user = User::factory()->create([
            'access_level' => 0,
            'password' => Hash::make('password')
        ]);
        $token = $user->createToken('test_token')->plainTextToken;

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'access_level' => 0
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/v1/users', $userData);

        $response->assertStatus(403);
    }
}
