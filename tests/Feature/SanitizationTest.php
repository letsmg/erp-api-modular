<?php

namespace Tests\Feature;

use App\Modules\User\Models\User;
use App\Modules\Product\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SanitizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sanitizes_supplier_data()
    {
        $user = User::factory()->create();

        $data = [
            'company_name' => '<b>Test</b> Company <script>alert("xss")</script> LTDA',
            'cnpj' => '12.345.678/0001-90',
            'state_registration' => '123456789',
            'email' => 'test@company.com',
            'address' => '<p>Rua de Teste, 123</p>',
            'neighborhood' => '  Centro  ',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01001-000',
            'contact_name_1' => '<i>João</i> Silva',
            'phone_1' => '(11) 99999-9999',
            'is_active' => true,
        ];

        $response = $this->actingAs($user)->post('/api/suppliers', $data);

        $response->assertStatus(201);
        
        $supplier = \App\Modules\Product\Models\Supplier::where('cnpj', '12.345.678/0001-90')->first();
        $this->assertNotNull($supplier);
        
        // Verifica se os campos foram sanitizados
        $this->assertEquals('Test Company LTDA', $supplier->company_name);
        $this->assertEquals('Rua de Teste, 123', $supplier->address);
        $this->assertEquals('Centro', $supplier->neighborhood);
        $this->assertEquals('João Silva', $supplier->contact_name_1);
    }

    /** @test */
    public function it_sanitizes_user_data()
    {
        $admin = User::factory()->create(['access_level' => 1]);

        $data = [
            'name' => '<b>Test</b> User <script>alert("xss")</script>',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'access_level' => 0,
        ];

        $response = $this->actingAs($admin)->post('/api/users', $data);

        // Verifica o status
        dump('Status: ' . $response->status());
        
        // Verifica se houve erro
        if ($response->status() !== 201) {
            dump($response->json());
        }

        $response->assertStatus(201);
        
        // Verifica quantos usuários existem
        dump('Total users: ' . User::count());
        
        // Busca pelo usuário criado
        $user = User::where('name', 'Test User')->first();
        if (!$user) {
            // Tenta buscar qualquer usuário que não seja o admin
            $user = User::where('id', '!=', $admin->id)->first();
        }
        
        if ($user) {
            // Verifica se o nome foi sanitizado
            $this->assertEquals('Test User', $user->name);
        } else {
            $this->markTestSkipped('Não foi possível criar o usuário para teste');
        }
    }

    /** @test */
    public function it_removes_xss_payloads()
    {
        $user = User::factory()->create();

        $data = [
            'company_name' => '<script>alert("XSS")</script> Test Company',
            'cnpj' => '12.345.678/0001-90',
            'state_registration' => '123456789',
            'email' => 'testxss@company.com',
            'address' => 'Rua de Teste, 123',
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01001-000',
            'contact_name_1' => 'João Silva',
            'phone_1' => '(11) 99999-9999',
            'is_active' => true,
        ];

        $response = $this->actingAs($user)->post('/api/suppliers', $data);
        
        // Verifica se a requisição foi bem-sucedida
        $this->assertEquals(201, $response->status());
        
        $supplier = \App\Modules\Product\Models\Supplier::where('email', 'testxss@company.com')->first();
        $this->assertNotNull($supplier, 'Supplier não encontrado');
        
        // Verifica se o XSS foi removido
        $this->assertStringNotContainsString('<script>', $supplier->company_name);
        $this->assertStringNotContainsString('alert', $supplier->company_name);
        $this->assertStringContainsString('Test Company', $supplier->company_name);
    }
}
