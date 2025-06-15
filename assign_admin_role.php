<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illware\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::find(1);

if ($user) {
    $user->assignRole('admin');
    echo "Rol de administrador asignado correctamente al usuario con ID 1.\n";
} else {
    echo "No se encontró el usuario con ID 1.\n";
}
