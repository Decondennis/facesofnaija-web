<!DOCTYPE html>
<html>
<head>
    <title>Text-to-Speech Test</title>
    <style>
        body { font-family: Arial; padding: 40px; background: #f5f5f5; }
        .box { background: white; padding: 30px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        button { background: #4CAF50; color: white; padding: 15px 30px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; margin: 10px; }
        button:hover { background: #45a049; }
        .greeting { font-size: 24px; padding: 20px; background: #e8f5e9; border-left: 4px solid #4CAF50; margin: 20px 0; }
    </style>
</head>
<body>
    <h1>🔊 Text-to-Speech Greeting Test</h1>
    
    <div class="box">
        <h2>How It Works:</h2>
        <p>When you load your homepage, the greeting message will automatically:</p>
        <ol>
            <li>✅ Display on screen (as before)</li>
            <li>✅ <strong>Speak out loud</strong> using your browser's text-to-speech engine</li>
        </ol>
    </div>
    
    <div class="box">
        <h2>Test the Greetings:</h2>
        
        <div class="greeting" id="morning">
            🌅 Good Morning, User! <br>
            <small>Start your day with positivity!</small>
        </div>
        <button onclick="speakGreeting('morning')">🔊 Test Morning Greeting</button>
        
        <div class="greeting" id="afternoon">
            ☀️ Good Afternoon, User! <br>
            <small>Keep up the great work!</small>
        </div>
        <button onclick="speakGreeting('afternoon')">🔊 Test Afternoon Greeting</button>
        
        <div class="greeting" id="evening">
            🌙 Good Evening, User! <br>
            <small>Relax and unwind!</small>
        </div>
        <button onclick="speakGreeting('evening')">🔊 Test Evening Greeting</button>
    </div>
    
    <div class="box">
        <h2>Voice Settings Test:</h2>
        <label>Speed: <input type="range" id="rate" min="0.5" max="2" value="0.9" step="0.1"> <span id="rateValue">0.9</span></label><br>
        <label>Pitch: <input type="range" id="pitch" min="0" max="2" value="1" step="0.1"> <span id="pitchValue">1</span></label><br>
        <label>Volume: <input type="range" id="volume" min="0" max="1" value="1" step="0.1"> <span id="volumeValue">1</span></label><br>
        <button onclick="testCustom()">🔊 Test Custom Settings</button>
    </div>
    
    <div class="box">
        <h2>Browser Support:</h2>
        <div id="support"></div>
    </div>
    
    <script>
        // Check browser support
        if ('speechSynthesis' in window) {
            document.getElementById('support').innerHTML = '<p style="color:green;">✅ Your browser supports Text-to-Speech!</p>';
        } else {
            document.getElementById('support').innerHTML = '<p style="color:red;">❌ Your browser does NOT support Text-to-Speech. Try Chrome, Edge, or Firefox.</p>';
        }
        
        // Update slider values
        document.getElementById('rate').oninput = function() {
            document.getElementById('rateValue').textContent = this.value;
        };
        document.getElementById('pitch').oninput = function() {
            document.getElementById('pitchValue').textContent = this.value;
        };
        document.getElementById('volume').oninput = function() {
            document.getElementById('volumeValue').textContent = this.value;
        };
        
        function speakGreeting(time) {
            var messages = {
                'morning': 'Good Morning, User! Start your day with positivity!',
                'afternoon': 'Good Afternoon, User! Keep up the great work!',
                'evening': 'Good Evening, User! Relax and unwind!'
            };
            
            var text = messages[time];
            var utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-US';
            utterance.rate = 0.9;
            utterance.pitch = 1;
            utterance.volume = 1;
            
            window.speechSynthesis.speak(utterance);
        }
        
        function testCustom() {
            var rate = document.getElementById('rate').value;
            var pitch = document.getElementById('pitch').value;
            var volume = document.getElementById('volume').value;
            
            var text = 'Good Morning! This is a test with custom settings.';
            var utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-US';
            utterance.rate = parseFloat(rate);
            utterance.pitch = parseFloat(pitch);
            utterance.volume = parseFloat(volume);
            
            window.speechSynthesis.speak(utterance);
        }
    </script>
</body>
</html>
