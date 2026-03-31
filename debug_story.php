<?php
require_once('assets/init.php');
global $wo, $db, $sqlConnect;

echo "PHP_VERSION=" . PHP_VERSION . "\n";

// Check tables exist
$tables = array('Wo_UserStory', 'Wo_Followers', 'Wo_Users', 'Wo_UserStoryMedia');
foreach ($tables as $table) {
    $r = mysqli_query($sqlConnect, "SHOW TABLES LIKE '$table'");
    $exists = ($r && mysqli_num_rows($r) > 0) ? 'YES' : 'NO';
    echo "TABLE_$table=$exists\n";
}

// Try the exact query from Wo_GetFriendsStatus manually
$user_id = $wo['user']['user_id'];
echo "USER_ID=$user_id\n";
echo "LOGGEDIN=" . ($wo['loggedin'] ? 'yes' : 'no') . "\n";

$group_by = "GROUP BY user_id";
$query = "SELECT DISTINCT user_id,title,description,posted,expire,thumbnail,(SELECT MAX(us.id) FROM Wo_UserStory us WHERE us.user_id = Wo_UserStory.user_id) AS id  FROM Wo_UserStory WHERE (user_id IN (SELECT following_id FROM Wo_Followers WHERE follower_id = '$user_id') OR user_id = '$user_id') AND user_id IN (SELECT user_id FROM Wo_Users WHERE active = '1') $group_by ORDER BY id DESC LIMIT 4";

echo "RUNNING_QUERY...\n";
$query_run = mysqli_query($sqlConnect, $query);
echo "QUERY_RESULT=" . var_export($query_run, true) . "\n";
if ($query_run === false) {
    echo "MYSQL_ERROR=" . mysqli_error($sqlConnect) . "\n";
} else {
    echo "QUERY_ROWS=" . mysqli_num_rows($query_run) . "\n";
    while ($row = mysqli_fetch_assoc($query_run)) {
        echo "ROW: " . json_encode($row) . "\n";
    }
}

// Now test the actual function
echo "CALLING_Wo_GetFriendsStatus...\n";
$result = Wo_GetFriendsStatus(array('limit' => 4));
echo "RESULT_TYPE=" . gettype($result) . "\n";
echo "RESULT_COUNT=" . (is_array($result) ? count($result) : 'N/A') . "\n";
echo "DONE\n";
