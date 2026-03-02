<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
register_shutdown_function(function(){
    $err = error_get_last();
    if ($err) {
        echo "SHUTDOWN ERROR: " . $err['message'] . " in " . $err['file'] . " on line " . $err['line'] . "\n";
    }
});
