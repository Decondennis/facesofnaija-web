<?php
echo "<h2>Request-Community Diagnostic</h2>";

// Check if source file exists
echo "<h3>1. Checking Source Files</h3>";
if (file_exists('sources/request-community.php')) {
    echo "✅ sources/request-community.php EXISTS<br>";
    echo "<pre>" . htmlspecialchars(file_get_contents('sources/request-community.php')) . "</pre>";
} else {
    echo "❌ sources/request-community.php NOT FOUND<br>";
}

// Check if template exists
echo "<h3>2. Checking Template Files</h3>";
if (file_exists('themes/facesofnaija/layout/community/request-community.phtml')) {
    echo "✅ themes/facesofnaija/layout/community/request-community.phtml EXISTS<br>";
} else {
    echo "❌ themes/facesofnaija/layout/community/request-community.phtml NOT FOUND<br>";
}

// Check ajax_loading.php for request-community case
echo "<h3>3. Checking ajax_loading.php</h3>";
$ajax_content = file_get_contents('ajax_loading.php');
if (strpos($ajax_content, "case 'request-community':") !== false) {
    echo "✅ 'request-community' case FOUND in ajax_loading.php<br>";
    
    // Count how many times it appears
    $count = substr_count($ajax_content, "case 'request-community':");
    echo "Found " . $count . " occurrence(s)<br>";
} else {
    echo "❌ 'request-community' case NOT FOUND in ajax_loading.php<br>";
}

// Clear cache
echo "<h3>4. Clearing Cache</h3>";
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

echo "<br><hr><strong>If all files exist, try:</strong><br>";
echo "1. Clear browser cache (Ctrl+Shift+Delete)<br>";
echo "2. <a href='index.php?link1=request-community'>Click here to test request-community directly</a><br>";
?>
