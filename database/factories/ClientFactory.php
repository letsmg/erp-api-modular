<?php

namespace Database\Factories;

use App\Modules\Client\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'document_number' => fake()->unique()->numerify('###########'),
            'phone' => fake()->phoneNumber(),
            'phone1' => fake()->phoneNumber(),
            'contact1' => fake()->name(),
            'phone2' => fake()->phoneNumber(),
            'contact2' => fake()->name(),
        ];
    }
}
