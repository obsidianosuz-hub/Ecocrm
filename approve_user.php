<?php
$user = App\Models\User::where('email', 'admin@itcloud.uz')->first();
$user->approval_status = 'approved';
$user->status = 'active';
$user->password = Illuminate\Support\Facades\Hash::make('12345678');
$user->save();
echo "User approved and password set.\n";
