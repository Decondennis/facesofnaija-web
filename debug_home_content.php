<?php
// Debug home/content.phtml loading specifically
require_once('assets/init.php');

error_log('DEBUG_HOME1: starting, ob_level=' . ob_get_level());

if ($wo['loggedin'] != true) {
    echo 'NOT LOGGED IN - use a valid session cookie';
    exit();
}

$wo['description'] = $wo['config']['siteDesc'];
$wo['keywords']    = $wo['config']['siteKeywords'];
$wo['page']        = 'home';
$wo['title']       = $wo['config']['siteTitle'];

error_log('DEBUG_HOME2: about to Wo_LoadPage(home/content)');

// Test loading the home content
$content = Wo_LoadPage('home/content');

error_log('DEBUG_HOME3: done! content_len=' . strlen($content));

@ob_end_clean();
echo 'SUCCESS: content_len=' . strlen($content);
