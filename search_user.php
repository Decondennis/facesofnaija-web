<?php
require_once('assets/init.php');

// Search with case-insensitive search
$username = "decon";
echo "Searching for '$username' (case-insensitive)...\n";
$query = "SELECT user_id, username FROM wo_users WHERE username LIKE '$username' OR username LIKE '%" . ucfirst($username) . "%'";
$result = @mysqli_query($GLOBALS['sqlConnect'], $query);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "Found: ID=" . $row['user_id'] . ", Username=" . $row['username'] . "\n";
    }
} else {
    echo "Not found. Showing users starting with 'd':\n";
    $query2 = "SELECT user_id, username FROM wo_users WHERE username LIKE 'd%' LIMIT 10";
    $result2 = @mysqli_query($GLOBALS['sqlConnect'], $query2);
    if ($result2) {
        while ($row = @mysqli_fetch_assoc($result2)) {
            echo "  ID=" . $row['user_id'] . ", Username=" . $row['username'] . "\n";
        }
    }
}
?>
