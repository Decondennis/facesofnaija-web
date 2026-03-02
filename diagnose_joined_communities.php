<?php
require_once('assets/init.php');

echo "<h2>Joined Communities Diagnostic</h2>";

echo "<h3>1. Your User Info:</h3>";
echo "User ID: " . $wo['user']['user_id'] . "<br>";
echo "Username: " . $wo['user']['username'] . "<br>";

echo "<h3>2. Check Wo_Community_Members Table (Correct Table):</h3>";
$members_check = mysqli_query($sqlConnect, "SELECT * FROM " . T_COMMUNITY_MEMBERS . " WHERE user_id = " . $wo['user']['user_id']);
echo "Memberships found in database: <strong>" . mysqli_num_rows($members_check) . "</strong><br><br>";

if (mysqli_num_rows($members_check) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Community ID</th><th>Community Name</th><th>Active</th><th>Join Time</th></tr>";
    while ($member = mysqli_fetch_assoc($members_check)) {
        // Get community details
        $comm = mysqli_query($sqlConnect, "SELECT * FROM Wo_Communities WHERE id = " . $member['community_id']);
        $community = mysqli_fetch_assoc($comm);
        
        echo "<tr>";
        echo "<td>" . $member['community_id'] . "</td>";
        echo "<td>" . ($community ? $community['community_title'] : 'N/A') . "</td>";
        echo "<td>" . $member['active'] . "</td>";
        echo "<td>" . date('Y-m-d H:i:s', $member['time']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<strong style='color:red;'>No memberships found in " . T_COMMUNITY_MEMBERS . " table!</strong><br>";
    echo "This means the join didn't work properly.";
}

echo "<h3>3. Testing Wo_GetMyCommunities() Function:</h3>";
$my_communities = Wo_GetMyCommunities();
echo "Communities returned: <strong>" . count($my_communities) . "</strong><br><br>";

if (count($my_communities) > 0) {
    echo "<ul>";
    foreach ($my_communities as $comm) {
        echo "<li>" . $comm['community_title'] . " (ID: " . $comm['id'] . ")</li>";
    }
    echo "</ul>";
} else {
    echo "<strong style='color:orange;'>Function returned 0 communities</strong><br>";
}

echo "<h3>4. Testing SQL Query Directly:</h3>";
$user_id = Wo_Secure($wo['user']['user_id']);
$query_text = "SELECT `id` FROM " . T_COMMUNITIES . " WHERE `id` IN (SELECT `community_id` FROM ".T_COMMUNITY_MEMBERS." WHERE `user_id` = {$user_id})";
echo "SQL Query: <pre>" . $query_text . "</pre>";

$result = mysqli_query($sqlConnect, $query_text);
echo "Results found: <strong>" . mysqli_num_rows($result) . "</strong><br>";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "- Community ID: " . $row['id'] . "<br>";
    }
}

echo "<h3>5. Check T_COMMUNITY_MEMBERS constant:</h3>";
echo "T_COMMUNITY_MEMBERS = " . T_COMMUNITY_MEMBERS . "<br>";

echo "<h3>6. Manual Join Test:</h3>";
echo "<p>If memberships are not in database, the Join button might not be working. Let me test joining manually:</p>";
$test_comm = mysqli_query($sqlConnect, "SELECT * FROM Wo_Communities LIMIT 1");
$test_community = mysqli_fetch_assoc($test_comm);

if ($test_community) {
    echo "<form method='POST'>";
    echo "<input type='hidden' name='community_id' value='" . $test_community['id'] . "'>";
    echo "<button type='submit' name='manual_join'>Manually Join: " . $test_community['community_title'] . "</button>";
    echo "</form>";
    
    if (isset($_POST['manual_join'])) {
        $join = Wo_RegisterCommunityJoin($_POST['community_id'], $wo['user']['user_id']);
        if ($join) {
            echo "<div style='background:green;color:white;padding:10px;'>✅ Successfully joined! Refresh the page.</div>";
        } else {
            echo "<div style='background:red;color:white;padding:10px;'>❌ Join failed!</div>";
        }
    }
}
?>
