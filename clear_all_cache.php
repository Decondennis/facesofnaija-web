<?php
// Clear all WoWonder cache files
require_once('config.php');

$cache_dir = 'cache';
$cleared_files = 0;

if (is_dir($cache_dir)) {
    $files = glob($cache_dir . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $cleared_files++;
        }
    }
}

// Also clear session
session_start();
session_destroy();

echo "Cache cleared successfully!<br>";
echo "Files cleared: " . $cleared_files . "<br><br>";
echo "Now:<br>";
echo "1. Clear your browser cache (Ctrl+Shift+Delete)<br>";
echo "2. Close and reopen your browser<br>";
echo "3. Refresh the page<br>";
?>
