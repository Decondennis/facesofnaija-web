<?php
// Create all reaction emoji SVGs

$directory = 'upload/files/2022/09/';

// Ensure directory exists
if (!file_exists($directory)) {
    mkdir($directory, 0777, true);
}

// Like/Thumbs Up SVG (the one you need)
$like_svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100">
  <defs>
    <radialGradient id="likeGrad">
      <stop offset="0%" style="stop-color:#5890ff;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#1877f2;stop-opacity:1" />
    </radialGradient>
  </defs>
  <circle cx="50" cy="50" r="48" fill="url(#likeGrad)"/>
  <path d="M 30 55 L 30 72 C 30 73 31 74 32 74 L 40 74 C 41 74 42 73 42 72 L 42 55 C 42 54 41 53 40 53 L 32 53 C 31 53 30 54 30 55 Z M 62 45 C 62 42 60 40 57 40 L 48 40 L 49 32 C 49 31 49 30 48 29 C 47 28 46 27 45 27 C 44 27 43 28 43 29 L 40 40 L 40 52 C 40 53 41 54 42 54 L 56 54 C 58 54 60 52 60 50 L 63 47 C 63 46 62 45 62 45 Z" fill="#ffffff"/>
</svg>';

// Love/Heart SVG
$love_svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100">
  <defs>
    <radialGradient id="loveGrad">
      <stop offset="0%" style="stop-color:#ff6b6b;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#ee5253;stop-opacity:1" />
    </radialGradient>
  </defs>
  <circle cx="50" cy="50" r="48" fill="url(#loveGrad)"/>
  <path d="M 50 70 L 35 55 C 30 50 30 42 35 37 C 40 32 48 32 50 37 C 52 32 60 32 65 37 C 70 42 70 50 65 55 Z" fill="#ffffff"/>
</svg>';

// Haha/Laughing SVG
$haha_svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100">
  <defs>
    <radialGradient id="hahaGrad">
      <stop offset="0%" style="stop-color:#ffd32a;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#f9ca24;stop-opacity:1" />
    </radialGradient>
  </defs>
  <circle cx="50" cy="50" r="48" fill="url(#hahaGrad)"/>
  <ellipse cx="35" cy="42" rx="3" ry="8" fill="#553000"/>
  <ellipse cx="65" cy="42" rx="3" ry="8" fill="#553000"/>
  <path d="M 30 60 Q 50 75 70 60" stroke="#553000" stroke-width="3" fill="none" stroke-linecap="round"/>
  <path d="M 30 60 Q 50 70 70 60 Q 50 65 30 60" fill="#553000"/>
</svg>';

// Wow/Surprised SVG
$wow_svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100">
  <defs>
    <radialGradient id="wowGrad">
      <stop offset="0%" style="stop-color:#ffd93d;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#f9ca24;stop-opacity:1" />
    </radialGradient>
  </defs>
  <circle cx="50" cy="50" r="48" fill="url(#wowGrad)"/>
  <circle cx="35" cy="40" r="6" fill="#553000"/>
  <circle cx="65" cy="40" r="6" fill="#553000"/>
  <ellipse cx="50" cy="65" rx="8" ry="12" fill="#553000"/>
</svg>';

// Sad/Crying SVG
$sad_svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100">
  <defs>
    <radialGradient id="sadGrad">
      <stop offset="0%" style="stop-color:#ffd93d;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#f9ca24;stop-opacity:1" />
    </radialGradient>
  </defs>
  <circle cx="50" cy="50" r="48" fill="url(#sadGrad)"/>
  <path d="M 30 42 Q 35 38 40 42" stroke="#553000" stroke-width="2" fill="none" stroke-linecap="round"/>
  <path d="M 60 42 Q 65 38 70 42" stroke="#553000" stroke-width="2" fill="none" stroke-linecap="round"/>
  <path d="M 30 68 Q 50 60 70 68" stroke="#553000" stroke-width="3" fill="none" stroke-linecap="round"/>
  <circle cx="38" cy="50" r="2" fill="#4ecdc4"/>
  <path d="M 38 52 L 38 62" stroke="#4ecdc4" stroke-width="2"/>
</svg>';

// Angry SVG
$angry_svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100">
  <defs>
    <radialGradient id="angryGrad">
      <stop offset="0%" style="stop-color:#ff6348;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#e84118;stop-opacity:1" />
    </radialGradient>
  </defs>
  <circle cx="50" cy="50" r="48" fill="url(#angryGrad)"/>
  <path d="M 28 38 L 40 42" stroke="#000" stroke-width="3" stroke-linecap="round"/>
  <path d="M 72 38 L 60 42" stroke="#000" stroke-width="3" stroke-linecap="round"/>
  <circle cx="35" cy="45" r="4" fill="#000"/>
  <circle cx="65" cy="45" r="4" fill="#000"/>
  <path d="M 35 65 Q 50 58 65 65" stroke="#000" stroke-width="3" fill="none" stroke-linecap="round"/>
</svg>';

$reactions = [
    'EAufYfaIkYQEsYzwvZha_01_4bafb7db09656e1ecb54d195b26be5c3_file.svg' => $like_svg,
    'like.svg' => $like_svg,
    'love.svg' => $love_svg,
    'haha.svg' => $haha_svg,
    'wow.svg' => $wow_svg,
    'sad.svg' => $sad_svg,
    'angry.svg' => $angry_svg,
];

echo "<h1>Creating Reaction Emoji SVGs</h1>";

foreach ($reactions as $filename => $svg_content) {
    $filepath = $directory . $filename;
    file_put_contents($filepath, $svg_content);
    echo "<p>✓ Created: $filepath</p>";
    echo "<img src='$filepath' width='50' height='50' style='border:1px solid #ddd; margin:5px;'>";
}

echo "<hr>";
echo "<h2 style='color:green;'>✓ All reaction emojis created!</h2>";
echo "<p>Now refresh your page and the like icons should display.</p>";

echo "<h3>Test the reactions:</h3>";
echo "<div style='background:#f5f5f5; padding:20px;'>";
foreach ($reactions as $filename => $svg) {
    $filepath = $directory . $filename;
    echo "<div style='display:inline-block; margin:10px; text-align:center;'>";
    echo "<img src='$filepath' width='60' height='60' style='border:2px solid #ddd; border-radius:50%;'><br>";
    echo "<small>" . str_replace('.svg', '', $filename) . "</small>";
    echo "</div>";
}
echo "</div>";

echo "<hr>";
echo "<p><strong>After confirming icons work, delete this file:</strong> create_reactions.php</p>";
?>
