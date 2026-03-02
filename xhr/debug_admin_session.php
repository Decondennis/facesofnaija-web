<?php
if ($f == 'debug_admin_session') {
    require_once('../assets/init.php');
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
    $resp['is_admin'] = function_exists('Wo_IsAdmin') ? Wo_IsAdmin() : false;
    $resp['check_session'] = function_exists('Wo_CheckSession') ? (Wo_CheckSession($hash) ? true : false) : false;
    $resp['main_session_value'] = (!empty($_SESSION['main_session'])) ? $_SESSION['main_session'] : null;
    $resp['user_id'] = (!empty($wo['user']['user_id'])) ? $wo['user']['user_id'] : 0;
    $resp['ok'] = true;
    echo json_encode($resp, JSON_PRETTY_PRINT);
    exit();
}
