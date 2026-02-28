<?php
@ini_set('session.cookie_httponly',1);
@ini_set('session.use_only_cookies',1);
@header("X-FRAME-OPTIONS: SAMEORIGIN");
// keep default error reporting (show errors only during development)
@ini_set('display_errors', 0);
@ini_set('display_startup_errors', 0);
error_reporting(0);
if (!version_compare(PHP_VERSION, '5.5.0', '>=')) {
    exit("Required PHP_VERSION >= 5.5.0 , Your PHP_VERSION is : " . PHP_VERSION . "\n");
}
date_default_timezone_set('UTC');
session_start();
// Buffer output to prevent accidental output before HTML DOCTYPE
@ob_start();
@ini_set('gd.jpeg_ignore_warning', 1);
require_once('assets/libraries/DB/vendor/joshcam/mysqli-database-class/MySQL-Maria.php');
require_once('includes/cache.php');
require_once('includes/functions_general.php');
require_once('includes/tabels.php');
require_once('includes/community_tables.php'); //added this
require_once('includes/community_functions.php');
require_once('includes/functions_one.php');
require_once('includes/functions_two.php');
require_once('includes/functions_three.php');
