<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
define('WOWONDER', true);
chdir('/var/www/html/facesofnaija');
require_once('config.php');
// Check DB settings using Wo_ table names
$host = $sql_db_host ?? 'localhost';
$user_db = $sql_db_user ?? '';
$pass_db = $sql_db_pass ?? '';
$dbname = $sql_db_name ?? '';
$conn = mysqli_connect($host, $user_db, $pass_db, $dbname);
if (!$conn) { die("DB fail: " . mysqli_connect_error()); }
// Get config values
$res = mysqli_query($conn, "SELECT name, value FROM Wo_Config WHERE name IN ('directory_system','membership_system')");
while ($r = mysqli_fetch_assoc($res)) {
    echo $r['name'] . '=' . $r['value'] . '<br>';
}
// Get admin user ID - use ORDER BY to ensure user_id=1 (facesofnaija) comes first
$res2 = mysqli_query($conn, "SELECT user_id, admin, is_pro FROM Wo_Users WHERE user_id=1 LIMIT 1");
$adminRow = mysqli_fetch_assoc($res2);
echo 'Admin: user_id=' . ($adminRow['user_id'] ?? 'none') . ' admin=' . ($adminRow['admin'] ?? 'N/A') . ' is_pro=' . ($adminRow['is_pro'] ?? 'N/A') . '<br>';

// Get admin session hash
$res3 = mysqli_query($conn, "SELECT session_id FROM Wo_AppsSessions WHERE user_id='" . intval($adminRow['user_id']) . "' AND platform='web' ORDER BY time DESC LIMIT 1");
$sessionRow = $res3 ? mysqli_fetch_assoc($res3) : null;
echo 'Session hash: ' . (empty($sessionRow['session_id']) ? 'none' : 'found') . '<br>';
mysqli_close($conn);

if (!empty($sessionRow['session_id'])) {
    $sid = 'dbgsession' . time();
    session_id($sid);
    session_start();
    $_SESSION['user_id'] = $sessionRow['session_id'];
    echo 'Set session user_id hash: ' . $_SESSION['user_id'] . '<br>';
    session_write_close();
    $_COOKIE['PHPSESSID'] = $sid;
} else {
    echo 'No session hash found - proceeding without login<br>';
}

require_once('assets/init.php');
echo 'Session after init: user_id=' . substr($_SESSION['user_id'] ?? 'missing', 0, 20) . '<br>';
echo 'Logged in: ' . ($wo['loggedin'] ? 'yes' : 'no') . '<br>';
echo 'User admin: ' . ($wo['user']['admin'] ?? 'n/a') . '<br>';
echo 'wo[page]: ' . ($wo['page'] ?? 'not set') . '<br>';

try {
    $content = Wo_LoadPage('home/content');
    echo 'home/content len: ' . strlen($content) . '<br>';
} catch (Throwable $e) {
    echo 'EXCEPTION: ' . $e->getMessage() . ' at line ' . $e->getLine() . '<br>';
}

