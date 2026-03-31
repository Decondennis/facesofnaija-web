<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
define('WOWONDER', true);
chdir('/var/www/html/facesofnaija');
// Read DB settings directly from config
require_once('config.php');
// Connect using the variables config.php defines
$host = $sql_db_host ?? 'localhost';
$user = $sql_db_user ?? '';
$pass = $sql_db_pass ?? '';
$dbname = $sql_db_name ?? '';
if (function_exists('mysqli_connect')) {
    $conn = mysqli_connect($host, $user, $pass, $dbname);
    if ($conn) {
        echo "DB: $dbname<br>\n";
        $res = mysqli_query($conn, "SHOW TABLES");
        echo "Tables: ";
        while ($row = mysqli_fetch_row($res)) { echo $row[0] . " | "; }
        echo "<br>\n";
        $res = mysqli_query($conn, "SELECT name, value FROM Wo_Config WHERE name IN ('directory_system','membership_system') LIMIT 10");
        while ($row = mysqli_fetch_assoc($res)) {
            echo $row['name'] . "=" . $row['value'] . "<br>\n";
        }
        $res2 = mysqli_query($conn, "SELECT user_id, admin, is_pro FROM Wo_Users WHERE admin=1 LIMIT 1");
        $admin = mysqli_fetch_assoc($res2);
        echo "Admin: user_id=" . $admin['user_id'] . " is_pro=" . $admin['is_pro'] . "<br>\n";
        mysqli_close($conn);
    } else {
        echo "DB connect failed: " . mysqli_connect_error() . "<br>\n";
    }
} else {
    echo "mysqli not available<br>\n";
}
