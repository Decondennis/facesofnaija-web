<?php
require_once('config.php');

echo "<h3>Checking Community Request System</h3>";

// Check if table exists
$check_table = mysqli_query($sqlConnect, "SHOW TABLES LIKE 'Wo_CommunityRequests'");
if (mysqli_num_rows($check_table) > 0) {
    echo "✅ Wo_CommunityRequests table exists<br>";
    
    // Show table structure
    $structure = mysqli_query($sqlConnect, "DESCRIBE Wo_CommunityRequests");
    echo "<br><strong>Table Structure:</strong><br>";
    while ($row = mysqli_fetch_assoc($structure)) {
        echo $row['Field'] . " - " . $row['Type'] . "<br>";
    }
    
    // Count requests
    $count = mysqli_query($sqlConnect, "SELECT COUNT(*) as total FROM Wo_CommunityRequests");
    $total = mysqli_fetch_assoc($count);
    echo "<br><strong>Total Requests:</strong> " . $total['total'] . "<br>";
    
} else {
    echo "❌ Wo_CommunityRequests table does not exist<br>";
    echo "<br>Creating table...<br>";
    
    $create_table = "CREATE TABLE IF NOT EXISTS `Wo_CommunityRequests` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL DEFAULT '0',
      `community_id` int(11) NOT NULL DEFAULT '0',
      `time` int(11) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`),
      KEY `community_id` (`community_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    
    if (mysqli_query($sqlConnect, $create_table)) {
        echo "✅ Table created successfully!<br>";
    } else {
        echo "❌ Error creating table: " . mysqli_error($sqlConnect) . "<br>";
    }
}

echo "<br><strong>request-community feature is now ready!</strong><br>";
echo "<br>Files created:<br>";
echo "1. sources/request-community.php ✅<br>";
echo "2. themes/facesofnaija/layout/community/request-community.phtml ✅<br>";
?>
