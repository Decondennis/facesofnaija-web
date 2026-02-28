<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Full Init Test</h2>";

try {
    echo "Loading config.php...<br>";
    require_once('config.php');
    echo "✓ Config loaded<br><br>";
    
    echo "Loading assets/init.php...<br>";
    require_once('assets/init.php');
    echo "✓ Init loaded<br><br>";
    
    echo "<h3>Checking Variables:</h3>";
    echo "- \$wo exists: " . (isset($wo) ? 'YES' : 'NO') . "<br>";
    echo "- \$sqlConnect exists: " . (isset($sqlConnect) ? 'YES' : 'NO') . "<br>";
    echo "- Database connected: " . (isset($sqlConnect) && $sqlConnect ? 'YES' : 'NO') . "<br>";
    
    if (isset($wo)) {
        echo "- Logged in: " . ($wo['loggedin'] ? 'YES' : 'NO') . "<br>";
        echo "- Site URL: " . (isset($wo['config']['site_url']) ? $wo['config']['site_url'] : 'NOT SET') . "<br>";
    }
    
    echo "<br><h3>Community Functions:</h3>";
    echo "- Wo_CommunityData exists: " . (function_exists('Wo_CommunityData') ? 'YES' : 'NO') . "<br>";
    echo "- Wo_RegisterCommunity exists: " . (function_exists('Wo_RegisterCommunity') ? 'YES' : 'NO') . "<br>";
    
    echo "<br><h3>Constants Defined:</h3>";
    echo "- T_COMMUNITIES: " . (defined('T_COMMUNITIES') ? T_COMMUNITIES : 'NOT DEFINED') . "<br>";
    echo "- T_COMMUNITY_MEMBERS: " . (defined('T_COMMUNITY_MEMBERS') ? T_COMMUNITY_MEMBERS : 'NOT DEFINED') . "<br>";
    
    echo "<br><h2 style='color: green;'>✓ ALL SYSTEMS OPERATIONAL!</h2>";
    
} catch (Error $e) {
    echo "<h2 style='color: red;'>✗ ERROR</h2>";
    echo "Message: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
} catch (Exception $e) {
    echo "<h2 style='color: red;'>✗ EXCEPTION</h2>";
    echo "Message: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}
?>
