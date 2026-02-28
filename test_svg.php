<!DOCTYPE html>
<html>
<head>
    <title>SVG Test</title>
</head>
<body>
    <h1>Testing SVG Files</h1>
    
    <h2>Test 1: Direct SVG Path</h2>
    <img src="upload/files/2022/09/iZcVfFlay3gkABhEhtVC_01_771d67d0b8ae8720f7775be3a0cfb51a_file.svg" width="200" height="200" style="border:1px solid red;">
    <p>Status: <span id="status1">Loading...</span></p>
    
    <h2>Test 2: Absolute Path</h2>
    <img src="<?php echo $wo['config']['site_url'] ?? 'http://localhost/facesofnaija-web'; ?>/upload/files/2022/09/iZcVfFlay3gkABhEhtVC_01_771d67d0b8ae8720f7775be3a0cfb51a_file.svg" width="200" height="200" style="border:1px solid blue;">
    <p>Status: <span id="status2">Loading...</span></p>
    
    <h2>Test 3: SVG from Theme</h2>
    <img src="themes/facesofnaija/img/posts.svg" width="200" height="200" style="border:1px solid green;">
    <p>Status: <span id="status3">Loading...</span></p>
    
    <h2>File Check:</h2>
    <pre><?php
    $file = 'upload/files/2022/09/iZcVfFlay3gkABhEhtVC_01_771d67d0b8ae8720f7775be3a0cfb51a_file.svg';
    if (file_exists($file)) {
        echo "✓ File exists: $file\n";
        echo "File size: " . filesize($file) . " bytes\n";
        echo "Permissions: " . substr(sprintf('%o', fileperms($file)), -4) . "\n";
        echo "MIME type: " . mime_content_type($file) . "\n";
    } else {
        echo "✗ File NOT found: $file\n";
    }
    
    // Check .htaccess
    if (file_exists('upload/.htaccess')) {
        echo "\n✓ upload/.htaccess exists\n";
        echo "Content:\n" . file_get_contents('upload/.htaccess');
    } else {
        echo "\n✗ upload/.htaccess NOT found\n";
    }
    ?></pre>
    
    <h2>Apache Modules Check:</h2>
    <pre><?php
    if (function_exists('apache_get_modules')) {
        $modules = apache_get_modules();
        echo "mod_rewrite: " . (in_array('mod_rewrite', $modules) ? '✓ Enabled' : '✗ Disabled') . "\n";
        echo "mod_mime: " . (in_array('mod_mime', $modules) ? '✓ Enabled' : '✗ Disabled') . "\n";
    } else {
        echo "apache_get_modules() not available\n";
    }
    ?></pre>
    
    <script>
    document.querySelectorAll('img').forEach((img, index) => {
        img.onload = function() {
            document.getElementById('status' + (index + 1)).textContent = '✓ Loaded successfully';
            document.getElementById('status' + (index + 1)).style.color = 'green';
        };
        img.onerror = function() {
            document.getElementById('status' + (index + 1)).textContent = '✗ Failed to load: ' + this.src;
            document.getElementById('status' + (index + 1)).style.color = 'red';
        };
    });
    </script>
</body>
</html>
