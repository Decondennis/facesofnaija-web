<!DOCTYPE html>
<html>
<head>
    <title>Announcement Carousel Test</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { font-family: Arial; padding: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; }
        .demo-box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; }
        
        .home-announcement {
            position: relative;
            height: 50px;
            overflow: hidden;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 4px;
        }
        
        .alert {
            background-color: white;
            color: #333;
            height: 50px;
            padding: 10px 60px 10px 15px;
            margin: 0;
            display: flex;
            align-items: center;
            position: relative;
            border-left: 4px solid #4CAF50;
        }
        
        .announcement-globe {
            position: absolute;
            top: 5px;
            right: 40px;
            width: 40px;
            height: 40px;
        }
        
        .announcement-globe img {
            width: 100%;
            height: 100%;
        }
        
        .close {
            position: absolute;
            right: 10px;
            top: 10px;
            z-index: 10;
            cursor: pointer;
            font-size: 20px;
            opacity: 0.5;
        }
        
        .close:hover {
            opacity: 1;
        }
        
        .announcement-carousel {
            width: calc(100% - 60px);
            overflow: hidden;
            white-space: nowrap;
        }
        
        .announcement-text {
            display: inline-block;
            padding-left: 100%;
            animation: scroll-left 20s linear infinite;
        }
        
        @keyframes scroll-left {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
        
        .announcement-carousel:hover .announcement-text {
            animation-play-state: paused;
        }
        
        .controls {
            margin: 20px 0;
        }
        
        .controls button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .controls button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌍 Announcement Carousel with Rotating Globe</h1>
        
        <div class="demo-box">
            <h2>Preview:</h2>
            
            <div class="home-announcement">
                <div class="alert">
                    <!-- Rotating Globe -->
                    <div class="announcement-globe">
                        <img src="themes/facesofnaija/img/globe.svg" alt="Globe">
                    </div>
                    
                    <span class="close" onclick="alert('Close clicked!')">
                        <i class="fa fa-remove"></i>
                    </span>
                    
                    <!-- Scrolling Text Carousel -->
                    <div class="announcement-carousel">
                        <div class="announcement-text" id="announcement-text">
                            <strong>📢 </strong>Welcome to FacesofNaija! Check out the latest updates and announcements. Stay connected with your community!
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="demo-box">
            <h2>Controls:</h2>
            <div class="controls">
                <label>Animation Speed: 
                    <select onchange="changeSpeed(this.value)">
                        <option value="30">Slow (30s)</option>
                        <option value="20" selected>Normal (20s)</option>
                        <option value="10">Fast (10s)</option>
                        <option value="5">Very Fast (5s)</option>
                    </select>
                </label>
                <br><br>
                <button onclick="pauseAnimation()">⏸️ Pause</button>
                <button onclick="playAnimation()">▶️ Play</button>
                <button onclick="changeText()">📝 Change Text</button>
            </div>
        </div>
        
        <div class="demo-box">
            <h2>✅ Features:</h2>
            <ul>
                <li>✓ <strong>Rotating globe</strong> at top right corner</li>
                <li>✓ <strong>Scrolling text</strong> carousel animation</li>
                <li>✓ <strong>Reduced height</strong> to 50px</li>
                <li>✓ <strong>Hover to pause</strong> - hover over text to pause scrolling</li>
                <li>✓ <strong>Close button</strong> to hide announcement</li>
                <li>✓ <strong>Responsive design</strong></li>
            </ul>
        </div>
        
        <div class="demo-box">
            <h2>📋 Implementation:</h2>
            <p>The code has been added to:</p>
            <code style="background: #f5f5f5; padding: 10px; display: block; border-radius: 4px;">
                themes/facesofnaija/layout/home/content.phtml
            </code>
            <br>
            <p>Globe SVG created at:</p>
            <code style="background: #f5f5f5; padding: 10px; display: block; border-radius: 4px;">
                themes/facesofnaija/img/globe.svg
            </code>
        </div>
    </div>
    
    <script>
        function changeSpeed(seconds) {
            const text = document.querySelector('.announcement-text');
            text.style.animation = `scroll-left ${seconds}s linear infinite`;
        }
        
        function pauseAnimation() {
            const text = document.querySelector('.announcement-text');
            text.style.animationPlayState = 'paused';
        }
        
        function playAnimation() {
            const text = document.querySelector('.announcement-text');
            text.style.animationPlayState = 'running';
        }
        
        function changeText() {
            const texts = [
                '<strong>📢 </strong>Welcome to FacesofNaija! Check out the latest updates and announcements.',
                '<strong>🎉 </strong>New features released! Explore our communities and connect with friends.',
                '<strong>⚡ </strong>Important: System maintenance scheduled for tonight. Please save your work.',
                '<strong>🌟 </strong>Join our growing community! Share your stories and experiences with others.'
            ];
            const randomText = texts[Math.floor(Math.random() * texts.length)];
            document.getElementById('announcement-text').innerHTML = randomText;
        }
    </script>
</body>
</html>
