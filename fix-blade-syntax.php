<?php
echo "=== Fixing Livewire Blade Syntax ===\n\n";

$files = glob('resources/views/**/*.blade.php');
$updated = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    $original = $content;
    
    // Fix: <livewire:component-name /> to @livewire('component-name')
    $content = preg_replace_callback(
        '/<livewire:([\w\.-]+)\s*\/>/',
        function($matches) {
            return "@livewire('" . $matches[1] . "')";
        },
        $content
    );
    
    // Fix: <livewire:component-name :param="$value" /> to @livewire('component-name', ['param' => $value])
    $content = preg_replace_callback(
        '/<livewire:([\w\.-]+)\s+(.*?)\s*\/>/',
        function($matches) {
            $component = $matches[1];
            $attributes = $matches[2];
            
            // Parse attributes (simple version)
            preg_match_all('/(\w+)=["\']([^"\']+)["\']/', $attributes, $attrMatches);
            
            $params = [];
            for ($i = 0; $i < count($attrMatches[0]); $i++) {
                $key = $attrMatches[1][$i];
                $value = $attrMatches[2][$i];
                $params[] = "'$key' => '$value'";
            }
            
            if (empty($params)) {
                return "@livewire('$component')";
            } else {
                return "@livewire('$component', [" . implode(', ', $params) . "])";
            }
        },
        $content
    );
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        $updated++;
        echo "Updated: " . str_replace('resources/views/', '', $file) . "\n";
    }
}

echo "\n=== DONE ===\n";
echo "Updated $updated files.\n";
echo "Now test your application!\n";
