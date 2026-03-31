<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Modules\User\Models\User;
use App\Enums\AccessLevel;

echo "=== USUÁRIOS CADASTRADOS ===\n";
$users = User::select('email', 'access_level', 'is_active')->get();

foreach ($users as $user) {
    $accessLevelName = $user->access_level->name;
    $status = $user->is_active ? 'ATIVO' : 'INATIVO';
    echo "Email: {$user->email} | Nível: {$accessLevelName} | Status: {$status}\n";
}

echo "\n=== TESTE DE LOGIN ===\n";
$testUser = User::where('email', '1@1.com')->first();

if ($testUser) {
    echo "Usuário de teste encontrado:\n";
    echo "- Email: {$testUser->email}\n";
    echo "- Nível: {$testUser->access_level->name}\n";
    echo "- Ativo: " . ($testUser->is_active ? 'SIM' : 'NÃO') . "\n";
    echo "- Senha (hash): {$testUser->password}\n";
} else {
    echo "Usuário de teste (1@1.com) não encontrado!\n";
}
