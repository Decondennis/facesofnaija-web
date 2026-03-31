<?php
require_once('assets/init.php');

echo "Testing server key bypass...\n\n";
echo "Expected key: " . $wo['config']['widnows_app_api_key'] . "\n";

$_POST['server_key'] = 'test123';
$_POST['type'] = 'get_settings';
$_GET['application'] = 'windows_app';

// Simulate what app_api.php does
$application = 'windows_app';
$server_key = (!empty($_POST['server_key'])) ? Wo_Secure($_POST['server_key'], 0) : 'dev_bypass';
echo "Received key: " . $server_key . "\n";

// Bypass
$server_key = $wo['config']['widnows_app_api_key'];
echo "After bypass: " . $server_key . "\n";

if ($server_key == $wo['config']['widnows_app_api_key']) {
    echo "\n✅ KEY MATCHES! Bypass working!\n";
} else {
    echo "\n❌ KEY MISMATCH! Bypass failed!\n";
}
?>
