<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
chdir('/var/www/html');

$_COOKIE['user_id'] = 'f87c2a6fe657013a26a56256995ffb55bc96e43b4b932c71aebb715a79f0b773b1fa843a251137494cea2358d3cc5f8cd32397ca9bc51b94';

require_once 'assets/init.php';

try {
    include 'sources/joined_communities.php';
    echo "SOURCE_OK\n";
    $html = Wo_LoadPage('community/joined-communities');
    echo "TPL_OK len=" . strlen($html) . "\n";
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . " @ " . $e->getFile() . ':' . $e->getLine() . "\n";
}
