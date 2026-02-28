<?php
// Quick config checker and fixer
require_once('config.php');

$db = new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

echo "<h2>Current Settings:</h2>";

// Check current values
$check = $db->query("SELECT name, value FROM Wo_Config WHERE name IN ('emailValidation', 'sms_or_email', 'nodejs', 'user_registration', 'nodejs_ssl')");

if ($check) {
    while ($row = $check->fetch_assoc()) {
        echo $row['name'] . " = " . $row['value'] . "<br>";
    }
}

echo "<hr><h2>Updating Settings...</h2>";

// Update settings
$updates = [
    "UPDATE Wo_Config SET value = '0' WHERE name = 'emailValidation'",
    "UPDATE Wo_Config SET value = '0' WHERE name = 'sms_or_email'",
    "UPDATE Wo_Config SET value = '0' WHERE name = 'smsVerification'",
    "UPDATE Wo_Config SET value = '0' WHERE name = 'nodejs'",
    "UPDATE Wo_Config SET value = '0' WHERE name = 'nodejs_ssl'",
    "UPDATE Wo_Config SET value = '1' WHERE name = 'user_registration'"
];

foreach ($updates as $sql) {
    if ($db->query($sql)) {
        echo "✓ " . $sql . "<br>";
    } else {
        echo "✗ Error: " . $db->error . "<br>";
    }
}

echo "<hr><h2>Updated Settings:</h2>";

// Check updated values
$check2 = $db->query("SELECT name, value FROM Wo_Config WHERE name IN ('emailValidation', 'sms_or_email', 'nodejs', 'user_registration', 'nodejs_ssl')");

if ($check2) {
    while ($row = $check2->fetch_assoc()) {
        echo $row['name'] . " = " . $row['value'] . "<br>";
    }
}

// Clear cache
$cache_dir = 'cache/';
$files = glob($cache_dir . '*');
foreach($files as $file) {
    if(is_file($file) && basename($file) !== '.htaccess') {
        unlink($file);
    }
}

echo "<hr><h3 style='color:green;'>✓ Cache cleared!</h3>";
echo "<h3 style='color:green;'>✓ Settings updated!</h3>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>Close this tab</li>";
echo "<li>Clear browser cache (Ctrl+Shift+Delete)</li>";
echo "<li>Go to: <a href='http://localhost/facesofnaija-web'>http://localhost/facesofnaija-web</a></li>";
echo "</ol>";

$db->close();
?>
