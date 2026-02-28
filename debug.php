<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Step 1: PHP is working<br>";

try {
    require_once('config.php');
    echo "Step 2: Config loaded<br>";
    echo "Database: " . $sql_db_name . "<br>";
} catch (Exception $e) {
    die("Config error: " . $e->getMessage());
}

try {
    echo "Step 3: About to load init.php<br>";
    ob_start();
    require_once('assets/init.php');
    $errors = ob_get_clean();
    if ($errors) {
        echo "Errors during init: <pre>" . htmlspecialchars($errors) . "</pre>";
    }
    echo "Step 4: Init loaded successfully<br>";
    
    echo "Step 5: Checking if logged in: " . ($wo['loggedin'] ? 'YES' : 'NO') . "<br>";
    echo "Step 6: Site URL: " . $wo['config']['site_url'] . "<br>";
    
} catch (Error $e) {
    die("Init error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
}

echo "<br><strong>All systems working!</strong>";
?>
