<?php
$dir = __DIR__ . '/app/Models';
$files = glob($dir . '/*.php');
foreach ($files as $file) {
    if (strpos($file, 'Company.php') !== false || strpos($file, 'User.php') !== false) {
        continue;
    }
    $content = file_get_contents($file);
    if (strpos($content, 'use HasFactory;') !== false && strpos($content, 'BelongsToCompany') === false) {
        $content = str_replace('use HasFactory;', "use HasFactory, \\App\\Traits\\BelongsToCompany;", $content);
        file_put_contents($file, $content);
        echo "Updated " . basename($file) . "\n";
    }
}
