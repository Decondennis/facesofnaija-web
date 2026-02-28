<?php
require_once('config.php');

$conn = mysqli_connect($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h2>Database Tables in: " . $sql_db_name . "</h2>";

$result = mysqli_query($conn, "SHOW TABLES");

if ($result) {
    $tables = [];
    while ($row = mysqli_fetch_array($result)) {
        $tables[] = $row[0];
    }
    
    echo "<p>Total tables: " . count($tables) . "</p>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>" . $table . "</li>";
    }
    echo "</ul>";
    
    // Check for community tables
    echo "<h3>Community Tables Check:</h3>";
    $community_tables = array_filter($tables, function($t) {
        return stripos($t, 'community') !== false || stripos($t, 'communities') !== false;
    });
    
    if (count($community_tables) > 0) {
        echo "<p style='color: green;'>✓ Found " . count($community_tables) . " community table(s)</p>";
        foreach ($community_tables as $t) {
            echo "- " . $t . "<br>";
        }
    } else {
        echo "<p style='color: red;'>✗ No community tables found</p>";
    }
    
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
