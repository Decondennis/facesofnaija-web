<?php
require_once('assets/init.php');

echo "Database connection test...\n";
if ($GLOBALS['sqlConnect']) {
    echo "✓ Database connected\n";
    
    // Test simple count
    echo "Counting users...\n";
    $count_query = "SELECT COUNT(*) as cnt FROM wo_users";
    $result = mysqli_query($GLOBALS['sqlConnect'], $count_query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "Total users: " . $row['cnt'] . "\n";
    } else {
        echo "Error: " . mysqli_error($GLOBALS['sqlConnect']) . "\n";
    }
} else {
    echo "✗ Database connection failed\n";
}
?>
