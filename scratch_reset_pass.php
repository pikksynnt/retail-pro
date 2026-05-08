<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'admin@gmail.com')->first();
if ($user) {
    $user->password = Hash::make('admin123');
    $user->save();
    echo "SUCCESS: Password for admin@gmail.com has been set to 'admin123'\n";
} else {
    echo "ERROR: User admin@gmail.com not found\n";
}
