<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('assets/init.php');

echo "Testing raw SQL query...\n";
$user_id = 0; // Unauthenticated

echo "Query 1: Simple active communities\n";
$start = microtime(true);
$query = " SELECT `id` FROM wo_communities WHERE `active` = '1' ORDER BY `id` DESC LIMIT 5";
echo "SQL: $query\n";
$sql = mysqli_query($GLOBALS['sqlConnect'], $query);
echo "Query completed in " . (microtime(true) - $start) . "s\n";
echo "Rows: " . ($sql ? mysqli_num_rows($sql) : 'ERROR: ' . mysqli_error($GLOBALS['sqlConnect'])) . "\n";

if ($sql && mysqli_num_rows($sql)) {
    while ($row = mysqli_fetch_assoc($sql)) {
        echo "  Community ID: " . $row['id'] . "\n";
    }
}

echo "\n\nNow testing Wo_CommunityData() on each ID...\n";
if ($sql) {
    mysqli_data_seek($sql, 0);
    while ($row = mysqli_fetch_assoc($sql)) {
        echo "Loading data for community " . $row['id'] . "...\n";
        $start = microtime(true);
        $data = Wo_CommunityData($row['id']);
        echo "  Took " . (microtime(true) - $start) . "s\n";
    }
}
?>
