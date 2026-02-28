<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Loading Test</h2>";

// Try to load the file directly
echo "Loading community_functions.php...<br>";
require_once('assets/includes/community_functions.php');
echo "✅ File loaded successfully<br><br>";

// Check if function exists
if (function_exists('Wo_GetCommunityJoinButton')) {
    echo "✅ Wo_GetCommunityJoinButton() function EXISTS!<br>";
} else {
    echo "❌ Wo_GetCommunityJoinButton() function DOES NOT EXIST<br>";
}

// List all functions defined in the file
echo "<br><h3>All functions defined in community_functions.php:</h3>";
$functions = get_defined_functions();
$user_functions = $functions['user'];

$community_functions = array_filter($user_functions, function($func) {
    return stripos($func, 'community') !== false || stripos($func, 'wo_') !== false;
});

foreach ($community_functions as $func) {
    echo $func . "<br>";
}
?>
