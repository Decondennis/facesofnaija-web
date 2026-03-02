<?php
chdir(__DIR__ . '/..');
require 'assets/init.php';
global $sqlConnect;
$res = mysqli_query($sqlConnect, "SHOW COLUMNS FROM Wo_Reports");
if ($res === false) {
    echo "ERROR: " . mysqli_error($sqlConnect) . "\n";
    exit(1);
}
while ($r = mysqli_fetch_assoc($res)) {
    echo $r['Field'] . "\t" . $r['Type'] . "\n";
}
