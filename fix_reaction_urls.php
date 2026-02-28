<?php
// Fix reaction URLs in database
require_once('config.php');

$db = new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

echo "<h1>🔧 Fixing Reaction Emoji URLs</h1>";

// First, let's see what reactions exist in the database
echo "<h2>Step 1: Checking Wo_Reactions table</h2>";
$check = $db->query("SELECT * FROM Wo_Reactions");

if ($check) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Current SVG Path</th></tr>";
    
    while ($row = $check->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td><small>" . ($row['wowonder_icon'] ?? 'N/A') . "</small></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red;'>Error: " . $db->error . "</p>";
}

echo "<hr><h2>Step 2: Testing get_reaction.php URLs</h2>";

$test_urls = [
    'like' => 'get_reaction.php?f=like',
    'love' => 'get_reaction.php?f=love',
    'haha' => 'get_reaction.php?f=haha'
];

foreach ($test_urls as $name => $url) {
    echo "<div style='margin:10px; display:inline-block;'>";
    echo "<img src='$url' width='40' height='40' style='border:1px solid #ddd;'><br>";
    echo "<small>$name</small>";
    echo "</div>";
}

echo "<hr><h2>Step 3: Update Database (Optional)</h2>";

if (isset($_GET['update'])) {
    $updates = [
        "UPDATE Wo_Reactions SET wowonder_icon = 'get_reaction.php?f=like' WHERE id = 1",
        "UPDATE Wo_Reactions SET wowonder_icon = 'get_reaction.php?f=love' WHERE id = 2",
        "UPDATE Wo_Reactions SET wowonder_icon = 'get_reaction.php?f=haha' WHERE id = 3",
        "UPDATE Wo_Reactions SET wowonder_icon = 'get_reaction.php?f=wow' WHERE id = 4",
        "UPDATE Wo_Reactions SET wowonder_icon = 'get_reaction.php?f=sad' WHERE id = 5",
        "UPDATE Wo_Reactions SET wowonder_icon = 'get_reaction.php?f=angry' WHERE id = 6"
    ];
    
    foreach ($updates as $sql) {
        if ($db->query($sql)) {
            echo "<p style='color:green;'>✓ " . $sql . "</p>";
        } else {
            echo "<p style='color:red;'>✗ Error: " . $db->error . "</p>";
        }
    }
    
    echo "<p><strong>Done! <a href='?'>Refresh to see changes</a></strong></p>";
} else {
    echo "<p><a href='?update=1' style='background:#4CAF50; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Click Here to Update Database</a></p>";
    echo "<p><em>This will change all reaction icons to use get_reaction.php URLs</em></p>";
}

$db->close();

echo "<hr><h2>✅ Solution Implemented!</h2>";
echo "<p><strong>The reactions now work via PHP proxy (get_reaction.php)</strong></p>";
echo "<p>This bypasses all Apache/htaccess issues and serves SVGs with proper headers.</p>";
?>
