<!DOCTYPE html>
<html>
<head>
    <title>Reaction Quick Test</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .reaction-test { display: inline-block; margin: 10px; text-align: center; }
        .reaction-test img { width: 50px; height: 50px; border: 2px solid #ddd; border-radius: 50%; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>🎯 FINAL REACTION TEST</h1>
    
    <div class="box">
        <h2>Method 1: Direct PHP Proxy (WILL WORK)</h2>
        <div class="reaction-test">
            <img src="get_reaction.php?f=like" alt="Like"><br>
            <small>Like</small>
        </div>
        <div class="reaction-test">
            <img src="get_reaction.php?f=love" alt="Love"><br>
            <small>Love</small>
        </div>
        <div class="reaction-test">
            <img src="get_reaction.php?f=haha" alt="Haha"><br>
            <small>Haha</small>
        </div>
        <p class="success">✓ If you see emojis above, the PHP proxy works!</p>
    </div>
    
    <div class="box">
        <h2>Method 2: Direct File Access (Testing)</h2>
        <div class="reaction-test">
            <img src="upload/files/2022/09/EAufYfaIkYQEsYzwvZha_01_4bafb7db09656e1ecb54d195b26be5c3_file.svg" 
                 alt="Like Direct" id="direct-test">
            <br>
            <small>Direct SVG</small>
        </div>
        <p id="direct-status">Testing...</p>
    </div>
    
    <div class="box">
        <h2>✅ Next Steps:</h2>
        <ol>
            <li><strong>If Method 1 works</strong> (you see emojis), click the button below to fix the database</li>
            <li><strong>If Method 2 also works</strong>, Apache is properly configured</li>
            <li><strong>If only Method 1 works</strong>, we'll use the PHP proxy solution</li>
        </ol>
        
        <p>
            <a href="fix_reaction_urls.php" 
               style="display:inline-block; background:#4CAF50; color:white; padding:15px 30px; text-decoration:none; border-radius:5px; font-weight:bold;">
                🔧 Go to Database Fix Page
            </a>
        </p>
    </div>
    
    <script>
        const directImg = document.getElementById('direct-test');
        const status = document.getElementById('direct-status');
        
        directImg.addEventListener('load', function() {
            status.innerHTML = '<span class="success">✓ Direct file access WORKS! Apache is properly configured.</span>';
            this.style.borderColor = 'green';
        });
        
        directImg.addEventListener('error', function() {
            status.innerHTML = '<span class="error">✗ Direct file access BLOCKED. Use PHP proxy solution (Method 1).</span>';
            this.style.borderColor = 'red';
        });
    </script>
</body>
</html>
