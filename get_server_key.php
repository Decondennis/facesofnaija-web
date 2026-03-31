<?php
require_once('config.php');

$conn = new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Try to get the server key
$sql = "SELECT conf_value FROM wo_config WHERE conf_name='widnows_app_api_key' LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $key = $row['conf_value'];
    echo "Current Server Key: " . $key . "\n";
} else {
    echo "No server key found. Setting a new one...\n";
    
    // Generate a new key
    $newKey = bin2hex(random_bytes(16));
    
    // Try to insert or update
    $sql = "INSERT INTO wo_config (conf_name, conf_value) VALUES ('widnows_app_api_key', '$newKey') 
            ON DUPLICATE KEY UPDATE conf_value='$newKey'";
    
    if ($conn->query($sql)) {
        echo "New Server Key Created: " . $newKey . "\n";
    } else {
        echo "Error creating key: " . $conn->error . "\n";
    }
}

$conn->close();
?>
