<?php
$dir = __DIR__ . '/app/Models';
$files = glob($dir . '/*.php');
foreach ($files as $file) {
    if (strpos($file, 'Company.php') !== false || strpos($file, 'User.php') !== false) continue;
    $content = file_get_contents($file);
    if (strpos($content, 'BelongsToCompany') === false) {
        // Try replacing HasFactory
        if (strpos($content, 'use HasFactory;') !== false) {
            $content = str_replace('use HasFactory;', "use HasFactory, \\App\\Traits\\BelongsToCompany;", $content);
        } else {
            // Find class definition and insert
            $content = preg_replace('/class\s+([A-Za-z0-9_]+)\s+extends\s+Model\n{/', "class $1 extends Model\n{\n    use \\App\\Traits\\BelongsToCompany;\n", $content);
        }
        file_put_contents($file, $content);
        echo "Updated " . basename($file) . "\n";
    }
}
