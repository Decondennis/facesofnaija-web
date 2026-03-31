<?php
// Temporary script to get API key
require_once('config.local.php');

// Connect to database
$conn = new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get API key
$sql = "SELECT conf_value FROM wo_config WHERE conf_name='widnows_app_api_key' LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "API Key: " . $row['conf_value'];
} else {
    echo "API Key not found in database. Using default or checking alternate keys...";
    
    // Try alternate spellings
    $sql2 = "SELECT conf_name, conf_value FROM wo_config WHERE conf_name LIKE '%api%key%' OR conf_name LIKE '%server%key%'";
    $result2 = $conn->query($sql2);
    
    if ($result2 && $result2->num_rows > 0) {
        echo "\n\nFound these API-related keys:\n";
        while($row = $result2->fetch_assoc()) {
            echo $row['conf_name'] . " = " . $row['conf_value'] . "\n";
        }
    }
}

$conn->close();
?>
