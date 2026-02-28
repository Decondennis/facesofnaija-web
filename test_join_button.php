<?php
require_once('config.php');

echo "<h2>Community Join Button Test</h2>";

// Test if we can call the function directly
if (function_exists('Wo_GetCommunityJoinButton')) {
    echo "<h3>Function Test</h3>";
    
    // Get a community
    $comm = mysqli_query($sqlConnect, "SELECT * FROM Wo_Communities LIMIT 1");
    if (mysqli_num_rows($comm) > 0) {
        $community = mysqli_fetch_assoc($comm);
        echo "Testing with community: <strong>" . $community['community_title'] . "</strong><br><br>";
        
        // Set up the global $wo variable as if we're on the community page
        $wo['loggedin'] = true;
        $wo['user']['user_id'] = 1; // Assuming admin user_id is 1
        
        echo "Calling Wo_GetCommunityJoinButton(" . $community['id'] . ")...<br><br>";
        
        $button = Wo_GetCommunityJoinButton($community['id']);
        
        if ($button) {
            echo "<div style='border: 2px solid green; padding: 10px;'>";
            echo "<strong>✅ Button Generated Successfully!</strong><br><br>";
            echo $button;
            echo "</div>";
        } else {
            echo "<strong style='color:red;'>❌ Function returned FALSE</strong><br>";
            echo "Possible reasons:<br>";
            echo "- You might be the owner of this community<br>";
            echo "- Not logged in<br>";
            echo "- Community data not found<br>";
        }
        
        echo "<br><br><a href='index.php?link1=community&c=" . $community['community_name'] . "' class='btn btn-primary'>Visit This Community Page</a>";
    } else {
        echo "<strong style='color:red;'>No communities found!</strong><br>";
        echo "<a href='index.php?link1=create-community'>Create a Community First</a>";
    }
} else {
    echo "<strong style='color:red;'>❌ Wo_GetCommunityJoinButton() function not found!</strong>";
}

echo "<br><br><hr><br>";
echo "<h3>Debug Info</h3>";
echo "Logged in: " . ($wo['loggedin'] ? 'YES' : 'NO') . "<br>";
if ($wo['loggedin']) {
    echo "User ID: " . $wo['user']['user_id'] . "<br>";
    echo "Username: " . $wo['user']['username'] . "<br>";
}
?>
