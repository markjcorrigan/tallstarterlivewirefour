<?php
// register-component.php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Registering Livewire Alert Component ===\n\n";

// First, let's see if the component is already discoverable
try {
    $component = new \Jantinnerezo\LivewireAlert\LivewireAlert();
    echo "✓ LivewireAlert class can be instantiated\n";

    // Check what the component name should be
    if (method_exists($component, 'getName')) {
        $componentName = $component->getName();
        echo "Component name from getName(): $componentName\n";
    } elseif (property_exists($component, 'componentName')) {
        $componentName = $component->componentName;
        echo "Component name from property: $componentName\n";
    } else {
        echo "Using default name: 'livewire-alert'\n";
        $componentName = 'livewire-alert';
    }
} catch (Exception $e) {
    echo "✗ Error instantiating: " . $e->getMessage() . "\n";
    exit;
}

// Now register it
try {
    \Livewire\Livewire::component($componentName, \Jantinnerezo\LivewireAlert\LivewireAlert::class);
    echo "✓ Registered component as: '$componentName'\n";

    // Test if it works
    $registered = \Livewire\Livewire::getClass('livewire-alert')
        ?: \Livewire\Livewire::getClass('alert');

    if ($registered) {
        echo "✓ Component is now registered and discoverable\n";
    } else {
        echo "⚠ Component registered but not discoverable by name\n";
    }
} catch (Exception $e) {
    echo "✗ Registration error: " . $e->getMessage() . "\n";
}

echo "\n=== Update Your Layout ===\n";
echo "In your layout, use: @livewire('$componentName')\n";
echo "Currently using: @livewire('livewire-alert')\n";

// Check current layout
$layoutFile = 'resources/views/components/layouts/app/frontend.blade.php';
if (file_exists($layoutFile)) {
    $layout = file_get_contents($layoutFile);
    if (strpos($layout, "@livewire('livewire-alert')") !== false) {
        echo "\n⚠ Layout uses 'livewire-alert'. ";
        if ($componentName !== 'livewire-alert') {
            echo "Change to: @livewire('$componentName')\n";
        }
    }
}

echo "\n=== Clear Cache and Test ===\n";
echo "php artisan optimize:clear\n";
echo "php artisan serve\n";
