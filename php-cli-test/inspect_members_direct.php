<?php
require_once(dirname(__DIR__) . '/config.php');

$host = isset($sql_db_host) ? $sql_db_host : 'localhost';
$user = isset($sql_db_user) ? $sql_db_user : 'root';
$pass = isset($sql_db_pass) ? $sql_db_pass : '';
$db   = isset($sql_db_name) ? $sql_db_name : '';

$user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 330;

$mysqli = @new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    echo "DB Connect Error: " . $mysqli->connect_error;
    exit;
}

$tables = array('Wo_Community_Members', 'Wo_CommunityMembers');

echo "<pre>Direct inspect for user_id={$user_id}\n\n";
foreach ($tables as $tbl) {
    $q = "SELECT * FROM `{$tbl}` WHERE `user_id` = {$user_id}";
    $res = @$mysqli->query($q);
    if ($res === false) {
        echo "Table {$tbl}: ERROR - " . $mysqli->error . "\n\n";
        continue;
    }
    $count = $res->num_rows;
    echo "Table {$tbl}: found {$count} rows\n";
    if ($count > 0) {
        while ($r = $res->fetch_assoc()) {
            print_r($r);
        }
        echo "\n";
    }
}

// Also try joined lookup via Wo_Communities
$q2 = "SELECT c.id,c.community_title,cm.* FROM `Wo_Communities` c INNER JOIN `Wo_Community_Members` cm ON c.id = cm.community_id WHERE cm.user_id = {$user_id}";
$r2 = @$mysqli->query($q2);
if ($r2 === false) {
    echo "Join lookup error: " . $mysqli->error . "\n";
} else {
    echo "Joined via Wo_Community_Members: " . $r2->num_rows . " rows\n";
    while ($row = $r2->fetch_assoc()) print_r($row);
}

echo "</pre>";
