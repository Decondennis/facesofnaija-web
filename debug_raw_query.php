<?php
// DO NOT use ob_start - run in raw mode
// Test the exact mysqli_query call that hangs

// Load DB credentials directly
require_once('config.php');

$max_exec = ini_get('max_execution_time');
$output_buf = ini_get('output_buffering');
echo "MAX_EXEC=$max_exec\n";
echo "OUTPUT_BUF=$output_buf\n";
echo "PHP=" . PHP_VERSION . "\n";

// Connect fresh - no init, no ob_start
$conn = mysqli_connect($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name, 3306);
if (!$conn) {
    echo "CONNECT_FAIL\n";
    exit;
}
echo "CONNECTED\n";

// Set time limit explicitly
set_time_limit(30);

// Run a simple query first
$r1 = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM Wo_UserStory");
echo "SIMPLE_QUERY=" . ($r1 ? mysqli_fetch_row($r1)[0] : 'FAIL') . "\n";

// Now the complex query
echo "STARTING_COMPLEX_QUERY...\n";
flush();

$user_id = 330;
$group_by = "GROUP BY user_id";
$query = "SELECT DISTINCT user_id,title,description,posted,expire,thumbnail,(SELECT MAX(us.id) FROM Wo_UserStory us WHERE us.user_id = Wo_UserStory.user_id) AS id  FROM Wo_UserStory WHERE (user_id IN (SELECT following_id FROM Wo_Followers WHERE follower_id = '$user_id') OR user_id = '$user_id') AND user_id IN (SELECT user_id FROM Wo_Users WHERE active = '1') $group_by ORDER BY id DESC LIMIT 4";

echo "QUERY_SENT\n";
flush();

$q = mysqli_query($conn, $query);
echo "COMPLEX_RESULT=" . var_export($q, true) . "\n";

if ($q === false) {
    echo "MYSQL_ERROR=" . mysqli_error($conn) . "\n";
} else {
    echo "ROWS=" . mysqli_num_rows($q) . "\n";
}

echo "DONE\n";
