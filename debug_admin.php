<?php
require_once('assets/init.php');

$user_id = 330;

echo "Test 1: Direct query with user_id=330\n";
$sql = "SELECT user_id, username FROM wo_users WHERE user_id=330";
echo "SQL: $sql\n";
$result = @mysqli_query($GLOBALS['sqlConnect'], $sql);
if ($result) {
    $rows = @mysqli_num_rows($result);
    echo "Rows: $rows\n";
    if ($rows > 0) {
        while ($row = @mysqli_fetch_assoc($result)) {
            echo "Found: " . json_encode($row) . "\n";
        }
    }
} else {
    echo "Error: " . mysqli_error($GLOBALS['sqlConnect']) . "\n";
}

echo "\nTest 2: Try direct update\n";
$update_sql = "UPDATE wo_users SET user_type='admin' WHERE user_id=330 LIMIT 1";
echo "SQL: $update_sql\n";
if (@mysqli_query($GLOBALS['sqlConnect'], $update_sql)) {
    echo "✓ Update successful, affected rows: " . mysqli_affected_rows($GLOBALS['sqlConnect']) . "\n";
} else {
    echo "Error: " . mysqli_error($GLOBALS['sqlConnect']) . "\n";
}
?>
