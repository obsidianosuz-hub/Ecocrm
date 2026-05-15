<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = \App\Models\User::first();
if($user) {
    auth()->login($user);
}

$request = Illuminate\Http\Request::create('/admin/academy/rooms', 'GET');
try {
    $response = $kernel->handle($request);
    echo "STATUS: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() >= 500) {
        if (preg_match('/<title>(.*?)<\/title>/s', $response->getContent(), $matches)) {
            echo "Exception: " . strip_tags($matches[1]) . "\n";
        }
    }
} catch (\Throwable $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
