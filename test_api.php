<?php
// Clear OpCache
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OpCache cleared!\n";
} else {
    echo "OpCache not enabled\n";
}

// Test the API
echo "\n=== Testing API ===\n";
$_POST['server_key'] = 'test';
$_POST['type'] = 'get_settings';

// Simulate the API call
$wo = ['config' => ['widnows_app_api_key' => '503724220f8530cae1fe21ffa90641b8']];
$application = 'windows_app';
$server_key = 'test';

echo "Server Key: $server_key\n";
echo "Expected: " . $wo['config']['widnows_app_api_key'] . "\n";

// Check if our commented code works
if ($server_key != $wo['config']['widnows_app_api_key']) {
    echo "THIS LINE SHOULD BE COMMENTED OUT!\n";
}

echo "\n=== File Check ===\n";
$content = file_get_contents('app_api.php');
if (strpos($content, 'TEMPORARY') !== false) {
    echo "✅ Comment found in file\n";
} else {
    echo "❌ Comment NOT found\n";
}
?>
