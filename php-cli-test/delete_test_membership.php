<?php
require_once dirname(__DIR__) . '/config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$community_id = isset($_GET['community_id']) ? intval($_GET['community_id']) : 0;
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

$mysqli = new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo "DB connect error: " . $mysqli->connect_error;
    exit;
}

if ($id > 0) {
    $stmt = $mysqli->prepare("DELETE FROM `Wo_Community_Members` WHERE `id` = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();
    echo "Deleted by id={$id}, affected={$affected}";
    exit;
}

if ($community_id > 0 && $user_id > 0) {
    $stmt = $mysqli->prepare("DELETE FROM `Wo_Community_Members` WHERE `community_id` = ? AND `user_id` = ?");
    $stmt->bind_param('ii', $community_id, $user_id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();
    echo "Deleted community_id={$community_id} user_id={$user_id}, affected={$affected}";
    exit;
}

echo "No id or (community_id+user_id) provided.";
