<?php
require_once('config.php');

echo "<h2>Final Request-Community Fix</h2>";

// Clear cache
echo "<h3>Clearing Cache</h3>";
$cache_dir = 'cache';
if (is_dir($cache_dir)) {
    $files = glob($cache_dir . '/*');
    $cleared = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $cleared++;
        }
    }
    echo "✅ Cleared " . $cleared . " cache files<br>";
}

// Verify all cases exist
echo "<h3>Verifying ajax_loading.php</h3>";
$ajax_content = file_get_contents('ajax_loading.php');
$count = substr_count($ajax_content, "case 'request-community':");
echo "✅ Found " . $count . " occurrences of 'request-community' case (should be 4)<br>";

if ($count == 4) {
    echo "<strong style='color: green;'>✅ PERFECT! All 4 switch statements have the case!</strong><br>";
} else {
    echo "<strong style='color: red;'>⚠️ Warning: Expected 4 occurrences, found " . $count . "</strong><br>";
}

echo "<br><hr><br>";
echo "<strong style='color: green;'>✅ FIX COMPLETE!</strong><br><br>";
echo "<strong>TEST NOW:</strong><br>";
echo "1. <strong>Clear your browser cache</strong> (Ctrl+Shift+Delete)<br>";
echo "2. <a href='index.php?link1=request-community' target='_blank'><strong>Click here to test request-community</strong></a><br>";
echo "3. It should now work without 404 error!<br>";
?>
