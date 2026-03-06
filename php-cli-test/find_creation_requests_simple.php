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

$tables = array('Wo_Community_Request','Wo_Community_Requests');
$out = array();
foreach ($tables as $table) {
    $q = "SELECT * FROM `{$table}` WHERE `user_id` = {$user_id} AND `status` = 'pending'";
    $res = @$mysqli->query($q);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $row['table'] = $table;
            $out[] = $row;
        }
    }
}

header('Content-Type: application/json');
echo json_encode(array('status' => 200, 'user_id' => $user_id, 'creation_requests' => $out));
