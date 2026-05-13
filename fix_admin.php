<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = \App\Models\User::where('email', 'admin@itcloud.uz')->first();
if ($u) {
    // Parolni yangilash
    $u->password = \Illuminate\Support\Facades\Hash::make('admin123');
    $u->approval_status = 'approved';
    $u->status = 'online';
    $u->role = 'admin';
    $u->save();
    echo "Admin updated successfully! Login: admin@itcloud.uz / Pass: admin123\n";
} else {
    echo "User not found!\n";
}
