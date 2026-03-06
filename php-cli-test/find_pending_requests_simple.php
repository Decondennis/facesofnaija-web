<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$root = dirname(__DIR__);
$config = $root . '/config.php';
if (!is_file($config)) {
    header('Content-Type: application/json');
    echo json_encode(array('status' => 500, 'error' => 'config.php not found', 'path' => $config));
    exit();
}
require_once $config;

$host = isset($sql_db_host) ? $sql_db_host : 'localhost';
$user = isset($sql_db_user) ? $sql_db_user : 'root';
$pass = isset($sql_db_pass) ? $sql_db_pass : '';
$db   = isset($sql_db_name) ? $sql_db_name : '';

$mysqli = @new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    header('Content-Type: application/json');
    echo json_encode(array('status' => 500, 'error' => 'DB connect failed', 'errno' => $mysqli->connect_errno, 'error' => $mysqli->connect_error));
    exit();
}

$user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 330;
$user_id = (int)$user_id;

$members_table = 'Wo_Community_Members';
$communities_table = 'Wo_Communities';

$sql = "SELECT m.id AS member_row_id, m.community_id, m.time, c.community_name, c.community_title FROM `{$members_table}` m LEFT JOIN `{$communities_table}` c ON c.id = m.community_id WHERE m.user_id = {$user_id} AND m.active = '0'";
$res = $mysqli->query($sql);
$out = array();
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $out[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode(array('status' => 200, 'user_id' => $user_id, 'pending_requests' => $out));
