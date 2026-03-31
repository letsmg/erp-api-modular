<?php

namespace App\Modules\Client\Tests\Feature;

use App\Modules\Client\Models\Client;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_client_with_address(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);

        $payload = [
            'name' => 'Cliente Teste',
            'document_number' => '12345678900',
            'phone' => '11999999999',
            'address' => [
                'zip_code' => '01001-000',
                'street' => 'Rua Um',
                'number' => '100',
                'neighborhood' => 'Centro',
                'city' => 'Sao Paulo',
                'state' => 'SP',
            ],
        ];

        $this->actingAs($user)
            ->postJson(route('api.clients.store'), $payload)
            ->assertCreated()
            ->assertJsonPath('data.address.city', 'Sao Paulo');
    }
}
