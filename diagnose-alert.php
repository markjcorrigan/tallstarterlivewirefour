<?php
// diagnose-alert.php
$packagePath = 'vendor/jantinnerezo/livewire-alert/';

echo "=== LIVEWIRE ALERT V4 INVESTIGATION ===\n\n";

// 1. Check if package exists
if (!is_dir($packagePath)) {
    die("ERROR: Package not found at: $packagePath\n");
}

// 2. List all PHP files in src
echo "1. PHP Files in package:\n";
$files = scandir($packagePath . 'src');
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        echo "   - $file\n";
    }
}

// 3. Check LivewireAlert.php specifically
$alertFile = $packagePath . 'src/LivewireAlert.php';
echo "\n2. LivewireAlert.php Analysis:\n";

if (file_exists($alertFile)) {
    $content = file_get_contents($alertFile);
    
    // Check what it is
    if (strpos($content, 'trait LivewireAlert') !== false) {
        echo "   ✓ It is a TRAIT\n";
    } elseif (strpos($content, 'class LivewireAlert') !== false) {
        echo "   ✓ It is a CLASS\n";
    } else {
        echo "   ? Cannot determine (not trait or class)\n";
    }
    
    // Get namespace
    if (preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
        echo "   Namespace: " . $matches[1] . "\n";
    }
    
    // Check for common methods
    $methods = ['alert(', 'flash(', 'confirm(', 'dialog('];
    foreach ($methods as $method) {
        if (strpos($content, $method) !== false) {
            echo "   Contains method: $method\n";
        }
    }
    
    // Show first 10 lines
    echo "\n   First 10 lines:\n";
    $lines = explode("\n", $content);
    for ($i = 0; $i < min(10, count($lines)); $i++) {
        echo "   " . ($i + 1) . ". " . trim($lines[$i]) . "\n";
    }
} else {
    echo "   ✗ File not found!\n";
}

// 4. Check README
echo "\n3. Documentation:\n";
$readmeFile = $packagePath . 'README.md';
if (file_exists($readmeFile)) {
    $readme = file_get_contents($readmeFile);
    // Find a usage section
    if (preg_match('/(##?\s*Usage.*?)(##\s|$)/s', $readme, $matches)) {
        echo "   Usage found:\n";
        echo "   " . str_replace("\n", "\n   ", trim($matches[1])) . "\n";
    } else {
        echo "   No usage section found in README\n";
    }
} else {
    echo "   No README.md found\n";
}

// 5. Check composer.json for autoload
$composerFile = $packagePath . 'composer.json';
if (file_exists($composerFile)) {
    $composer = json_decode(file_get_contents($composerFile), true);
    echo "\n4. Package Info:\n";
    echo "   Name: " . ($composer['name'] ?? 'N/A') . "\n";
    echo "   Description: " . ($composer['description'] ?? 'N/A') . "\n";
    
    if (isset($composer['autoload']['psr-4'])) {
        echo "   PSR-4 Autoload:\n";
        foreach ($composer['autoload']['psr-4'] as $namespace => $path) {
            echo "     $namespace => $path\n";
        }
    }
}

echo "\n=== END OF DIAGNOSTIC ===\n";