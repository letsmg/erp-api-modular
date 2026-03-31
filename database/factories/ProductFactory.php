<?php

namespace Database\Factories;

use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $description = ucfirst($this->faker->words(3, true));
        return [
            'supplier_id' => Supplier::factory(), 
            'category_id' => Category::factory(), 
            'description' => $description,
            'slug' => Str::slug($description), // Adicionar slug para evitar erro
            'brand' => $this->faker->company(), 
            'model' => $this->faker->bothify('??-###'), 
            'size' => $this->faker->randomElement(['P', 'M', 'G', 'GG']),
            'collection' => 'Colecao '.$this->faker->word(), 
            'gender' => $this->faker->randomElement(['Masculino', 'Feminino', 'Unissex']),
            'cost_price' => $this->faker->randomFloat(2, 50, 150), 
            'sale_price' => $this->faker->randomFloat(2, 200, 500),
            'barcode' => $this->faker->ean13(), 
            'stock_quantity' => $this->faker->numberBetween(10, 100), 
            'is_active' => true,
            'is_featured' => false, 
            'weight' => $this->faker->randomFloat(2, 0.1, 5), 
            'width' => $this->faker->numberBetween(10, 100),
            'height' => $this->faker->numberBetween(10, 100), 
            'length' => $this->faker->numberBetween(10, 100),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            $product->seo()->create([
                'meta_title' => $this->faker->text(65), 
                'meta_description' => $this->faker->text(160),
                'meta_keywords' => implode(',', $this->faker->words(5)), 
                'h1' => $this->faker->text(70), 
                'text1' => $this->faker->paragraph(),
            ]);
        });
    }
}
