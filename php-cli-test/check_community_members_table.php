<?php
chdir(__DIR__ . '/..');
require 'assets/init.php';
global $sqlConnect;
$table = T_COMMUNITY_MEMBERS;
$res = mysqli_query($sqlConnect, "SHOW TABLES LIKE 'Wo_Community_Members'");
if ($res === false) {
    echo "ERROR SHOW TABLES: " . mysqli_error($sqlConnect) . "\n";
} else {
    echo "rows=" . mysqli_num_rows($res) . "\n";
    while ($r = mysqli_fetch_row($res)) echo "table:" . $r[0] . "\n";
}
$res2 = mysqli_query($sqlConnect, "SELECT * FROM " . $table . " LIMIT 1");
if ($res2 === false) {
    echo "ERROR SELECT: " . mysqli_error($sqlConnect) . "\n";
} else {
    echo "SELECT OK, rows=" . mysqli_num_rows($res2) . "\n";
}
