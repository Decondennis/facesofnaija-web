<?php
// Debug script - mimics index.php home page flow
require_once('assets/init.php');

error_log('DEBUG_STEP1: ob_level=' . ob_get_level() . ' loggedin=' . (int)$wo['loggedin']);

// Set home page (what index.php does for logged-in users visiting /)
$page = ($wo['loggedin'] == true) ? 'home' : 'welcome';

if ($wo['loggedin'] == true) {
    include('sources/home.php');
} else {
    include('sources/welcome.php');
}

error_log('DEBUG_STEP2: ob_level=' . ob_get_level() . ' content_len=' . strlen((string)$wo['content']) . ' title=' . $wo['title']);

$container = Wo_LoadPage('container');

error_log('DEBUG_STEP3: ob_level=' . ob_get_level() . ' container_len=' . strlen($container));

@ob_end_clean();

error_log('DEBUG_STEP4: ob_level=' . ob_get_level() . ' - about to echo container');

echo $container;
