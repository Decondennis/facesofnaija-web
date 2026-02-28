<?php
// Disable Socket.io completely
require_once('config.php');

$db = new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

echo "<h1>Disabling Socket.io (Real-time Chat)</h1>";

$updates = [
    "UPDATE Wo_Config SET value = '0' WHERE name = 'nodejs'",
    "UPDATE Wo_Config SET value = '0' WHERE name = 'nodejs_ssl'",
    "UPDATE Wo_Config SET value = '0' WHERE name = 'chat_system'"
];

foreach ($updates as $sql) {
    if ($db->query($sql)) {
        echo "<p style='color:green;'>✓ " . $sql . "</p>";
    } else {
        echo "<p style='color:red;'>✗ Error: " . $db->error . "</p>";
    }
}

// Clear cache
$cache_dir = 'cache/';
$count = 0;
$files = glob($cache_dir . '*');
if ($files) {
    foreach($files as $file) {
        if(is_file($file) && basename($file) !== '.htaccess') {
            unlink($file);
            $count++;
        }
    }
}

echo "<p style='color:green;'>✓ Cleared $count cache files</p>";

$db->close();

echo "<hr>";
echo "<h2 style='color:green;'>✓ Socket.io Disabled!</h2>";
echo "<p>Refresh your page and the connection errors should stop.</p>";
?>
