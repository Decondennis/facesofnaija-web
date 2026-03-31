<?php
echo "File location: " . __FILE__ . "\n";
echo "Directory: " . __DIR__ . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "\nChecking for TEMPORARY comment...\n";
$content = file_get_contents(__FILE__);
if (strpos($content, 'TEMPORARY') !== false) {
    echo "✅ This file has the TEMPORARY comment\n";
} else {
    echo "❌ This file does NOT have the comment\n";
}

// Check app_api.php
$api_file = __DIR__ . '/app_api.php';
if (file_exists($api_file)) {
    $api_content = file_get_contents($api_file);
    if (strpos($api_content, 'TEMPORARY') !== false) {
        echo "✅ app_api.php has the TEMPORARY comment\n";
    } else {
        echo "❌ app_api.php does NOT have the comment\n";
    }
} else {
    echo "❌ app_api.php not found!\n";
}
?>
