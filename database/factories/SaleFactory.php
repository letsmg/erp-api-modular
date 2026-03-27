<?php

namespace Database\Factories;

use App\Modules\Client\Models\Client;
use App\Modules\Sale\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'total_amount' => fake()->randomFloat(2, 100, 1000),
            'sale_date' => now(),
            'status' => 'pending',
        ];
    }
}
