<?php
chdir(__DIR__ . '/..');
require 'assets/init.php';
// simulate request debug flag
$_REQUEST['debug_joined'] = 1;
include 'sources/joined_communities.php';
if (!empty($wo['joined_diag'])) {
    echo "joined_diag:\n";
    print_r($wo['joined_diag']);
} else {
    echo "no joined_diag\n";
}

?>
