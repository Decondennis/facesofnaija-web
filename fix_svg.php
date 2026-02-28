<?php
// Fix missing SVG and image issues
require_once('config.php');

echo "<h2>Fixing SVG and Image Issues</h2>";

// 1. Create missing directories
$directories = [
    'upload/files/2022/09',
    'upload/files/2022/10',
    'upload/files/2022/11',
    'upload/files/2022/12',
    'upload/photos/2022/09',
    'upload/photos/d-avatar',
    'upload/photos/d-cover'
];

echo "<h3>Creating missing directories...</h3>";
foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
        echo "✓ Created: $dir<br>";
    } else {
        echo "- Already exists: $dir<br>";
    }
}

// 2. Create default avatar SVG
$default_avatar_svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="200" height="200">
  <rect width="200" height="200" fill="#6c63ff"/>
  <circle cx="100" cy="75" r="35" fill="#ffffff"/>
  <ellipse cx="100" cy="150" rx="50" ry="40" fill="#ffffff"/>
</svg>';

$avatar_files = [
    'upload/photos/d-avatar.svg',
    'upload/photos/d-avatar/avatar.svg',
    'themes/facesofnaija/img/default-avatar.svg'
];

echo "<hr><h3>Creating default avatar SVGs...</h3>";
foreach ($avatar_files as $file) {
    $dir = dirname($file);
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($file, $default_avatar_svg);
    echo "✓ Created: $file<br>";
}

// 3. Update database to use default images for users without avatars
$db = new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);

if (!$db->connect_error) {
    echo "<hr><h3>Updating user avatars...</h3>";
    
    $result = $db->query("SELECT COUNT(*) as count FROM Wo_Users WHERE avatar = '' OR avatar IS NULL");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "Users without avatars: " . $row['count'] . "<br>";
        
        // Set default avatar
        $db->query("UPDATE Wo_Users SET avatar = 'upload/photos/d-avatar.svg' WHERE avatar = '' OR avatar IS NULL");
        echo "✓ Updated user avatars<br>";
    }
    
    $db->close();
}

echo "<hr><h3 style='color:green;'>✓ All SVG issues fixed!</h3>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>Delete this file (fix_svg.php) for security</li>";
echo "<li>Clear browser cache (Ctrl+Shift+Delete)</li>";
echo "<li>Refresh the page</li>";
echo "</ol>";
?>
