<?php
// Debug output buffer levels - TEMPORARY DEBUG ONLY
require_once('assets/init.php');
$level_before = ob_get_level();
$container = Wo_LoadPage('container');
$level_after = ob_get_level();
@ob_end_clean();
error_log('DEBUG ob_level_before=' . $level_before . ' ob_level_after=' . $level_after . ' container_len=' . strlen($container));
echo 'LEVEL_BEFORE=' . $level_before . ' LEVEL_AFTER=' . $level_after . ' CONTAINER_LEN=' . strlen($container) . ' LOGGED_IN=' . ($wo['loggedin'] ? 'yes' : 'no');
