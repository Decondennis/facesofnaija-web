<?php
require_once('assets/init.php');

$user_id = 330;
$username = "decon";

echo "Making '$username' (ID: $user_id) an admin...\n";

// Check current status
$check = "SELECT user_id, username, user_type FROM wo_users WHERE user_id=$user_id";
$result = mysqli_query($GLOBALS['sqlConnect'], $check);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo "Current status: {$row['username']} - Type: {$row['user_type']}\n";
    
    // Update to admin
    $update = "UPDATE wo_users SET user_type='admin' WHERE user_id=$user_id";
    if (mysqli_query($GLOBALS['sqlConnect'], $update)) {
        echo "✓ Successfully updated to admin!\n";
        
        // Verify
        $verify = mysqli_query($GLOBALS['sqlConnect'], $check);
        if ($verify) {
            $updated = mysqli_fetch_assoc($verify);
            echo "✓ Verified: {$updated['username']} is now {$updated['user_type']}\n";
        }
    } else {
        echo "✗ Error updating: " . mysqli_error($GLOBALS['sqlConnect']) . "\n";
    }
} else {
    echo "✗ User not found\n";
}
?>
