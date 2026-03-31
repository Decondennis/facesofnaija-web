<?php
$conn = new mysqli("localhost", "root", "", "facesofnaija");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "Connected to database!\n\n";

// Try to get the server key
$sql = "SELECT value FROM wo_config WHERE name='widnows_app_api_key' LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $key = $row['value'];
    echo "✅ Current Server Key: " . $key . "\n";
} else {
    echo "No server key found. Creating a new one...\n\n";
    
    // Generate a simple key for development
    $newKey = "facesofnaija_dev_2024_key";
    
    // Try to insert
    $sql = "INSERT INTO wo_config (name, value) VALUES ('widnows_app_api_key', '$newKey')";
    
    if ($conn->query($sql)) {
        echo "✅ New Server Key Created: " . $newKey . "\n\n";
        echo "Update your Android app with this key!\n";
    } else {
        // Maybe it exists, try update
        $sql = "UPDATE wo_config SET value='$newKey' WHERE name='widnows_app_api_key'";
        if ($conn->query($sql)) {
            echo "✅ Server Key Updated: " . $newKey . "\n";
        } else {
            echo "Error: " . $conn->error . "\n";
        }
    }
}

// Verify it's set
echo "\n=== Verification ===\n";
$result = $conn->query("SELECT value FROM wo_config WHERE name='widnows_app_api_key'");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "Confirmed Server Key: " . $row['value'] . "\n";
}

$conn->close();
?>
