<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
include "/var/www/html/facesofnaija/config.php";
$con = new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);
echo $con->connect_error ? "DB_ERROR: " . $con->connect_error : "DB_OK";
echo " | SITE: " . $site_url;
echo " | PHP: " . PHP_VERSION;
