<?php
require_once(dirname(__DIR__) . '/config.php');

$host = $sql_db_host;
$user = $sql_db_user;
$pass = $sql_db_pass;
$db   = $sql_db_name;

$community_id = isset($_GET['community_id']) ? (int)$_GET['community_id'] : 1;
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 330;

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    echo "DB Connect Error: " . $mysqli->connect_error;
    exit;
}

// ensure table exists
$tbl = 'Wo_Community_Members';
$res = $mysqli->query("SHOW TABLES LIKE '{$tbl}'");
if (!$res || $res->num_rows == 0) {
    echo "Table {$tbl} not found\n";
    exit;
}

// check existing
$stmt = $mysqli->prepare("SELECT id FROM {$tbl} WHERE community_id = ? AND user_id = ? LIMIT 1");
$stmt->bind_param('ii', $community_id, $user_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo "Membership already exists for user_id={$user_id} community_id={$community_id}\n";
    exit;
}
$stmt->close();

$time = time();
$active = 1;
$ins = $mysqli->prepare("INSERT INTO {$tbl} (community_id, user_id, time, active) VALUES (?, ?, ?, ?)");
$ins->bind_param('iiii', $community_id, $user_id, $time, $active);
if ($ins->execute()) {
    echo "Inserted membership: id=" . $mysqli->insert_id . " community_id={$community_id} user_id={$user_id}\n";
} else {
    echo "Insert failed: " . $mysqli->error . "\n";
}
$ins->close();
$mysqli->close();
