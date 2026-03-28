<?php
// Place this file in the document root to test what in index.php causes 500
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Override the header function to track 500 calls
// We'll require init.php step by step

echo "Step 1: Starting\n";
flush();

chdir('/var/www/html/facesofnaija');

// Test individual requires from init.php
echo "Step 2: Loading MySQL class\n"; flush();
require_once('assets/libraries/DB/vendor/joshcam/mysqli-database-class/MySQL-Maria.php');
echo "Step 3: Loading cache\n"; flush();
require_once('includes/cache.php');
echo "Step 4: Loading functions_general\n"; flush();
require_once('includes/functions_general.php');
echo "Step 5: Loading tabels\n"; flush();
require_once('includes/tabels.php');
echo "Step 6: Done - all no error\n"; flush();
echo "PHP version: " . PHP_VERSION . "\n";
