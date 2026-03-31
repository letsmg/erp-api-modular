<?php

namespace Tests\Feature\Api;

use App\Modules\User\Models\User;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CatalogApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_home_is_publicly_accessible()
    {
        Category::factory()->count(3)->create();
        Supplier::factory()->count(2)->create();

        $response = $this->getJson('/api/v1/catalog/home');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'products',
                        'featured_products',
                        'on_sale_products',
                        'brands',
                        'filters'
                    ],
                    'meta' => [
                        'current_page',
                        'last_page',
                        'per_page',
                        'total'
                    ]
                ]);
    }

    public function test_catalog_products_is_publicly_accessible()
    {
        Category::factory()->create(['name' => 'Test Category']);
        
        $response = $this->getJson('/api/v1/catalog/products');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'description',
                            'brand',
                            'sale_price',
                            'slug',
                            'images'
                        ]
                    ]
                ]);
    }

    public function test_catalog_products_can_be_filtered()
    {
        Category::factory()->create(['name' => 'Eletrônicos']);
        Category::factory()->create(['name' => 'Vestuário']);

        $response = $this->getJson('/api/v1/catalog/products?category=1&search=test');

        $response->assertStatus(200);
    }

    public function test_catalog_product_show_is_publicly_accessible()
    {
        $category = Category::factory()->create(['name' => 'Test Category']);
        $supplier = Supplier::factory()->create();
        
        $product = \App\Modules\Product\Models\Product::factory()->create([
            'description' => 'Test Product',
            'slug' => 'test-product',
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'is_active' => true
        ]);

        $response = $this->getJson('/api/v1/catalog/products/test-product');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'product' => [
                            'id',
                            'description',
                            'brand',
                            'sale_price',
                            'slug',
                            'category',
                            'supplier',
                            'images',
                            'seo'
                        ],
                        'related_products'
                    ]
                ]);
    }

    public function test_catalog_product_show_returns_404_for_inactive_product()
    {
        $category = Category::factory()->create();
        $supplier = Supplier::factory()->create();
        
        $product = \App\Modules\Product\Models\Product::factory()->create([
            'description' => 'Inactive Product',
            'slug' => 'inactive-product',
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'is_active' => false
        ]);

        $response = $this->getJson('/api/v1/catalog/products/inactive-product');

        $response->assertStatus(404);
    }
}
