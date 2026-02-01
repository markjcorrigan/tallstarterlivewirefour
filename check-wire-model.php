<?php
echo "=== Checking wire:model syntax ===\n\n";

$files = glob('resources/views/**/*.blade.php');
$deferCount = 0;
$debounceCount = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    if (strpos($content, 'wire:model.defer') !== false) {
        $deferCount++;
        echo "Found wire:model.defer in: " . str_replace('resources/views/', '', $file) . "\n";
    }
    
    if (strpos($content, 'wire:model.debounce') !== false) {
        $debounceCount++;
        echo "Found wire:model.debounce in: " . str_replace('resources/views/', '', $file) . "\n";
    }
}

echo "\n=== SUMMARY ===\n";
echo "wire:model.defer found: $deferCount files\n";
echo "wire:model.debounce found: $debounceCount files\n";

if ($deferCount > 0 || $debounceCount > 0) {
    echo "\nRun: php fix-wire-model.php\n";
} else {
    echo "\n✓ No wire:model updates needed!\n";
}
