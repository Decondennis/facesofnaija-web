<?php
require_once('assets/init.php');
header('Content-Type: application/json; charset=utf-8');
$resp = array('ok' => false);
if ($wo['loggedin'] == false) {
  $resp['error'] = 'not_logged_in';
  echo json_encode($resp);
  exit();
}
if ($wo['config']['communities'] == 0) {
  $resp['error'] = 'communities_disabled';
  echo json_encode($resp);
  exit();
}
$user_id = (!empty($wo['user']['user_id'])) ? $wo['user']['user_id'] : 0;
$pages = array();
if ($user_id > 0) {
  $pages = Wo_GetMyCommunities();
}
$raw_rows = 0;
$raw_err = '';
global $sqlConnect;
if ($user_id > 0) {
  $raw_q = "SELECT `id` FROM " . T_COMMUNITIES . " WHERE `id` IN (SELECT `community_id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `user_id` = {$user_id})";
  $res = @mysqli_query($sqlConnect, $raw_q);
  if ($res === false) {
    $raw_err = mysqli_error($sqlConnect);
  } else {
    $raw_rows = mysqli_num_rows($res);
  }
}
$resp['ok'] = true;
$resp['user_id'] = $user_id;
$resp['pages_count'] = count($pages);
$resp['raw_rows'] = $raw_rows;
$resp['raw_err'] = $raw_err;
echo json_encode($resp, JSON_PRETTY_PRINT);
exit();
