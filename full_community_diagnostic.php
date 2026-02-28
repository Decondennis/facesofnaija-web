<?php
require_once('assets/init.php');

echo "<h2>Community List & Join Button Diagnostic</h2>";

echo "<h3>1. Your User Info:</h3>";
echo "User ID: " . $wo['user']['user_id'] . "<br>";
echo "Username: " . $wo['user']['username'] . "<br>";
echo "Admin: " . (Wo_IsAdmin() ? "YES" : "NO") . "<br>";

echo "<h3>2. All Communities in Database:</h3>";
$all_communities = mysqli_query($sqlConnect, "SELECT * FROM Wo_Communities");
if (mysqli_num_rows($all_communities) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Title</th><th>Creator ID</th><th>Active</th><th>Privacy</th><th>Test Join Button</th></tr>";
    
    while ($comm = mysqli_fetch_assoc($all_communities)) {
        echo "<tr>";
        echo "<td>" . $comm['id'] . "</td>";
        echo "<td>" . $comm['community_name'] . "</td>";
        echo "<td>" . $comm['community_title'] . "</td>";
        echo "<td>" . ($comm['creator_id'] ?? 'N/A') . "</td>";
        echo "<td>" . ($comm['active'] ?? 'N/A') . "</td>";
        echo "<td>" . ($comm['privacy'] == 1 ? 'Public' : 'Private') . "</td>";
        echo "<td>";
        
        // Test if join button would show
        $wo['community_profile']['id'] = $comm['id'];
        $button = Wo_GetCommunityJoinButton($comm['id']);
        if ($button) {
            echo "✅ Button appears";
        } else {
            echo "❌ No button";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No communities found!";
}

echo "<h3>3. Are you a member of any community?</h3>";
$membership = mysqli_query($sqlConnect, "SELECT * FROM Wo_CommunityMembers WHERE user_id = " . $wo['user']['user_id']);
echo "Memberships found: " . mysqli_num_rows($membership) . "<br>";

echo "<h3>4. Testing Wo_GetMyCommunities() function:</h3>";
$my_communities = Wo_GetMyCommunities();
echo "Count: " . count($my_communities) . "<br>";
if (count($my_communities) > 0) {
    foreach ($my_communities as $c) {
        echo "- " . $c['community_title'] . "<br>";
    }
}

echo "<h3>5. Testing Wo_CommunitySug() function (Suggested Communities):</h3>";
$suggested = Wo_CommunitySug(20);
echo "Count: " . count($suggested) . "<br>";
if (count($suggested) > 0) {
    foreach ($suggested as $s) {
        echo "- " . $s['community_title'] . " (ID: " . $s['id'] . ")<br>";
    }
} else {
    echo "No suggested communities found.";
}

echo "<h3>6. Direct Join Button Test:</h3>";
// Get first community
$test_comm = mysqli_query($sqlConnect, "SELECT * FROM Wo_Communities LIMIT 1");
$test_community = mysqli_fetch_assoc($test_comm);

if ($test_community) {
    echo "Testing with: " . $test_community['community_title'] . " (ID: " . $test_community['id'] . ")<br><br>";
    
    $wo['join'] = Wo_CommunityData($test_community['id']);
    $button_output = Wo_GetCommunityJoinButton($test_community['id']);
    
    if ($button_output) {
        echo "<div style='border:2px solid green; padding:20px;'>";
        echo "<strong>✅ Join Button Generated:</strong><br><br>";
        echo $button_output;
        echo "</div>";
    } else {
        echo "<div style='border:2px solid red; padding:20px;'>";
        echo "❌ No button generated. Reasons could be:<br>";
        echo "- You are the creator/owner<br>";
        echo "- You are already a member<br>";
        echo "- Community data not found<br>";
        echo "</div>";
    }
}
?>
