<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Company;
use App\Models\User;

$company = Company::first();
if (!$company) {
    $company = Company::create([
        'name' => 'ITcloud Academy',
        'slug' => 'itcloud'
    ]);
    echo "Created default company: ITcloud Academy\n";
}

$updated = User::whereNull('company_id')->update(['company_id' => $company->id]);
echo "Updated {$updated} users with company_id: {$company->id}\n";
