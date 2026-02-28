<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Loading files step by step...<br><br>";

try {
    echo "1. Loading cache.php...<br>";
    require_once('assets/includes/cache.php');
    echo "&nbsp;&nbsp;&nbsp;✓ Success<br>";
} catch (Error $e) {
    die("✗ Error in cache.php: " . $e->getMessage());
}

try {
    echo "2. Loading functions_general.php...<br>";
    require_once('assets/includes/functions_general.php');
    echo "&nbsp;&nbsp;&nbsp;✓ Success<br>";
} catch (Error $e) {
    die("✗ Error in functions_general.php: " . $e->getMessage());
}

try {
    echo "3. Loading tabels.php...<br>";
    require_once('assets/includes/tabels.php');
    echo "&nbsp;&nbsp;&nbsp;✓ Success<br>";
} catch (Error $e) {
    die("✗ Error in tabels.php: " . $e->getMessage());
}

try {
    echo "4. Loading community_tables.php...<br>";
    require_once('assets/includes/community_tables.php');
    echo "&nbsp;&nbsp;&nbsp;✓ Success<br>";
} catch (Error $e) {
    die("✗ Error in community_tables.php: " . $e->getMessage());
}

try {
    echo "5. Loading community_functions.php...<br>";
    require_once('assets/includes/community_functions.php');
    echo "&nbsp;&nbsp;&nbsp;✓ Success<br>";
} catch (Error $e) {
    die("✗ Error in community_functions.php: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
}

echo "<br><strong>All files loaded successfully!</strong>";
?>
