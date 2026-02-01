<?php
echo "=== FINAL LIVEWIRE 4 CHECK ===\n\n";

// Check Livewire version
echo "1. Livewire Version: ";
try {
    echo \Livewire\Livewire::VERSION . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Check if Livewire Alert is properly installed
echo "2. Livewire Alert: ";
if (class_exists('Jantinnerezo\\LivewireAlert\\LivewireAlert')) {
    echo "✓ Installed\n";
} else {
    echo "✗ Not found\n";
}

// Check for any remaining old syntax
echo "3. Remaining old syntax in Blade files:\n";
$files = glob('resources/views/**/*.blade.php');
$found = false;
foreach ($files as $file) {
    $content = file_get_contents($file);
    if (preg_match('/<livewire:[^>]+\/>/', $content)) {
        echo "   - " . str_replace('resources/views/', '', $file) . "\n";
        $found = true;
    }
}
if (!$found) echo "   ✓ None found\n";

// Check for emit() calls
echo "4. Remaining emit() calls:\n";
$phpFiles = glob('app/**/*.php');
$foundEmit = false;
foreach ($phpFiles as $file) {
    $content = file_get_contents($file);
    if (preg_match('/\$this->emit\(/', $content)) {
        echo "   - " . str_replace('app/', '', $file) . "\n";
        $foundEmit = true;
    }
}
if (!$foundEmit) echo "   ✓ None found\n";

echo "\n=== READY TO TEST ===\n";
echo "Run: php artisan serve\n";
echo "Then test:\n";
echo "1. Login/Logout\n";
echo "2. Forms (create team, user, etc.)\n";
echo "3. Alerts (should appear after actions)\n";
