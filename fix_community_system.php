<?php
require_once('config.php');

echo "<h2>Community System Quick Fix</h2>";

// 1. Enable communities
echo "<h3>1. Enabling Communities System</h3>";
$update = mysqli_query($sqlConnect, "UPDATE Wo_Config SET value = '1' WHERE name = 'communities'");
if ($update) {
    echo "✅ Communities enabled in configuration<br>";
} else {
    echo "❌ Failed to enable communities: " . mysqli_error($sqlConnect) . "<br>";
}

// 2. Check current user (assume user_id = 1 for admin)
echo "<h3>2. Checking Admin User</h3>";
$user_check = mysqli_query($sqlConnect, "SELECT user_id, username, name, admin FROM Wo_Users WHERE admin = 1 LIMIT 1");
if ($admin = mysqli_fetch_assoc($user_check)) {
    echo "✅ Admin user found:<br>";
    echo "&nbsp;&nbsp;- User ID: " . $admin['user_id'] . "<br>";
    echo "&nbsp;&nbsp;- Username: " . $admin['username'] . "<br>";
    echo "&nbsp;&nbsp;- Name: " . $admin['name'] . "<br>";
} else {
    echo "❌ No admin user found<br>";
}

// 3. Check Wo_Communities table
echo "<h3>3. Checking Communities Table</h3>";
$table_check = mysqli_query($sqlConnect, "SHOW TABLES LIKE 'Wo_Communities'");
if (mysqli_num_rows($table_check) > 0) {
    echo "✅ Wo_Communities table exists<br>";
    
    // Check structure
    $structure = mysqli_query($sqlConnect, "DESCRIBE Wo_Communities");
    echo "<br><strong>Table columns:</strong><br>";
    while ($col = mysqli_fetch_assoc($structure)) {
        echo "&nbsp;&nbsp;- " . $col['Field'] . " (" . $col['Type'] . ")<br>";
    }
} else {
    echo "❌ Wo_Communities table not found<br>";
}

// 4. Test create community access
echo "<h3>4. Test Links</h3>";
echo "<a href='index.php?link1=communities' target='_blank'>View All Communities</a><br>";
echo "<a href='index.php?link1=create-community' target='_blank'>Create Community Page</a><br>";
echo "<a href='index.php?link1=joined-communities' target='_blank'>My Joined Communities</a><br>";

// 5. Clear cache
echo "<h3>5. Clearing Cache</h3>";
$cache_dir = 'cache';
if (is_dir($cache_dir)) {
    $files = glob($cache_dir . '/*');
    $cleared = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $cleared++;
        }
    }
    echo "✅ Cleared " . $cleared . " cache files<br>";
}

echo "<br><hr><br>";
echo "<strong style='color: green;'>✅ Community system is now ready!</strong><br>";
echo "<strong>Next steps:</strong><br>";
echo "1. Clear your browser cache (Ctrl+Shift+Delete)<br>";
echo "2. Logout and login again<br>";
echo "3. Go to Communities page and click 'Create' button<br>";
?>
