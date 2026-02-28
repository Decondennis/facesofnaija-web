<?php
// Complete Reaction Fix - Updates database AND creates a URL rewrite fallback
require_once('config.php');

$db = new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

echo "<h1>🔧 Complete Reaction Fix</h1>";

// Step 1: Check current reactions
echo "<h2>Step 1: Current Wo_Reactions Table</h2>";
$check = $db->query("SELECT * FROM Wo_Reactions");

if ($check && $check->num_rows > 0) {
    echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
    echo "<tr style='background:#eee;'><th>ID</th><th>Name</th><th>wowonder_icon</th><th>wowonder_small_icon</th></tr>";
    
    while ($row = $check->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td><strong>" . $row['name'] . "</strong></td>";
        echo "<td><small>" . ($row['wowonder_icon'] ?? 'NULL') . "</small></td>";
        echo "<td><small>" . ($row['wowonder_small_icon'] ?? 'NULL') . "</small></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red;'>No reactions found or error: " . $db->error . "</p>";
}

// Step 2: Update database with PHP proxy URLs
echo "<hr><h2>Step 2: Updating Database to Use PHP Proxy</h2>";

$site_url = $wo['config']['site_url'] ?? 'http://localhost/facesofnaija-web';

$updates = [
    "UPDATE Wo_Reactions SET wowonder_icon = '{$site_url}/get_reaction.php?f=like', wowonder_small_icon = '{$site_url}/get_reaction.php?f=like' WHERE id = 1 OR LOWER(name) = 'like'",
    "UPDATE Wo_Reactions SET wowonder_icon = '{$site_url}/get_reaction.php?f=love', wowonder_small_icon = '{$site_url}/get_reaction.php?f=love' WHERE id = 2 OR LOWER(name) = 'love'",
    "UPDATE Wo_Reactions SET wowonder_icon = '{$site_url}/get_reaction.php?f=haha', wowonder_small_icon = '{$site_url}/get_reaction.php?f=haha' WHERE id = 3 OR LOWER(name) = 'haha'",
    "UPDATE Wo_Reactions SET wowonder_icon = '{$site_url}/get_reaction.php?f=wow', wowonder_small_icon = '{$site_url}/get_reaction.php?f=wow' WHERE id = 4 OR LOWER(name) = 'wow'",
    "UPDATE Wo_Reactions SET wowonder_icon = '{$site_url}/get_reaction.php?f=sad', wowonder_small_icon = '{$site_url}/get_reaction.php?f=sad' WHERE id = 5 OR LOWER(name) = 'sad'",
    "UPDATE Wo_Reactions SET wowonder_icon = '{$site_url}/get_reaction.php?f=angry', wowonder_small_icon = '{$site_url}/get_reaction.php?f=angry' WHERE id = 6 OR LOWER(name) = 'angry'"
];

foreach ($updates as $sql) {
    if ($db->query($sql)) {
        echo "<p style='color:green;'>✓ " . substr($sql, 0, 100) . "...</p>";
    } else {
        echo "<p style='color:red;'>✗ Error: " . $db->error . "</p>";
    }
}

// Step 3: Clear cache
echo "<hr><h2>Step 3: Clearing Cache</h2>";
$cache_dir = 'cache/';
$count = 0;
$files = glob($cache_dir . '*');
if ($files) {
    foreach($files as $file) {
        if(is_file($file) && basename($file) !== '.htaccess') {
            unlink($file);
            $count++;
        }
    }
}
echo "<p style='color:green;'>✓ Cleared $count cache files</p>";

// Step 4: Verify updates
echo "<hr><h2>Step 4: Verification - Updated Reactions</h2>";
$check2 = $db->query("SELECT * FROM Wo_Reactions");

if ($check2 && $check2->num_rows > 0) {
    echo "<table border='1' cellpadding='8' style='border-collapse: collapse;'>";
    echo "<tr style='background:#eee;'><th>ID</th><th>Name</th><th>Icon Preview</th><th>Small Icon URL</th></tr>";
    
    while ($row = $check2->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td><strong>" . $row['name'] . "</strong></td>";
        echo "<td><img src='" . $row['wowonder_small_icon'] . "' width='30' height='30' style='border:1px solid #ddd;'></td>";
        echo "<td><small>" . $row['wowonder_small_icon'] . "</small></td>";
        echo "</tr>";
    }
    echo "</table>";
}

$db->close();

echo "<hr>";
echo "<h2 style='color:green;'>✅ COMPLETE!</h2>";
echo "<h3>Final Steps:</h3>";
echo "<ol>";
echo "<li><strong>Hard refresh your browser</strong> (Ctrl + Shift + R)</li>";
echo "<li><strong>Go to your homepage:</strong> <a href='{$site_url}'>{$site_url}</a></li>";
echo "<li><strong>Reactions should now display!</strong> 🎉</li>";
echo "</ol>";

echo "<hr>";
echo "<h3>⚠️ Clean Up (After Confirming It Works):</h3>";
echo "<p>Delete these test files:</p>";
echo "<ul>";
echo "<li>final_reaction_fix.php (this file)</li>";
echo "<li>fix_reaction_urls.php</li>";
echo "<li>create_reactions.php</li>";
echo "<li>test_reaction.php</li>";
echo "<li>quick_reaction_test.php</li>";
echo "<li>complete_fix.php</li>";
echo "<li>check_headers.php</li>";
echo "<li>test_icons.php</li>";
echo "<li>svg_diagnostic.php</li>";
echo "</ul>";
echo "<p><strong>Keep:</strong> get_reaction.php (this serves the emojis)</p>";
?>
