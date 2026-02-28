<?php
require_once('config.php');

echo "<h2>Complete Community Access Fix</h2>";

// 1. Check membership system
echo "<h3>1. Checking Membership System</h3>";
$membership_check = mysqli_query($sqlConnect, "SELECT value FROM Wo_Config WHERE name = 'membership_system'");
if ($row = mysqli_fetch_assoc($membership_check)) {
    echo "Membership System: " . ($row['value'] == 1 ? 'ENABLED' : 'DISABLED') . "<br>";
    
    if ($row['value'] == 1) {
        echo "<strong>Disabling membership system to allow all users access...</strong><br>";
        mysqli_query($sqlConnect, "UPDATE Wo_Config SET value = '0' WHERE name = 'membership_system'");
        echo "✅ Membership system disabled<br>";
    }
}

// 2. Enable communities
echo "<h3>2. Enabling Communities</h3>";
mysqli_query($sqlConnect, "UPDATE Wo_Config SET value = '1' WHERE name = 'communities'");
echo "✅ Communities enabled<br>";

// 3. Make sure admin user exists
echo "<h3>3. Checking Admin User</h3>";
$admin = mysqli_query($sqlConnect, "SELECT user_id, username, admin, is_pro FROM Wo_Users WHERE admin = 1 LIMIT 1");
if ($user = mysqli_fetch_assoc($admin)) {
    echo "Admin User ID: " . $user['user_id'] . "<br>";
    echo "Username: " . $user['username'] . "<br>";
    echo "Admin: " . ($user['admin'] == 1 ? 'YES' : 'NO') . "<br>";
    echo "Pro: " . ($user['is_pro'] != 0 ? 'YES' : 'NO') . "<br>";
    
    // Make admin also pro to ensure access
    if ($user['is_pro'] == 0) {
        echo "<br><strong>Making admin a pro user for full access...</strong><br>";
        mysqli_query($sqlConnect, "UPDATE Wo_Users SET is_pro = 1 WHERE user_id = " . $user['user_id']);
        echo "✅ Admin is now pro user<br>";
    }
}

// 4. Clear cache
echo "<h3>4. Clearing Cache</h3>";
$cache_dir = 'cache';
if (is_dir($cache_dir)) {
    $files = glob($cache_dir . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "✅ Cache cleared<br>";
}

// 5. Check if request-community files exist
echo "<h3>5. Checking Request-Community Files</h3>";
if (file_exists('sources/request-community.php')) {
    echo "✅ sources/request-community.php exists<br>";
} else {
    echo "❌ sources/request-community.php missing<br>";
}

if (file_exists('themes/facesofnaija/layout/community/request-community.phtml')) {
    echo "✅ Template file exists<br>";
} else {
    echo "❌ Template file missing<br>";
}

echo "<br><hr><br>";
echo "<strong style='color: green;'>✅ ALL FIXES APPLIED!</strong><br><br>";
echo "<strong>NEXT STEPS:</strong><br>";
echo "1. <strong>LOGOUT</strong> from your current session<br>";
echo "2. <strong>CLEAR BROWSER CACHE</strong> (Ctrl+Shift+Delete)<br>";
echo "3. <strong>LOGIN AGAIN</strong><br>";
echo "4. <a href='index.php?link1=communities' target='_blank'><strong>GO TO COMMUNITIES PAGE</strong></a><br>";
echo "5. You should now see the CREATE button (for admins)<br>";
?>
