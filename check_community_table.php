<?php
require_once('assets/init.php');

echo "<h2>Wo_Communities Table Structure</h2>";

$structure = mysqli_query($sqlConnect, "DESCRIBE Wo_Communities");
if ($structure) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_assoc($structure)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<br><h3>Now let's test the Join Button on your communities:</h3>";

// Get your communities
$communities = mysqli_query($sqlConnect, "SELECT * FROM Wo_Communities LIMIT 3");
while ($comm = mysqli_fetch_assoc($communities)) {
    echo "<div style='border:1px solid #ccc; padding:10px; margin:10px 0;'>";
    echo "<h4>" . $comm['community_title'] . " (ID: " . $comm['id'] . ")</h4>";
    echo "<a href='index.php?link1=community&c=" . $comm['community_name'] . "' target='_blank' style='padding:5px 10px; background:blue; color:white; text-decoration:none;'>Visit Community Page</a>";
    echo "</div>";
}
?>
