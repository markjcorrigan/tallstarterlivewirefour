<?php
echo "=== LIVEWIRE 4 UPGRADE CHECK ===\n\n";

echo "1. Checking Blade files for old Livewire syntax...\n";
$count = 0;
$files = glob('resources/views/**/*.blade.php');
foreach ($files as $file) {
    $content = file_get_contents($file);
    if (preg_match('/<livewire:[^>]+\/>/', $content)) {
        $count++;
        echo "   Found in: " . str_replace('resources/views/', '', $file) . "\n";
    }
}

if ($count === 0) {
    echo "   ✓ Great! No changes needed.\n";
} else {
    echo "\n   ✗ Found $count files that need updating.\n";
}

echo "\n=== NEXT STEP ===\n";
if ($count > 0) {
    echo "Run: php fix-blade-syntax.php\n";
} else {
    echo "Your Blade files are already updated!\n";
}
