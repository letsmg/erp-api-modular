<?php

namespace App\Modules\Product\Tests\Feature;

use App\Enums\AccessLevel;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Supplier;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(int $level): User
    {
        return User::factory()->create([
            'access_level' => $level,
            'password' => Hash::make('password')
        ]);
    }

    public function test_only_staff_can_access_products_index(): void
    {
        $admin = $this->createUser(1);
        $operator = $this->createUser(0);
        $guest = User::factory()->create([
            'access_level' => AccessLevel::CLIENT,
            'password' => Hash::make('password')
        ]);

        $this->actingAs($admin)->get(route('products.index'))->assertOk();
        $this->actingAs($operator)->get(route('products.index'))->assertOk();
        $this->actingAs($guest)->get(route('products.index'))->assertForbidden();
    }

    public function test_only_admin_can_toggle_featured_status(): void
    {
        $admin = $this->createUser(1);
        $operator = $this->createUser(0);
        $product = Product::factory()->create(['is_featured' => false]);

        $this->actingAs($operator)
            ->patch(route('products.toggle-featured', $product))
            ->assertForbidden();

        $this->actingAs($admin)
            ->patch(route('products.toggle-featured', $product))
            ->assertRedirect();
    }

    public function test_level_0_can_only_create_inactive_products(): void
    {
        $operator = $this->createUser(0);
        $supplier = Supplier::factory()->create();
        $category = Category::factory()->create();

        $data = [
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'description' => 'Produto API Teste',
            'brand' => 'Marca Teste',
            'model' => 'MOD-100',
            'size' => 'M',
            'collection' => 'Colecao 2026',
            'gender' => 'Unissex',
            'barcode' => '7891234567890',
            'cost_price' => 10,
            'sale_price' => 20,
            'stock_quantity' => 5,
            'weight' => 1,
            'width' => 10,
            'height' => 15,
            'length' => 20,
            'meta_title' => 'Titulo teste',
            'meta_description' => 'Descricao teste',
            'meta_keywords' => 'teste,api,produto',
            'h1' => 'Produto teste',
            'text1' => 'Texto teste',
            'images' => [UploadedFile::fake()->create('p.jpg', 100, 'image/jpeg')],
        ];

        $this->actingAs($operator)
            ->post(route('products.store'), $data)
            ->assertRedirect();

        $this->assertDatabaseHas('products', [
            'description' => 'Produto API Teste',
            'is_active' => false
        ]);
    }

    public function test_level_0_cannot_activate_product_during_update(): void
    {
        $operator = $this->createUser(0);
        $product = Product::factory()->create(['is_active' => false]);

        $payload = [
            'supplier_id' => $product->supplier_id,
            'category_id' => $product->category_id,
            'description' => 'Descricao Alterada',
            'brand' => $product->brand,
            'model' => $product->model,
            'size' => $product->size,
            'collection' => $product->collection,
            'gender' => $product->gender,
            'barcode' => $product->barcode,
            'cost_price' => $product->cost_price,
            'sale_price' => $product->sale_price,
            'stock_quantity' => $product->stock_quantity,
            'weight' => $product->weight,
            'width' => $product->width,
            'height' => $product->height,
            'length' => $product->length,
            'meta_title' => 'Novo titulo',
            'meta_description' => 'Nova descricao',
            'meta_keywords' => 'um,dois,tres',
            'h1' => 'Novo h1',
            'text1' => 'Novo texto',
            'existing_images' => [],
            'is_active' => true,
        ];

        $this->actingAs($operator)
            ->put(route('products.update', $product), $payload)
            ->assertRedirect();

        $this->assertFalse($product->refresh()->is_active);
        $this->assertSame('Descricao Alterada', $product->description);
    }

    public function test_only_admin_can_delete_products(): void
    {
        $admin = $this->createUser(1);
        $operator = $this->createUser(0);
        $product = Product::factory()->create();

        $this->actingAs($operator)
            ->delete(route('products.destroy', $product))
            ->assertForbidden();

        $this->actingAs($admin)
            ->delete(route('products.destroy', $product))
            ->assertRedirect();

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
