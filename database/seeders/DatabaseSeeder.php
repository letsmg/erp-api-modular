<?php

namespace Database\Seeders;

use App\Modules\User\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // O método call recebe um array com as classes que você deseja executar
        $this->call([
            SingleUserSeeder::class,
            // UserSeeder::class, // Removido para evitar conflito
            SupplierSeeder::class,
            CategorySeeder::class,            
            ProductSeeder::class,            
        ]);
    }
}
