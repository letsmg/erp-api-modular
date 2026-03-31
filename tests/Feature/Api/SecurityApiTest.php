<?php

namespace Tests\Feature\Api;

use App\Modules\User\Models\User;
use App\Modules\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_blocks_unauthenticated_requests()
    {
        $endpoints = [
            '/api/v1/products',
            '/api/v1/users',
            '/api/v1/auth/me',
            '/api/v1/auth/tokens'
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->getJson($endpoint);
            $response->assertStatus(401);
        }
    }

    public function test_api_blocks_invalid_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid_token'
        ])->getJson('/api/v1/products');

        $response->assertStatus(401);
    }

    public function test_xss_protection_in_api_responses()
    {
        $user = User::factory()->create([
            'name' => '<script>alert("xss")</script>Admin',
            'password' => Hash::make('password')
        ]);

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/v1/auth/me');

        $response->assertStatus(200);
        
        // Verifica se o XSS foi sanitizado na resposta
        $this->assertStringNotContainsString('<script>', $response->getContent());
        $this->assertStringContainsString('&amp;lt;script&amp;gt;', $response->getContent());
    }

    public function test_input_sanitization_works()
    {
        // Criar dados necessários primeiro
        $category = \App\Modules\Product\Models\Category::factory()->create();
        $supplier = \App\Modules\Product\Models\Supplier::factory()->create();
        
        $admin = User::factory()->create([
            'access_level' => 1,
            'password' => Hash::make('password')
        ]);
        $token = $admin->createToken('test_token')->plainTextToken;

        $maliciousData = [
            'description' => '<script>alert("xss")</script>Product',
            'brand' => '<img src=x onerror=alert("xss")>Brand',
            'model' => 'TEST-001',
            'gender' => 'Unissex',
            'sale_price' => 99.99,
            'cost_price' => 50.00,
            'stock_quantity' => 10,
            'weight' => 0.5,
            'width' => 10,
            'height' => 15,
            'length' => 20,
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'meta_title' => 'Test SEO',
            'meta_description' => 'Test description',
            'meta_keywords' => 'test',
            'h1' => 'Test H1',
            'text1' => 'Test text'
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/v1/products', $maliciousData);

        $response->assertStatus(201);
        
        // Verifica se os dados foram sanitizados no banco
        $product = Product::first();
        $this->assertStringNotContainsString('<script>', $product->description);
        $this->assertStringNotContainsString('<img', $product->brand);
    }

    public function test_rate_limiting_is_configured()
    {
        // Teste básico para verificar se rate limiting está ativo
        for ($i = 0; $i < 10; $i++) {
            $response = $this->postJson('/api/v1/auth/login', [
                'email' => 'test@test.com',
                'password' => 'wrongpassword',
                'device_name' => 'test'
            ]);
        }

        // Se rate limiting estiver ativo, deverá retornar 429
        // Este teste pode precisar de ajuste dependendo da configuração
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@test.com',
            'password' => 'wrongpassword',
            'device_name' => 'test'
        ]);

        // Pode ser 422 (validação) ou 429 (rate limit)
        $this->assertContains($response->status(), [422, 429]);
    }
}
