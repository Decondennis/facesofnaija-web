<?php
require_once('assets/init.php');

echo "<h2>Join Button Preview</h2>";

// Get a community
$comm_query = mysqli_query($sqlConnect, "SELECT * FROM Wo_Communities LIMIT 1");
$community = mysqli_fetch_assoc($comm_query);

if ($community) {
    echo "<h3>Testing with: " . $community['community_title'] . "</h3>";
    echo "<p>Community ID: " . $community['id'] . "</p>";
    
    // Temporarily set $wo variables to simulate viewing as another user
    $original_user_id = $wo['user']['user_id'];
    
    // Check if current user is owner
    echo "<h4>Current User Status:</h4>";
    echo "Your User ID: " . $wo['user']['user_id'] . "<br>";
    
    // Check if you're a member
    $is_member = Wo_IsCommunityJoined($community['id'], $wo['user']['user_id']);
    echo "Are you a member? " . ($is_member ? "YES" : "NO") . "<br>";
    
    // Try to get the button
    echo "<br><h4>Join Button Test:</h4>";
    $button = Wo_GetCommunityJoinButton($community['id']);
    
    if ($button) {
        echo "<div style='border:2px solid green; padding:20px; background:#f0f0f0;'>";
        echo $button;
        echo "</div>";
    } else {
        echo "<div style='border:2px solid orange; padding:20px; background:#fff8dc;'>";
        echo "⚠️ No button shown because:<br>";
        echo "- You might be the owner/creator of this community<br>";
        echo "- OR you're already a member<br>";
        echo "<br>To see the Join button, you need to visit a community you DON'T own and haven't joined yet.";
        echo "</div>";
    }
    
    echo "<br><h4>Your Communities:</h4>";
    $my_communities = mysqli_query($sqlConnect, "SELECT c.* FROM Wo_Communities c 
        INNER JOIN Wo_CommunityMembers cm ON c.id = cm.community_id 
        WHERE cm.user_id = " . $wo['user']['user_id']);
    
    if (mysqli_num_rows($my_communities) > 0) {
        echo "<ul>";
        while ($my_comm = mysqli_fetch_assoc($my_communities)) {
            echo "<li>" . $my_comm['community_title'] . " (you are a member)</li>";
        }
        echo "</ul>";
        echo "<p><strong>These communities won't show a Join button for you.</strong></p>";
    }
}
?>
