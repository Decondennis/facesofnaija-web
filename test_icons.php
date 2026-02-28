<!DOCTYPE html>
<html>
<head>
    <title>Icon Test</title>
    <link rel="stylesheet" href="themes/facesofnaija/stylesheet/font-awesome-4.7.0/css/font-awesome.css">
    <style>
        body { font-family: Arial; padding: 20px; }
        .test-box { background: white; padding: 20px; margin: 20px 0; border: 1px solid #ddd; }
        .icon-test { font-size: 48px; margin: 10px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>🎨 Font Awesome Icon Test</h1>
    
    <div class="test-box">
        <h2>Test Icons:</h2>
        <div class="icon-test">
            <i class="fa fa-heart"></i> Heart (Like)
        </div>
        <div class="icon-test">
            <i class="fa fa-thumbs-up"></i> Thumbs Up
        </div>
        <div class="icon-test">
            <i class="fa fa-comment"></i> Comment
        </div>
        <div class="icon-test">
            <i class="fa fa-share"></i> Share
        </div>
        <div class="icon-test">
            <i class="fa fa-user"></i> User
        </div>
    </div>
    
    <div class="test-box">
        <h2>Font Loading Status:</h2>
        <p id="font-status">Checking...</p>
    </div>
    
    <script>
        // Check if Font Awesome loaded
        document.fonts.ready.then(function() {
            let loaded = false;
            document.fonts.forEach(function(font) {
                if (font.family.includes('FontAwesome')) {
                    loaded = true;
                }
            });
            
            if (loaded) {
                document.getElementById('font-status').innerHTML = '<span class="success">✓ Font Awesome loaded successfully!</span>';
            } else {
                document.getElementById('font-status').innerHTML = '<span class="error">✗ Font Awesome NOT loaded. Check console for errors.</span>';
            }
        });
        
        // Check network requests
        window.addEventListener('load', function() {
            console.log('Page loaded. Check Network tab for font files (.woff2, .woff, .ttf)');
        });
    </script>
    
    <div class="test-box">
        <h2>✅ Instructions:</h2>
        <ol>
            <li><strong>Restart Apache</strong> in XAMPP Control Panel</li>
            <li><strong>Hard refresh</strong> (Ctrl + Shift + R)</li>
            <li>You should see proper icons above (not boxes)</li>
            <li>Press F12 → Network tab → Look for font files loading with 200 OK</li>
        </ol>
    </div>
</body>
</html>
