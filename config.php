<?php
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.wowonder.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com   
// +------------------------------------------------------------------------+
// | WoWonder - The Ultimate PHP Social Networking Platform
// | Copyright (c) 2016 WoWonder. All rights reserved.
// +------------------------------------------------------------------------+

// ==========================================================================
// ENVIRONMENT AUTO-DETECTION
// ==========================================================================
$_detected_host = $_SERVER['HTTP_HOST'] ?? 'localhost';

if ($_detected_host === '172.236.19.52') {
    // Live server (IP address - DNS not yet pointed)
    $site_url    = "http://172.236.19.52";
    $sql_db_host = "localhost";
    $sql_db_user = "facesofnaija_user";
    $sql_db_pass = "facesofnaija_pass123";
    $sql_db_name = "facesofnaija";
} else {
    // Local development
    $site_url    = "http://localhost/facesofnaija-web";
    $sql_db_host = "localhost";
    $sql_db_user = "facesofnaija_user";
    $sql_db_pass = "facesofnaija_pass123";
    $sql_db_name = "facesofnaija";
}

// Purchase code (Don't share this with anyone)
$purchase_code = "330ec2fb-f1e5-4229-b7d4-866894cff196";

// ==========================================================================
// DEVELOPMENT MODE (Enable errors for debugging)
// ==========================================================================
if (strpos($site_url, 'localhost') !== false) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}
?>