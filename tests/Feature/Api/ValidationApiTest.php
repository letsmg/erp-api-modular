<?php

namespace Tests\Feature\Api;

use App\Modules\User\Models\User;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ValidationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_validation_fails_with_invalid_data()
    {
        $responses = [
            $this->postJson('/api/v1/auth/login', []),
            $this->postJson('/api/v1/auth/login', ['email' => 'invalid']),
            $this->postJson('/api/v1/auth/login', ['email' => 'test@test.com']),
            $this->postJson('/api/v1/auth/login', ['email' => 'test@test.com', 'password' => '']),
            $this->postJson('/api/v1/auth/login', ['email' => 'test@test.com', 'password' => '123', 'device_name' => ''])
        ];

        foreach ($responses as $response) {
            $response->assertStatus(422)
                    ->assertJsonStructure([
                        'message',
                        'errors'
                    ]);
        }
    }

    public function test_product_creation_validation_fails()
    {
        $admin = User::factory()->create([
            'access_level' => 1,
            'password' => Hash::make('password')
        ]);
        $token = $admin->createToken('test_token')->plainTextToken;

        $invalidProducts = [
            [], // Vazio
            ['description' => ''], // Descrição vazia
            ['description' => 'Test'], // Sem campos obrigatórios
            ['description' => 'Test', 'sale_price' => 'invalid'], // Preço inválido
            ['description' => 'Test', 'sale_price' => -10], // Preço negativo
            ['description' => 'Test', 'sale_price' => 100, 'category_id' => 999], // Categoria inexistente
        ];

        foreach ($invalidProducts as $productData) {
            $response = $this->withHeaders([
                'Authorization' => "Bearer $token"
            ])->postJson('/api/v1/products', $productData);

            $response->assertStatus(422)
                    ->assertJsonStructure([
                        'message',
                        'errors'
                    ]);
        }
    }

    public function test_user_creation_validation_fails()
    {
        $admin = User::factory()->create([
            'access_level' => 1,
            'password' => Hash::make('password')
        ]);
        $token = $admin->createToken('test_token')->plainTextToken;

        $invalidUsers = [
            [], // Vazio
            ['name' => ''], // Nome vazio
            ['name' => 'Test'], // Sem email
            ['name' => 'Test', 'email' => 'invalid'], // Email inválido
            ['name' => 'Test', 'email' => 'test@test.com'], // Sem senha
            ['name' => 'Test', 'email' => 'test@test.com', 'password' => '123'], // Senha curta
            ['name' => 'Test', 'email' => 'existing@test.com', 'password' => 'password123'], // Email duplicado
        ];

        // Criar usuário com email para teste de duplicação
        User::factory()->create([
            'email' => 'existing@test.com',
            'password' => Hash::make('password')
        ]);

        foreach ($invalidUsers as $userData) {
            $response = $this->withHeaders([
                'Authorization' => "Bearer $token"
            ])->postJson('/api/v1/users', $userData);

            $response->assertStatus(422)
                    ->assertJsonStructure([
                        'message',
                        'errors'
                    ]);
        }
    }

    public function test_api_returns_proper_error_format()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nonexistent@test.com',
            'password' => 'wrongpassword',
            'device_name' => 'test'
        ]);

        $response->assertStatus(422);
        
        // Verificar se contém a estrutura básica de erro
        $data = $response->json();
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('errors', $data);
    }
}
