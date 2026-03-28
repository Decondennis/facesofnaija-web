<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php_diag.log');
define('DIAG_MODE', true);
chdir('/var/www/html/facesofnaija');
include '/var/www/html/facesofnaija/index.php';
