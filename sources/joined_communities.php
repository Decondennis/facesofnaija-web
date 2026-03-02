<?php
if ($wo['loggedin'] == false) {
  header("Location: " . Wo_SeoLink('index.php?link1=welcome'));
  exit();
}
if ($wo['config']['communities'] == 0) {
    header("Location: " . Wo_SeoLink('index.php?link1=welcome'));
    exit();
}
$wo['description'] = $wo['config']['siteDesc'];
$wo['keywords']    = $wo['config']['siteKeywords'];
$wo['page']        = 'joined_communities';
$wo['title']       = $wo['lang']['joined_communities'];
// collect diagnostics for logged-in users (also shown for admins or ?debug_joined)
global $sqlConnect;
$user_id = (!empty($wo['user']['user_id'])) ? $wo['user']['user_id'] : 0;
$show_diag = false;
if (!empty($wo['loggedin'])) {
  $show_diag = true;
}
if ((isset($_REQUEST['debug_joined']) && !empty($_REQUEST['debug_joined'])) || Wo_IsAdmin()) {
  $show_diag = true;
}
if ($show_diag) {
  $pages = array();
  if ($user_id > 0) {
    $pages = Wo_GetMyCommunities();
  }
  $raw_rows = 0;
  $raw_err = '';
  if ($user_id > 0) {
    $raw_q = "SELECT `id` FROM " . T_COMMUNITIES . " WHERE `id` IN (SELECT `community_id` FROM " . T_COMMUNITY_MEMBERS . " WHERE `user_id` = {$user_id})";
    $res = @mysqli_query($sqlConnect, $raw_q);
    if ($res === false) {
      $raw_err = mysqli_error($sqlConnect);
    } else {
      $raw_rows = mysqli_num_rows($res);
    }
  }
  $wo['joined_diag'] = array(
    'loggedin' => !empty($wo['loggedin']),
    'user_id' => $user_id,
    'pages_count' => count($pages),
    'raw_rows' => $raw_rows,
    'raw_err' => $raw_err
  );
}

// Server-side log for web debugging (appends to php-cli-test/joined_web.log)
$log_path = dirname(__DIR__) . '/php-cli-test/joined_web.log';
$log_line = date('c') . " | user_id=" . (!empty($wo['user']['user_id']) ? $wo['user']['user_id'] : '0') . " | loggedin=" . (!empty($wo['loggedin']) ? '1' : '0') . " | pages_count=" . (isset($wo['joined_diag']) ? $wo['joined_diag']['pages_count'] : 'n/a') . " | raw_rows=" . (isset($wo['joined_diag']) ? $wo['joined_diag']['raw_rows'] : 'n/a') . " | raw_err=" . (isset($wo['joined_diag']) ? str_replace("\n", ' ', $wo['joined_diag']['raw_err']) : '') . "\n";
@file_put_contents($log_path, $log_line, FILE_APPEND);

$wo['content']     = Wo_LoadPage('community/joined-communities');