<?php

namespace App\Modules\Product\Tests\Feature;

use App\Modules\Product\Models\Supplier;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_suppliers_api(): void
    {
        $this->getJson(route('api.suppliers.index'))->assertUnauthorized();
    }

    public function test_authenticated_user_can_list_suppliers(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);
        Supplier::factory()->count(2)->create();

        $response = $this->actingAs($user)
            ->getJson(route('api.suppliers.index'))
            ->assertOk();

        // Os fornecedores estão em data.data (resposta -> data -> data dos fornecedores)
        $suppliers = $response->json('data.data');
        $this->assertIsArray($suppliers);
        $this->assertCount(2, $suppliers);
    }

    public function test_authenticated_user_can_create_supplier(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);

        $payload = [
            'company_name' => 'Fornecedor de Teste LTDA',
            'cnpj' => '12.345.678/0001-90',
            'state_registration' => '123456789',
            'email' => 'contato@fornecedor.com',
            'address' => 'Rua de Teste, 123',
            'neighborhood' => 'Centro',
            'city' => 'Sao Paulo',
            'state' => 'SP',
            'zip_code' => '01001-000',
            'contact_name_1' => 'Joao Silva',
            'phone_1' => '(11) 99999-9999',
            'is_active' => true,
        ];

        $this->actingAs($user)
            ->postJson(route('api.suppliers.store'), $payload)
            ->assertCreated()
            ->assertJsonPath('data.cnpj', '12.345.678/0001-90');
    }

    public function test_authenticated_user_can_update_supplier(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);
        $supplier = Supplier::factory()->create(['company_name' => 'Antigo Nome']);

        $payload = [
            'company_name' => 'Novo Nome Atualizado',
            'cnpj' => $supplier->cnpj,
            'state_registration' => $supplier->state_registration,
            'email' => 'novo@email.com',
            'address' => $supplier->address,
            'neighborhood' => $supplier->neighborhood,
            'city' => $supplier->city,
            'state' => $supplier->state,
            'zip_code' => $supplier->zip_code,
            'contact_name_1' => $supplier->contact_name_1,
            'phone_1' => $supplier->phone_1,
            'is_active' => false,
        ];

        $this->actingAs($user)
            ->putJson(route('api.suppliers.update', $supplier), $payload)
            ->assertOk()
            ->assertJsonPath('data.company_name', 'Novo Nome Atualizado');
    }

    public function test_authenticated_user_can_delete_supplier(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);
        $supplier = Supplier::factory()->create();

        $this->actingAs($user)
            ->deleteJson(route('api.suppliers.destroy', $supplier))
            ->assertNoContent(); // 204 No Content é o status correto para delete

        $this->assertModelMissing($supplier);
    }
}
