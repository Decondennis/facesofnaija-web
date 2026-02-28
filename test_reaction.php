<!DOCTYPE html>
<html>
<head>
    <title>Reaction Test</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .test { margin: 20px 0; padding: 20px; background: #f5f5f5; border-radius: 8px; }
        img { border: 2px solid red; margin: 10px; }
        img.loaded { border-color: green; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>🔍 Reaction Emoji Diagnostic</h1>
    
    <div class="test">
        <h2>Test 1: Direct IMG Tag</h2>
        <img src="http://localhost/facesofnaija-web/upload/files/2022/09/EAufYfaIkYQEsYzwvZha_01_4bafb7db09656e1ecb54d195b26be5c3_file.svg" 
             width="50" height="50" id="test1" alt="Like">
        <p id="status1">Loading...</p>
    </div>
    
    <div class="test">
        <h2>Test 2: As Background Image (CSS)</h2>
        <div id="test2" style="width:50px; height:50px; background-image: url('http://localhost/facesofnaija-web/upload/files/2022/09/EAufYfaIkYQEsYzwvZha_01_4bafb7db09656e1ecb54d195b26be5c3_file.svg'); background-size: contain; border: 2px solid red;"></div>
        <p id="status2">Check above</p>
    </div>
    
    <div class="test">
        <h2>Test 3: Simulating Your HTML</h2>
        <span class="like-btn">
            <span class="rea active-like">Like
                <div class="inline_post_count_emoji reaction">
                    <img src="http://localhost/facesofnaija-web/upload/files/2022/09/EAufYfaIkYQEsYzwvZha_01_4bafb7db09656e1ecb54d195b26be5c3_file.svg" id="test3">
                </div>
            </span>
        </span>
        <p id="status3">Loading...</p>
    </div>
    
    <div class="test">
        <h2>Test 4: File Check</h2>
        <pre><?php
        $file = 'upload/files/2022/09/EAufYfaIkYQEsYzwvZha_01_4bafb7db09656e1ecb54d195b26be5c3_file.svg';
        if (file_exists($file)) {
            echo "✓ File exists\n";
            echo "Size: " . filesize($file) . " bytes\n";
            echo "Readable: " . (is_readable($file) ? 'YES' : 'NO') . "\n";
            echo "Full path: " . realpath($file) . "\n";
            
            // Try to read content
            $content = file_get_contents($file);
            echo "Content length: " . strlen($content) . " chars\n";
            echo "\nFirst 200 chars:\n" . substr($content, 0, 200);
        } else {
            echo "✗ FILE NOT FOUND: $file";
        }
        ?></pre>
    </div>
    
    <div class="test">
        <h2>Test 5: HTTP Request Test</h2>
        <button onclick="testFetch()">Test Fetch Request</button>
        <pre id="fetch-result"></pre>
    </div>
    
    <script>
        const tests = [
            { id: 'test1', status: 'status1' },
            { id: 'test3', status: 'status3' }
        ];
        
        tests.forEach(test => {
            const img = document.getElementById(test.id);
            const status = document.getElementById(test.status);
            
            img.addEventListener('load', function() {
                this.classList.add('loaded');
                status.innerHTML = '<span class="success">✓ LOADED SUCCESSFULLY!</span>';
                console.log('Loaded:', this.src);
            });
            
            img.addEventListener('error', function(e) {
                status.innerHTML = '<span class="error">✗ FAILED TO LOAD</span><br>URL: ' + this.src;
                console.error('Failed to load:', this.src, e);
            });
        });
        
        function testFetch() {
            const url = 'http://localhost/facesofnaija-web/upload/files/2022/09/EAufYfaIkYQEsYzwvZha_01_4bafb7db09656e1ecb54d195b26be5c3_file.svg';
            
            fetch(url)
                .then(response => {
                    const result = document.getElementById('fetch-result');
                    result.textContent = 'Status: ' + response.status + '\n';
                    result.textContent += 'Content-Type: ' + response.headers.get('content-type') + '\n';
                    result.textContent += 'Cache-Control: ' + response.headers.get('cache-control') + '\n';
                    
                    return response.text();
                })
                .then(text => {
                    const result = document.getElementById('fetch-result');
                    result.textContent += '\nContent (first 300 chars):\n' + text.substring(0, 300);
                })
                .catch(error => {
                    document.getElementById('fetch-result').textContent = 'ERROR: ' + error;
                });
        }
        
        // Auto-test fetch on load
        window.addEventListener('load', () => {
            setTimeout(testFetch, 1000);
        });
    </script>
    
    <div class="test">
        <h2>⚠️ IMPORTANT:</h2>
        <ol>
            <li><strong>Restart Apache</strong> in XAMPP Control Panel</li>
            <li><strong>Clear browser cache completely</strong> (Ctrl+Shift+Delete)</li>
            <li><strong>Hard refresh</strong> this page (Ctrl+Shift+R)</li>
            <li>Check browser console (F12) for errors</li>
        </ol>
    </div>
</body>
</html>
