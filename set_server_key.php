<?php
// Try to connect with root user
$conn = new mysqli("localhost", "root", "", "facesofnaija");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "Connected successfully!\n\n";

// Try to get the server key
$sql = "SELECT conf_value FROM wo_config WHERE conf_name='widnows_app_api_key' LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $key = $row['conf_value'];
    echo "Current Server Key: " . $key . "\n";
} else {
    echo "No server key found. Creating a new one...\n\n";
    
    // Generate a simple key for development
    $newKey = "facesofnaija_2024_dev_key";
    
    // Try to insert or update
    $sql = "INSERT INTO wo_config (conf_name, conf_value) VALUES ('widnows_app_api_key', '$newKey') 
            ON DUPLICATE KEY UPDATE conf_value='$newKey'";
    
    if ($conn->query($sql)) {
        echo "✅ New Server Key Created: " . $newKey . "\n";
    } else {
        echo "Error: " . $conn->error . "\n";
    }
}

$conn->close();
?>
