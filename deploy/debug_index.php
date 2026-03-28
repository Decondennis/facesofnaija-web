<?php
// Simulate running index.php with errors enabled
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', '/tmp/app_errors.log');

// Simulate $_GET['link1'] = 'welcome' for unauthenticated access
$_SERVER['HTTP_HOST'] = 'facesofnaija.net';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['REQUEST_METHOD'] = 'GET';

chdir('/var/www/html/facesofnaija');

// Override ob_start to not suppress output during this test
// Load init.php with error display on
try {
    include '/var/www/html/facesofnaija/index.php';
} catch (Throwable $e) {
    echo "CAUGHT: " . $e->getMessage() . " in " . $e->getFile() . " line " . $e->getLine() . "\n";
}

echo "\nError log:\n";
echo file_get_contents('/tmp/app_errors.log') ?: "(empty)";
