<?php

require __DIR__ . '/vendor/autoload.php';

// Load the application
$app = require_once __DIR__ . '/bootstrap/app.php';

// Bootstrap the app
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Test the setting helper function
    echo "Testing setting() helper function:\n";
    echo "------------------------\n";

    // Get a setting
    echo "Get setting 'site_name': " . setting('site_name', 'Default Site Name') . "\n";

    // Set a setting
    echo "Setting 'test_key' to 'test_value'...\n";
    setting(['test_key' => 'test_value']);

    // Get the setting we just set
    echo "Get setting 'test_key': " . setting('test_key', 'Default Value') . "\n";

    // Test the Settings model directly
    echo "\nTesting Settings model directly:\n";
    echo "------------------------\n";

    // Get a setting using the model
    echo "Get setting 'site_name' via model: " . App\Models\Setting::get('site_name', 'Default Site Name') . "\n";

    // Set a setting using the model
    echo "Setting 'model_test_key' to 'model_test_value' via model...\n";
    App\Models\Setting::set('model_test_key', 'model_test_value', 'Test description', 'test');

    // Get the setting we just set using the model
    echo "Get setting 'model_test_key' via model: " . App\Models\Setting::get('model_test_key', 'Default Value') . "\n";

    // Get all settings grouped
    echo "\nAll settings grouped by group:\n";
    echo "------------------------\n";
    $groupedSettings = App\Models\Setting::getAllGrouped();
    if ($groupedSettings) {
        foreach ($groupedSettings as $group => $settings) {
            echo "Group: $group\n";
            foreach ($settings as $setting) {
                echo "  - {$setting->key}: {$setting->value}\n";
            }
            echo "\n";
        }
    } else {
        echo "No settings found.\n";
    }

    echo "Settings test completed successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
}
