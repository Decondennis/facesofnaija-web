<?php
require_once('config.php');

echo "<h3>Community System Diagnostic</h3>";

// Check communities configuration
$check_config = mysqli_query($sqlConnect, "SELECT * FROM Wo_Config WHERE name = 'communities'");
if ($row = mysqli_fetch_assoc($check_config)) {
    echo "<strong>Communities Setting:</strong> " . ($row['value'] == 1 ? '✅ Enabled' : '❌ Disabled') . "<br>";
    
    if ($row['value'] == 0) {
        echo "<br><strong>Enabling communities...</strong><br>";
        mysqli_query($sqlConnect, "UPDATE Wo_Config SET value = '1' WHERE name = 'communities'");
        echo "✅ Communities enabled!<br>";
    }
} else {
    echo "❌ Communities configuration not found in database<br>";
}

// Check if user can create community
$user_id = $_GET['user_id'] ?? 1;
$check_user = mysqli_query($sqlConnect, "SELECT * FROM Wo_Users WHERE user_id = {$user_id}");
if ($user = mysqli_fetch_assoc($check_user)) {
    echo "<br><strong>User Info:</strong><br>";
    echo "Name: " . $user['name'] . "<br>";
    echo "Admin: " . ($user['admin'] == 1 ? '✅ Yes' : '❌ No') . "<br>";
    echo "Active: " . ($user['active'] == 1 ? '✅ Yes' : '❌ No') . "<br>";
}

// Check if Wo_Communities table exists
$check_table = mysqli_query($sqlConnect, "SHOW TABLES LIKE 'Wo_Communities'");
if (mysqli_num_rows($check_table) > 0) {
    echo "<br>✅ Wo_Communities table exists<br>";
    
    $count = mysqli_query($sqlConnect, "SELECT COUNT(*) as total FROM Wo_Communities");
    $total = mysqli_fetch_assoc($count);
    echo "Total Communities: " . $total['total'] . "<br>";
} else {
    echo "<br>❌ Wo_Communities table does not exist<br>";
}

echo "<br><strong>Create Community URL:</strong><br>";
echo "<a href='" . $wo['config']['site_url'] . "/index.php?link1=create-community' target='_blank'>Click here to create community</a>";
?>
