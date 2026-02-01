<?php
echo "=== Migrating to Livewire Alert v4 ===\n\n";

$components = [
    'app/Livewire/Teams/CreateTeam.php',
    'app/Livewire/Teams/ManageTeam.php',
    'app/Livewire/Admin/Permissions/CreatePermission.php',
    'app/Livewire/Admin/Permissions/EditPermission.php',
    'app/Livewire/Admin/Permissions.php',
    'app/Livewire/Admin/Roles/CreateRole.php',
    'app/Livewire/Admin/Roles/EditRole.php',
    'app/Livewire/Admin/Roles.php',
    'app/Livewire/Admin/Users/CreateUser.php',
    'app/Livewire/Admin/Users/EditUser.php',
    'app/Livewire/Admin/Users.php',
];

foreach ($components as $file) {
    if (!file_exists($file)) {
        echo "Skipping (not found): $file\n";
        continue;
    }
    
    $content = file_get_contents($file);
    $original = $content;
    
    // 1. Remove the old use statement
    $content = str_replace("use Jantinnerezo\\LivewireAlert\\LivewireAlert;\n", '', $content);
    
    // 2. Remove the trait usage
    $content = str_replace("use LivewireAlert;", '', $content);
    
    // 3. Replace alert() calls with dispatch
    $content = preg_replace_callback(
        '/\$this->alert\([\'"](success|error|warning|info)[\'"],\s*(.+?)\);/',
        function($matches) {
            $type = $matches[1];
            $message = $matches[2];
            return "\$this->dispatch('alert', type: '$type', message: $message);";
        },
        $content
    );
    
    // 4. Replace flash() calls (for redirects)
    $content = preg_replace_callback(
        '/\$this->flash\([\'"](success|error|warning|info)[\'"],\s*(.+?)\);/',
        function($matches) {
            $type = $matches[1];
            $message = $matches[2];
            return "session()->flash('alert', ['type' => '$type', 'message' => $message]);";
        },
        $content
    );
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Updated: $file\n";
    }
}

echo "\n=== IMPORTANT NEXT STEPS ===\n";
echo "1. Layout updated with @livewire('livewire-alert')\n";
echo "2. Run: php artisan optimize:clear\n";
echo "3. Run: composer dump-autoload\n";
echo "4. Test alerts in your application\n";