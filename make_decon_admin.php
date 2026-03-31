<?php
require_once('assets/init.php');

// Find the decon user
$query = "SELECT id, user_id, username FROM wo_users WHERE username='decon' LIMIT 1";
$result = mysqli_query($GLOBALS['sqlConnect'], $query);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['user_id'];
    echo "Found user: decon (user_id: $user_id)\n";
    
    // Update the user to be an admin
    $update = "UPDATE wo_users SET user_type='admin' WHERE user_id=$user_id";
    if (mysqli_query($GLOBALS['sqlConnect'], $update)) {
        echo "✓ Successfully made decon an admin!\n";
    } else {
        echo "✗ Error: " . mysqli_error($GLOBALS['sqlConnect']) . "\n";
    }
} else {
    echo "decon user not found\n";
}
?>
