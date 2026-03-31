<?php
// LOG what the Android app is sending
$logFile = 'api_debug.log';
$logData = [
    'time' => date('Y-m-d H:i:s'),
    'server_key_received' => $_POST['server_key'] ?? 'NOT SET',
    'type' => $_POST['type'] ?? $_GET['type'] ?? 'NOT SET',
    'application' => $_GET['application'] ?? 'NOT SET',
    'all_post' => $_POST,
    'all_get' => $_GET
];
file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

echo json_encode(['logged' => true, 'check' => 'api_debug.log']);
?>
