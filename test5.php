<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

auth()->loginUsingId(1);
auth()->user()->role = 'teacher';

$request = Illuminate\Http\Request::create('/teacher/dashboard', 'GET');
try {
    $response = $kernel->handle($request);
    echo "STATUS: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() >= 500) {
        if (preg_match('/<title>(.*?)<\/title>/s', $response->getContent(), $matches)) {
            echo "Exception: " . strip_tags($matches[1]) . "\n";
        }
        if (preg_match('/<div class="exception-message">\s*<.*?>(.*?)<\/.*?>/s', $response->getContent(), $matches)) {
            echo "Details: " . trim(strip_tags($matches[1])) . "\n";
        } else {
            echo substr(strip_tags($response->getContent()), 0, 1000);
        }
    }
} catch (\Throwable $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
