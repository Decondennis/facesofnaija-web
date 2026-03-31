<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('assets/init.php');

echo "Testing Wo_CommunitySug function...\n";
echo "User ID: " . (isset($wo['user']['user_id']) ? $wo['user']['user_id'] : 'NOT SET') . "\n";

try {
    echo "\nCalling Wo_CommunitySug(5)...\n";
    $start = microtime(true);
    $result = Wo_CommunitySug(5);
    $elapsed = microtime(true) - $start;
    
    echo "Result: " . json_encode($result) . "\n";
    echo "Time taken: {$elapsed}s\n";
    echo "Result type: " . gettype($result) . "\n";
    echo "Result count: " . (is_array($result) ? count($result) : 'N/A') . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>
