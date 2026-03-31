<?php

namespace Tests\Feature\Api;

use App\Modules\Product\Models\Product;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_products()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);
        Product::factory()->count(5)->create();

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/v1/products');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'description',
                            'brand',
                            'sale_price'
                        ]
                    ]
                ]);
    }

    public function test_unauthenticated_user_cannot_access_products()
    {
        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_product()
    {
        // Criar dados necessários primeiro
        $category = \App\Modules\Product\Models\Category::factory()->create();
        $supplier = \App\Modules\Product\Models\Supplier::factory()->create();
        
        $user = User::factory()->create([
            'access_level' => 1, // Admin
            'password' => Hash::make('password')
        ]);
        $token = $user->createToken('test_token')->plainTextToken;

        $productData = [
            'description' => 'Test Product',
            'brand' => 'Test Brand',
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
            'meta_title' => 'Test Product SEO',
            'meta_description' => 'Test product description',
            'meta_keywords' => 'test,product',
            'h1' => 'Test Product H1',
            'text1' => 'Test product text'
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/v1/products', $productData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'description',
                        'brand',
                        'sale_price'
                    ]
                ]);
    }
}
