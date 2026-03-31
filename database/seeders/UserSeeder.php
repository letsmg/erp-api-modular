<?php

namespace Database\Seeders;

use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Para os usuários normais aleatórios, podemos manter a factory,
        // mas é recomendável só rodar se a tabela estiver vazia para evitar lentidão
        if (User::count() < 5) {
            User::factory()->count(5)->create();
        }

        // 2. O ADMIN - REMOVIDO para evitar erro de hash
        // O admin será criado pelo SingleUserSeeder
        // Se precisar do admin manualmente, usar:
        // php artisan tinker --execute="User::create(['name' => 'Admin', 'email' => 'admin@teste.com', 'password' => bcrypt('Mudar@123'), 'access_level' => 1, 'is_active' => true, 'email_verified_at' => now()])"
    }
}