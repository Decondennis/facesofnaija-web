<?php
chdir(__DIR__ . '/..');
require 'assets/init.php';
global $sqlConnect;
$res = mysqli_query($sqlConnect, "SHOW TABLES LIKE 'Wo_Reports'");
if ($res === false) {
    echo "ERROR: " . mysqli_error($sqlConnect) . "\n";
    exit(1);
}
echo "rows=" . mysqli_num_rows($res) . "\n";
if (mysqli_num_rows($res)) {
    $r = mysqli_fetch_row($res);
    echo "table:" . $r[0] . "\n";
}
