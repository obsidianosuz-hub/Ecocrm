<?php
$user = App\Models\User::where('email', 'admin@itcloud.uz')->first();
if ($user) {
    $user->password = bcrypt('12345678');
    $user->save();
    echo "OK";
} else {
    echo "NOT_FOUND";
}
