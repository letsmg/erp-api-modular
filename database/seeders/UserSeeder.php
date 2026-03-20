<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Cria 5 usuários normais
        User::factory()->count(5)->create();

        // Cria 1 admin
        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@teste.com',
        ]);
    }
}