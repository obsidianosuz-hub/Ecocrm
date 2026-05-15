<?php
$user = \App\Models\User::where('role', 'teacher')->first();
auth()->login($user);
$request = Illuminate\Http\Request::create('/teacher/dashboard', 'GET');
$response = app()->make(Illuminate\Contracts\Http\Kernel::class)->handle($request);
if ($response->getStatusCode() >= 500) {
    echo "ERROR 500\n";
    if (preg_match('/<title>(.*?)<\/title>/s', $response->getContent(), $matches)) {
        echo "Exception: " . strip_tags($matches[1]) . "\n";
    }
    // Try to extract the first trace line or exception message
    if (preg_match('/<div class="exception-message">\s*<.*?>(.*?)<\/.*?>/s', $response->getContent(), $matches)) {
        echo "Details: " . trim(strip_tags($matches[1])) . "\n";
    } else {
        echo substr($response->getContent(), 0, 500);
    }
} else {
    echo 'Status: ' . $response->getStatusCode();
}
