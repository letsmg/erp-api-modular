<?php

namespace App\Modules\Sale\Tests\Feature;

use App\Modules\Client\Models\Client;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_sale(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);
        $client = Client::factory()->create();
        $client->address()->create([
            'zip_code' => '01001-000',
            'street' => 'Rua Um',
            'number' => '100',
            'neighborhood' => 'Centro',
            'city' => 'Sao Paulo',
            'state' => 'SP',
        ]);

        $payload = [
            'client_id' => $client->id,
            'items' => [
                [
                    'product_description' => 'Produto A',
                    'quantity' => 2,
                    'unit_price' => 15,
                ],
            ],
        ];

        $this->actingAs($user)
            ->postJson(route('api.sales.store'), $payload)
            ->assertCreated()
            ->assertJsonPath('data.total_amount', '30.00');
    }
}
