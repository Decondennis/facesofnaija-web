<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
define('WOWONDER', true);
chdir('/var/www/html/facesofnaija');
require_once('config.php');
// Check DB for admin user and directory_system setting
$pdo = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4", $config['db_username'], $config['db_password']);
$row = $pdo->query("SELECT directory_system, membership_system FROM site_settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);
echo 'directory_system: ' . ($row['directory_system'] ?? 'N/A') . '<br>';
echo 'membership_system: ' . ($row['membership_system'] ?? 'N/A') . '<br>';
$adminRow = $pdo->query("SELECT user_id, username, admin, is_pro FROM users WHERE admin=1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
echo 'Admin user: '; print_r($adminRow); echo '<br>';

// Simulate session for admin
session_name('PHPSESSID');
session_start();
if (!empty($adminRow)) {
    $_SESSION['user_id'] = $adminRow['user_id'];
    echo 'Session set for user_id=' . $adminRow['user_id'] . '<br>';
    session_write_close();
}

require_once('assets/init.php');
echo 'INIT OK<br>';
echo 'Logged in: ' . ($wo['loggedin'] ? 'yes' : 'no') . '<br>';
echo 'User admin: ' . ($wo['user']['admin'] ?? 'n/a') . '<br>';
echo 'User is_pro: ' . ($wo['user']['is_pro'] ?? 'n/a') . '<br>';

try {
    ob_start();
    $content = Wo_LoadPage('home/content');
    ob_end_clean();
    echo 'home/content len: ' . strlen($content) . '<br>';
    if (strlen($content) < 200) {
        echo 'Content: <pre>' . htmlspecialchars($content) . '</pre>';
    }
} catch (Throwable $e) {
    ob_end_clean();
    echo 'EXCEPTION: ' . $e->getMessage() . ' in ' . basename($e->getFile()) . ':' . $e->getLine() . '<br>';
}

