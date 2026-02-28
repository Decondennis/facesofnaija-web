<?php
session_start();

echo "<h2>Session & Email Verification Fix</h2>";

// Clear problematic sessions
if (isset($_SESSION['code_id'])) {
    echo "<p>Found code_id session: " . $_SESSION['code_id'] . "</p>";
    unset($_SESSION['code_id']);
    echo "<p style='color:green;'>✓ Cleared code_id session</p>";
}

// Clear all sessions to be safe
session_destroy();
session_start();

echo "<p style='color:green;'>✓ All sessions cleared</p>";

// Now fix database
require_once('config.php');

$db = new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

echo "<hr><h3>Current Config Settings:</h3>";
$check = $db->query("SELECT name, value FROM Wo_Config WHERE name IN ('emailValidation', 'sms_or_email', 'smsVerification', 'user_registration')");
if ($check) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Setting</th><th>Value</th></tr>";
    while ($row = $check->fetch_assoc()) {
        echo "<tr><td>" . $row['name'] . "</td><td>" . $row['value'] . "</td></tr>";
    }
    echo "</table>";
}

echo "<hr><h3>Disabling Email/SMS Verification...</h3>";

$updates = [
    "UPDATE Wo_Config SET value = '0' WHERE name = 'emailValidation'",
    "UPDATE Wo_Config SET value = '0' WHERE name = 'sms_or_email'",
    "UPDATE Wo_Config SET value = '0' WHERE name = 'smsVerification'",
    "UPDATE Wo_Config SET value = '1' WHERE name = 'user_registration'"
];

foreach ($updates as $sql) {
    if ($db->query($sql)) {
        echo "✓ " . $sql . "<br>";
    } else {
        echo "✗ Error: " . $db->error . "<br>";
    }
}

echo "<hr><h3>Activating All Users...</h3>";

// Activate all users
$activate = $db->query("UPDATE Wo_Users SET active = '1', email_code = '0'");
if ($activate) {
    echo "<p style='color:green;'>✓ Activated " . $db->affected_rows . " users</p>";
}

echo "<hr><h3>Clearing Cache...</h3>";

// Clear cache
$cache_dir = 'cache/';
$count = 0;
$files = glob($cache_dir . '*');
foreach($files as $file) {
    if(is_file($file) && basename($file) !== '.htaccess') {
        unlink($file);
        $count++;
    }
}
echo "<p style='color:green;'>✓ Cleared $count cache files</p>";

$db->close();

echo "<hr><h2 style='color:green;'>✓✓✓ ALL FIXED! ✓✓✓</h2>";
echo "<h3>IMPORTANT - Do These Steps Now:</h3>";
echo "<ol>";
echo "<li><strong>Close ALL browser tabs</strong> of facesofnaija-web</li>";
echo "<li><strong>Clear browser cache</strong> (Ctrl+Shift+Delete)</li>";
echo "<li><strong>Restart Apache</strong> in XAMPP Control Panel</li>";
echo "<li><strong>Open NEW Incognito window</strong> (Ctrl+Shift+N)</li>";
echo "<li>Go to: <a href='http://localhost/facesofnaija-web' target='_blank'>http://localhost/facesofnaija-web</a></li>";
echo "<li>Login normally - NO email verification required!</li>";
echo "</ol>";

echo "<hr><p><strong>After successful login, delete these files:</strong></p>";
echo "<ul>";
echo "<li>complete_fix.php (this file)</li>";
echo "<li>check_config.php</li>";
echo "<li>activate_all_users.php</li>";
echo "<li>fix_svg.php</li>";
echo "<li>test_svg.php</li>";
echo "</ul>";
?>
