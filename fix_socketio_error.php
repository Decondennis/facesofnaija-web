<?php
require_once('config.php');

// Check and disable Socket.io
$check_nodejs = mysqli_query($sqlConnect, "SELECT * FROM Wo_Config WHERE name IN ('nodejs', 'nodejs_ssl')");

echo "<h3>Current Socket.io Configuration:</h3>";
while ($row = mysqli_fetch_assoc($check_nodejs)) {
    echo $row['name'] . " = " . $row['value'] . "<br>";
}

// Force disable Socket.io
mysqli_query($sqlConnect, "UPDATE Wo_Config SET value = '0' WHERE name = 'nodejs'");
mysqli_query($sqlConnect, "UPDATE Wo_Config SET value = '0' WHERE name = 'nodejs_ssl'");

echo "<br><h3>After Update:</h3>";
$check_nodejs2 = mysqli_query($sqlConnect, "SELECT * FROM Wo_Config WHERE name IN ('nodejs', 'nodejs_ssl')");
while ($row = mysqli_fetch_assoc($check_nodejs2)) {
    echo $row['name'] . " = " . $row['value'] . "<br>";
}

// Clear cache directory
$cache_dir = 'cache';
if (is_dir($cache_dir)) {
    $files = glob($cache_dir . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "<br>Cache cleared!<br>";
}

echo "<br><strong>Socket.io has been disabled. Now clear your browser cache and refresh.</strong>";
?>
