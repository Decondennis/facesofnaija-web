<?php
// Check what headers are being sent with the SVG
$url = 'http://localhost/facesofnaija-web/upload/files/2022/09/iZcVfFlay3gkABhEhtVC_01_771d67d0b8ae8720f7775be3a0cfb51a_file.svg';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);

$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

header('Content-Type: text/html');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Header Check</title>
    <style>
        body { font-family: monospace; padding: 20px; }
        .good { color: green; }
        .bad { color: red; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>SVG Response Headers</h1>
    
    <h2>HTTP Status: <?php echo $info['http_code']; ?></h2>
    
    <h2>Response Headers:</h2>
    <pre><?php echo htmlspecialchars($response); ?></pre>
    
    <h2>Analysis:</h2>
    <ul>
        <?php
        if ($info['http_code'] == 200) {
            echo '<li class="good">✓ HTTP 200 OK</li>';
        } else {
            echo '<li class="bad">✗ HTTP ' . $info['http_code'] . '</li>';
        }
        
        if (isset($info['content_type']) && strpos($info['content_type'], 'svg') !== false) {
            echo '<li class="good">✓ Content-Type: ' . $info['content_type'] . '</li>';
        } else {
            echo '<li class="bad">✗ Content-Type: ' . ($info['content_type'] ?? 'not set') . '</li>';
        }
        
        if (strpos($response, 'must-revalidate') !== false || strpos($response, 'no-store') !== false) {
            echo '<li class="bad">✗ Has restrictive cache headers (this is the problem!)</li>';
        } else {
            echo '<li class="good">✓ No restrictive cache headers</li>';
        }
        
        if (strpos($response, 'Access-Control-Allow-Origin') !== false) {
            echo '<li class="good">✓ CORS headers present</li>';
        } else {
            echo '<li>⚠ No CORS headers (might be okay)</li>';
        }
        ?>
    </ul>
    
    <hr>
    <h2>Now restart Apache and refresh!</h2>
    <p><a href="quick_test.php">← Back to Quick Test</a></p>
</body>
</html>
