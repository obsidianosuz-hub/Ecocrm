<?php
foreach(App\Models\User::all() as $u) {
    echo $u->email . " | Role: " . ($u->role ?? 'null') . "\n";
}

$user = App\Models\User::where('role', 'super_admin')->orWhere('role', 'superadmin')->orWhere('role', 'admin')->first();
if ($user) {
    $user->password = bcrypt('12345678');
    $user->save();
    echo "\nPassword for " . $user->email . " set to 12345678\n";
}
