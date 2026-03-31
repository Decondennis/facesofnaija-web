<?php
require_once('assets/init.php');

echo "Checking wo_users table structure...\n";
$sql = "DESCRIBE wo_users";
$result = @mysqli_query($GLOBALS['sqlConnect'], $sql);
if ($result) {
    echo "Table columns:\n";
    while ($row = @mysqli_fetch_assoc($result)) {
        echo "  - {$row['Field']} ({$row['Type']})\n";
    }
} else {
    echo "Error: " . mysqli_error($GLOBALS['sqlConnect']) . "\n";
}

echo "\nSearching for admin-related columns...\n";
$sql2 = "SHOW COLUMNS FROM wo_users LIKE '%admin%' OR LIKE '%type%'";
$result2 = @mysqli_query($GLOBALS['sqlConnect'], $sql2);
if ($result2) {
    while ($row = @mysqli_fetch_assoc($result2)) {
        echo "  Found: {$row['Field']}\n";
    }
}

echo "\nDecon user data:\n";
$sql3 = "SELECT * FROM wo_users WHERE username='decon' LIMIT 1";
$result3 = @mysqli_query($GLOBALS['sqlConnect'], $sql3);
if ($result3 && mysqli_num_rows($result3) > 0) {
    $row = @mysqli_fetch_assoc($result3);
    foreach ($row as $k => $v) {
        echo "  $k = " . (strlen((string)$v) > 80 ? substr((string)$v, 0, 80) . "..." : $v) . "\n";
    }
}
?>
