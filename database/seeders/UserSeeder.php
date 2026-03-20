<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria 10 usuários alternando entre access_level 0 e 1
        for ($i = 1; $i <= 10; $i++) {
            User::factory()->create([
                'access_level' => $i % 2, // 0,1,0,1,...
                'password' => Hash::make('Mudar@123'),
            ]);
        }
    }
}