<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;

class SingleUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => '1@1.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Mudar@123'), // Usar Hash::make para argon2id
                'access_level' => 1,
                'is_active' => true,
            ]
        );
        $user = User::updateOrCreate(
            ['email' => '2@1.com'],
            [
                'name' => 'Padrão',
                'password' => Hash::make('Mudar@123'), // Usar Hash::make para argon2id
                'access_level' => 0,
                'is_active' => true,
            ]
        );
        $user = User::updateOrCreate(
            ['email' => '3@1.com'],
            [
                'name' => 'Cliente a implementar',
                'password' => Hash::make('Mudar@123'), // Usar Hash::make para argon2id
                'access_level' => 2,
                'is_active' => true,
            ]
        );

        $this->command->info("Usuário {$user->email} verificado/criado!");
    }
}