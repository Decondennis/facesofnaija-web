# 🔊 Text-to-Speech Greeting - INSTALLED ✅

## What Was Added:

Your homepage greeting (Good Morning/Afternoon/Evening) will now **speak out loud** when you visit the page!

---

## How It Works:

1. **When you visit the homepage**, the JavaScript detects the time of day
2. **Displays the greeting** in the alert box (as before)
3. **NEW:** Automatically speaks the greeting using your browser's built-in text-to-speech engine

---

## Features:

✅ **Automatic**: Plays automatically when the page loads  
✅ **Time-aware**: Different greeting based on time of day  
✅ **No plugins needed**: Uses browser's native Web Speech API  
✅ **Customizable**: Speech rate, pitch, and volume can be adjusted  

---

## The Code Added (in `themes/facesofnaija/layout/home/content.phtml`):

```javascript
// Text-to-Speech functionality
if ('speechSynthesis' in window) {
  var greetText = greet.replace(/<[^>]*>/g, ''); // Remove HTML tags
  var speechText = greetText + '. ' + quote;
  
  var utterance = new SpeechSynthesisUtterance(speechText);
  utterance.lang = 'en-US';
  utterance.rate = 0.9;  // Speed
  utterance.pitch = 1;   // Pitch
  utterance.volume = 1;  // Volume
  
  setTimeout(function() {
    window.speechSynthesis.speak(utterance);
  }, 500);
}
```

---

## Test It:

### Option 1: Test Page
Open: **http://localhost/facesofnaija-web/test_tts.php**

- Click the buttons to hear different greetings
- Adjust speed, pitch, and volume
- Test your browser compatibility

### Option 2: Live Test
1. Go to: **http://localhost/facesofnaija-web**
2. **Clear your cookie** for the greeting (or wait 3 days)
3. **Refresh the page**
4. You should hear: "Good [Morning/Afternoon/Evening], [Your Name]. [Quote]"

---

## Customization:

### Change Speech Speed:
In `themes/facesofnaija/layout/home/content.phtml`, line ~351, change:
```javascript
utterance.rate = 0.9; // 0.5 = slow, 1 = normal, 2 = fast
```

### Change Voice Pitch:
```javascript
utterance.pitch = 1; // 0 = low, 1 = normal, 2 = high
```

### Change Volume:
```javascript
utterance.volume = 1; // 0 = mute, 1 = full volume
```

### Change Language:
```javascript
utterance.lang = 'en-US'; // Options: en-GB, en-AU, fr-FR, es-ES, etc.
```

---

## Browser Support:

✅ **Chrome** (Windows, Mac, Android)  
✅ **Edge** (Windows, Mac)  
✅ **Safari** (Mac, iOS)  
✅ **Firefox** (Windows, Mac, Linux)  
❌ **Opera** (limited support)  
❌ **IE** (not supported)  

---

## Disable It:

If you want to turn off the audio:

### Option 1: Comment Out the Code
In `themes/facesofnaija/layout/home/content.phtml`, add `//` before lines 348-360

### Option 2: Mute Volume
Change `utterance.volume = 0;`

---

## Troubleshooting:

### No sound?
1. Check browser volume
2. Check system volume
3. Try a different browser (Chrome recommended)
4. Check browser console (F12) for errors

### Wrong voice?
Different browsers use different default voices. You can select a specific voice:

```javascript
var voices = window.speechSynthesis.getVoices();
utterance.voice = voices[0]; // Change index to select different voice
```

---

## Next Steps:

✅ Test it: **http://localhost/facesofnaija-web/test_tts.php**  
✅ Adjust settings if needed  
✅ Enjoy your talking website! 🎉  

---

**Implemented:** <?php echo date('Y-m-d H:i:s'); ?>
