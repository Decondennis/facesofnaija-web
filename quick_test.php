<!DOCTYPE html>
<html>
<head>
    <title>SVG Quick Test</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .test-box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        img { border: 3px solid #ddd; margin: 10px; background: white; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>🎨 SVG Quick Test</h1>
    
    <div class="test-box">
        <h2>Test 1: Direct SVG Link</h2>
        <p>Click this link: <a href="upload/files/2022/09/iZcVfFlay3gkABhEhtVC_01_771d67d0b8ae8720f7775be3a0cfb51a_file.svg" target="_blank">Open SVG Directly</a></p>
        <p>Should open the SVG in a new tab. If you see the SVG image, it works!</p>
    </div>
    
    <div class="test-box">
        <h2>Test 2: IMG Tag</h2>
        <img src="upload/files/2022/09/iZcVfFlay3gkABhEhtVC_01_771d67d0b8ae8720f7775be3a0cfb51a_file.svg" width="150" height="150" id="testimg">
        <p id="imgstatus">Loading...</p>
    </div>
    
    <div class="test-box">
        <h2>Test 3: Background Image (CSS)</h2>
        <div style="width:150px; height:150px; background-image: url('upload/files/2022/09/iZcVfFlay3gkABhEhtVC_01_771d67d0b8ae8720f7775be3a0cfb51a_file.svg'); background-size: cover; border: 3px solid #ddd;"></div>
    </div>
    
    <div class="test-box">
        <h2>Test 4: Other Theme SVG (Control Test)</h2>
        <img src="themes/facesofnaija/img/posts.svg" width="150" height="150" id="control">
        <p id="controlstatus">Loading...</p>
    </div>
    
    <script>
        const testimg = document.getElementById('testimg');
        const control = document.getElementById('control');
        
        testimg.onload = function() {
            document.getElementById('imgstatus').innerHTML = '<span class="success">✓ SUCCESS! SVG loaded via IMG tag</span>';
            testimg.style.borderColor = 'green';
        };
        
        testimg.onerror = function() {
            document.getElementById('imgstatus').innerHTML = '<span class="error">✗ FAILED! Check console for errors</span>';
            testimg.style.borderColor = 'red';
        };
        
        control.onload = function() {
            document.getElementById('controlstatus').innerHTML = '<span class="success">✓ Theme SVG works</span>';
            control.style.borderColor = 'green';
        };
        
        control.onerror = function() {
            document.getElementById('controlstatus').innerHTML = '<span class="error">✗ Theme SVG failed</span>';
            control.style.borderColor = 'red';
        };
        
        // Network test
        fetch('upload/files/2022/09/iZcVfFlay3gkABhEhtVC_01_771d67d0b8ae8720f7775be3a0cfb51a_file.svg')
            .then(response => {
                console.log('Fetch response:', response);
                console.log('Status:', response.status);
                console.log('Content-Type:', response.headers.get('content-type'));
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    </script>
    
    <div class="test-box">
        <h2>✅ Next Steps:</h2>
        <ol>
            <li><strong>Restart Apache</strong> in XAMPP Control Panel (Stop → Start)</li>
            <li><strong>Hard refresh</strong> this page (Ctrl + Shift + R)</li>
            <li>Check if Test 2 shows green border</li>
            <li>If it works, refresh your main site!</li>
        </ol>
    </div>
</body>
</html>
