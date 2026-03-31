<?php
$conn = new mysqli("localhost", "root", "", "facesofnaija");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

// Show all tables
echo "=== Database Tables ===\n";
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_array()) {
    echo "- " . $row[0] . "\n";
}

// Check if wo_config exists
echo "\n=== Checking wo_config table ===\n";
$result = $conn->query("SHOW TABLES LIKE 'wo_config'");
if ($result->num_rows > 0) {
    echo "Table exists. Checking structure...\n\n";
    $result = $conn->query("DESCRIBE wo_config");
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    
    // Show some sample data
    echo "\n=== Sample Data ===\n";
    $result = $conn->query("SELECT * FROM wo_config LIMIT 5");
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "wo_config table does not exist\n";
}

$conn->close();
?>
