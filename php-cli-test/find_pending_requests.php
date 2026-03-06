<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$root = dirname(__DIR__);
$init = $root . '/assets/init.php';
if (!is_file($init)) {
    header('Content-Type: application/json');
    echo json_encode(array('status' => 500, 'error' => 'init.php not found', 'path' => $init));
    exit();
}
require_once $init;

if (!isset($_GET['user_id'])) {
    $user_id = 330;
} else {
    $user_id = (int) $_GET['user_id'];
}

global $sqlConnect;
$user_id = Wo_Secure($user_id);
$results = array();
$sql = "SELECT `id`,`community_id`,`time` FROM " . T_COMMUNITY_MEMBERS . " WHERE `user_id` = {$user_id} AND `active` = '0'";
$res = @mysqli_query($sqlConnect, $sql);
if ($res && mysqli_num_rows($res) > 0) {
    while ($r = mysqli_fetch_assoc($res)) {
        $comm = Wo_CommunityData($r['community_id']);
        $results[] = array(
            'member_row_id' => $r['id'],
            'community_id' => $r['community_id'],
            'community_name' => (!empty($comm['community_name']) ? $comm['community_name'] : null),
            'community_title' => (!empty($comm['community_title']) ? $comm['community_title'] : null),
            'time' => $r['time']
        );
    }
}

header('Content-Type: application/json');
echo json_encode(array('status' => 200, 'user_id' => (int)$user_id, 'pending_requests' => $results));
