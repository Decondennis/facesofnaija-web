<?php
require_once('assets/init.php');
header('Content-Type: application/json; charset=utf-8');
$resp = array('ok' => false);
$hash = '';
if (!empty($_GET['hash'])) {
  $hash = $_GET['hash'];
} elseif (!empty($_GET['hash_id'])) {
  $hash = $_GET['hash_id'];
} elseif (!empty($_POST['hash'])) {
  $hash = $_POST['hash'];
}
$resp['received_hash'] = $hash;
$resp['is_admin'] = Wo_IsAdmin();
$resp['check_session'] = Wo_CheckSession($hash) ? true : false;
$resp['main_session_value'] = (!empty($_SESSION['main_session'])) ? $_SESSION['main_session'] : null;
$resp['user_id'] = (!empty($wo['user']['user_id'])) ? $wo['user']['user_id'] : 0;
$resp['ok'] = true;
echo json_encode($resp, JSON_PRETTY_PRINT);
exit();
