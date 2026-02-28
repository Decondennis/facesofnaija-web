<?php
header('Content-Type: text/html; charset=utf-8');

$svg_path = 'upload/files/2022/09/iZcVfFlay3gkABhEhtVC_01_771d67d0b8ae8720f7775be3a0cfb51a_file.svg';
$full_path = __DIR__ . '/' . $svg_path;

?>
<!DOCTYPE html>
<html>
<head>
    <title>SVG Diagnostic</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .test-box { border: 2px solid #ccc; padding: 10px; margin: 10px 0; }
        .success { color: green; }
        .error { color: red; }
        img { border: 2px solid red; margin: 10px; }
    </style>
</head>
<body>
    <h1>SVG Diagnostic Report</h1>
    
    <div class="test-box">
        <h2>1. File System Check</h2>
        <?php
        if (file_exists($full_path)) {
            echo "<p class='success'>✓ File exists: $svg_path</p>";
            echo "<p>Full path: $full_path</p>";
            echo "<p>File size: " . filesize($full_path) . " bytes</p>";
            echo "<p>Permissions: " . substr(sprintf('%o', fileperms($full_path)), -4) . "</p>";
            echo "<p>Is readable: " . (is_readable($full_path) ? 'YES' : 'NO') . "</p>";
        } else {
            echo "<p class='error'>✗ File NOT found: $full_path</p>";
        }
        ?>
    </div>
    
    <div class="test-box">
        <h2>2. SVG Content Check</h2>
        <?php
        if (file_exists($full_path)) {
            $content = file_get_contents($full_path);
            echo "<p>Content length: " . strlen($content) . " characters</p>";
            echo "<details><summary>View SVG Source</summary><pre>" . htmlspecialchars($content) . "</pre></details>";
        }
        ?>
    </div>
    
    <div class="test-box">
        <h2>3. Direct File Output Test</h2>
        <p>Outputting SVG directly from PHP:</p>
        <?php
        if (file_exists($full_path)) {
            echo file_get_contents($full_path);
        }
        ?>
    </div>
    
    <div class="test-box">
        <h2>4. IMG Tag Tests</h2>
        
        <h3>Test A: Relative Path</h3>
        <img src="<?php echo $svg_path; ?>" width="100" height="100" alt="Test A">
        <p>Path: <?php echo $svg_path; ?></p>
        
        <h3>Test B: Absolute URL</h3>
        <img src="http://localhost/facesofnaija-web/<?php echo $svg_path; ?>" width="100" height="100" alt="Test B">
        <p>Path: http://localhost/facesofnaija-web/<?php echo $svg_path; ?></p>
        
        <h3>Test C: Data URI</h3>
        <?php
        if (file_exists($full_path)) {
            $svg_content = file_get_contents($full_path);
            $data_uri = 'data:image/svg+xml;base64,' . base64_encode($svg_content);
            echo '<img src="' . $data_uri . '" width="100" height="100" alt="Test C">';
            echo '<p>Using base64 encoded data URI</p>';
        }
        ?>
    </div>
    
    <div class="test-box">
        <h2>5. Server Configuration</h2>
        <?php
        if (function_exists('apache_get_modules')) {
            $modules = apache_get_modules();
            echo "<p>mod_rewrite: " . (in_array('mod_rewrite', $modules) ? '✓ Enabled' : '✗ Disabled') . "</p>";
            echo "<p>mod_mime: " . (in_array('mod_mime', $modules) ? '✓ Enabled' : '✗ Disabled') . "</p>";
        }
        
        if (function_exists('apache_get_version')) {
            echo "<p>Apache version: " . apache_get_version() . "</p>";
        }
        ?>
    </div>
    
    <div class="test-box">
        <h2>6. .htaccess Files</h2>
        <?php
        $htaccess_files = [
            '.htaccess',
            'upload/.htaccess',
            'upload/files/.htaccess'
        ];
        
        foreach ($htaccess_files as $htfile) {
            if (file_exists($htfile)) {
                echo "<h4>$htfile:</h4>";
                echo "<pre>" . htmlspecialchars(file_get_contents($htfile)) . "</pre>";
            } else {
                echo "<p class='error'>$htfile not found</p>";
            }
        }
        ?>
    </div>
    
    <div class="test-box">
        <h2>7. AJAX Test</h2>
        <button onclick="testAjax()">Test AJAX Load</button>
        <div id="ajax-result"></div>
    </div>
    
    <script>
        // Monitor image loading
        document.querySelectorAll('img').forEach((img, index) => {
            img.addEventListener('load', function() {
                console.log('Image loaded:', this.src);
                this.style.borderColor = 'green';
            });
            
            img.addEventListener('error', function() {
                console.error('Image failed:', this.src);
                this.style.borderColor = 'red';
                this.parentElement.innerHTML += '<p class="error">Failed to load: ' + this.src + '</p>';
            });
        });
        
        function testAjax() {
            fetch('<?php echo $svg_path; ?>')
                .then(response => {
                    document.getElementById('ajax-result').innerHTML = 
                        '<p>Status: ' + response.status + '</p>' +
                        '<p>Content-Type: ' + response.headers.get('content-type') + '</p>';
                    return response.text();
                })
                .then(data => {
                    document.getElementById('ajax-result').innerHTML += 
                        '<p class="success">✓ AJAX loaded successfully</p>' +
                        '<details><summary>Response</summary><pre>' + 
                        data.substring(0, 500) + '</pre></details>';
                })
                .catch(error => {
                    document.getElementById('ajax-result').innerHTML = 
                        '<p class="error">✗ AJAX failed: ' + error + '</p>';
                });
        }
    </script>
    
    <div class="test-box">
        <h2>8. Create Simple Test SVG</h2>
        <?php
        // Create a super simple inline SVG for comparison
        ?>
        <h3>Inline SVG (should always work):</h3>
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="40" fill="blue"/>
        </svg>
    </div>
    
</body>
</html>
