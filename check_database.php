<?php
$conn = new mysqli("localhost", "root", "", "facesofnaija");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "=== Database Connection: SUCCESS ===\n\n";

// Check users
$result = $conn->query("SELECT COUNT(*) as count FROM wo_users");
if ($result) {
    $row = $result->fetch_assoc();
    echo "Total Users: " . $row['count'] . "\n";
    
    if ($row['count'] > 0) {
        echo "\n=== Sample Users (First 5) ===\n";
        $users = $conn->query("SELECT user_id, username, email, active FROM wo_users LIMIT 5");
        while ($user = $users->fetch_assoc()) {
            $status = $user['active'] == '1' ? '✅ Active' : '❌ Inactive';
            echo "- {$user['username']} ({$user['email']}) - {$status}\n";
        }
    } else {
        echo "❌ No users found. You need to register!\n";
    }
} else {
    echo "Error querying users: " . $conn->error . "\n";
}

// Check server key
echo "\n=== API Configuration ===\n";
$result = $conn->query("SELECT value FROM wo_config WHERE name='widnows_app_api_key'");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "Server Key: " . $row['value'] . "\n";
} else {
    echo "❌ Server key not configured\n";
}

$conn->close();
?>
