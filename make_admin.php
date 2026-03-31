<?php
require_once('assets/init.php');

$user_id = 330;
$username = "decon";

echo "Making '$username' (ID: $user_id) an admin...\n";

// Check current status
$check = "SELECT admin FROM wo_users WHERE user_id=$user_id";
$result = mysqli_query($GLOBALS['sqlConnect'], $check);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo "Current admin status: {$row['admin']}\n";
    
    // Update admin column to 1
    $update = "UPDATE wo_users SET admin='1' WHERE user_id=$user_id";
    if (mysqli_query($GLOBALS['sqlConnect'], $update)) {
        $affected = mysqli_affected_rows($GLOBALS['sqlConnect']);
        echo "✓ Successfully updated! Affected rows: $affected\n";
        
        // Verify
        $verify = mysqli_query($GLOBALS['sqlConnect'], $check);
        if ($verify) {
            $updated = mysqli_fetch_assoc($verify);
            echo "✓ Verified: decon is now admin={$updated['admin']}\n";
        }
    } else {
        echo "✗ Error updateing: " . mysqli_error($GLOBALS['sqlConnect']) . "\n";
    }
} else {
    echo "✗ User not found\n";
}
?>
