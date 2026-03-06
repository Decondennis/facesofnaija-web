<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
$mysqli = @new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);
if ($mysqli->connect_errno) {
    header('Content-Type: application/json');
    echo json_encode(['status'=>500,'error'=>'DB connect failed','errno'=>$mysqli->connect_errno,'err'=>$mysqli->connect_error]);
    exit();
}
$sql = "SELECT m.id AS member_row_id, m.user_id, m.community_id, m.time, c.community_name, c.community_title FROM Wo_Community_Members m LEFT JOIN Wo_Communities c ON c.id = m.community_id WHERE m.active = '0' ORDER BY m.community_id, m.id";
$res = $mysqli->query($sql);
$out = [];
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $out[] = $r;
    }
}
header('Content-Type: application/json');
echo json_encode(['status'=>200,'count'=>count($out),'requests'=>$out]);
