<?php
require_once('assets/init.php');

echo "Searching for users with 'decon' in name...\n";
$query = "SELECT user_id, username, user_type FROM wo_users WHERE LOWER(username) LIKE '%decon%' LIMIT 10";
$result = mysqli_query($GLOBALS['sqlConnect'], $query);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "  ID: {$row['user_id']}, Username: {$row['username']}, Type: {$row['user_type']}\n";
    }
} else {
    echo "  No users found with 'decon' in username\n";
}

echo "\nAll current admin users:\n";
$admin_query = "SELECT user_id, username FROM wo_users WHERE user_type='admin' LIMIT 10";
$admin_result = mysqli_query($GLOBALS['sqlConnect'], $admin_query);
if ($admin_result && mysqli_num_rows($admin_result) > 0) {
    while ($row = mysqli_fetch_assoc($admin_result)) {
        echo "  ID: {$row['user_id']}, Username: {$row['username']}\n";
    }
} else {
    echo "  No admin users found\n";
}

echo "\nFirst 10 users in database:\n";
$first_query = "SELECT user_id, username, user_type FROM wo_users LIMIT 10";
$first_result = mysqli_query($GLOBALS['sqlConnect'], $first_query);
if ($first_result && mysqli_num_rows($first_result) > 0) {
    while ($row = mysqli_fetch_assoc($first_result)) {
        echo "  ID: {$row['user_id']}, Username: {$row['username']}, Type: {$row['user_type']}\n";
    }
}
?>
