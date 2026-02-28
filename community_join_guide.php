<?php
require_once('config.php');

echo "<h2>Community Join Guide & Diagnostic</h2>";

echo "<h3>How to Join a Community:</h3>";
echo "<ol>";
echo "<li><strong>Method 1: From Communities List</strong>";
echo "<ul>";
echo "<li>Go to <a href='index.php?link1=communities'>All Communities</a></li>";
echo "<li>Or go to <a href='index.php?link1=suggested-communities'>Suggested Communities</a></li>";
echo "<li>Click the <strong>Join</strong> button on any community</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Method 2: From Community Page</strong>";
echo "<ul>";
echo "<li>Visit any community page directly</li>";
echo "<li>Click the <strong>Join</strong> button at the top</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";

echo "<hr><h3>Community Privacy Types:</h3>";
echo "<ul>";
echo "<li><strong>Public (1):</strong> Anyone can join instantly</li>";
echo "<li><strong>Private (2):</strong> You send a join request, admin must approve</li>";
echo "</ul>";

// Check if there are any communities
echo "<hr><h3>Existing Communities:</h3>";
$communities = mysqli_query($sqlConnect, "SELECT * FROM Wo_Communities LIMIT 10");
if (mysqli_num_rows($communities) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Privacy</th><th>Members</th><th>Action</th></tr>";
    while ($comm = mysqli_fetch_assoc($communities)) {
        $privacy = ($comm['privacy'] == 1 ? 'Public' : 'Private');
        $members_count = mysqli_fetch_assoc(mysqli_query($sqlConnect, "SELECT COUNT(*) as count FROM Wo_CommunityMembers WHERE community_id = " . $comm['id']))['count'];
        
        echo "<tr>";
        echo "<td>" . $comm['id'] . "</td>";
        echo "<td>" . $comm['community_title'] . "</td>";
        echo "<td>" . $privacy . "</td>";
        echo "<td>" . $members_count . "</td>";
        echo "<td><a href='index.php?link1=community&c=" . $comm['community_name'] . "' target='_blank'>View</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<strong>No communities found.</strong> You need to create one first!<br>";
    echo "<a href='index.php?link1=create-community' class='btn btn-primary'>Create Your First Community</a>";
}

// Check community members table
echo "<hr><h3>Community System Check:</h3>";
$check_members = mysqli_query($sqlConnect, "SHOW TABLES LIKE 'Wo_CommunityMembers'");
if (mysqli_num_rows($check_members) > 0) {
    echo "✅ Wo_CommunityMembers table exists<br>";
} else {
    echo "❌ Wo_CommunityMembers table missing!<br>";
}

$check_requests = mysqli_query($sqlConnect, "SHOW TABLES LIKE 'Wo_CommunityRequests'");
if (mysqli_num_rows($check_requests) > 0) {
    echo "✅ Wo_CommunityRequests table exists<br>";
} else {
    echo "❌ Wo_CommunityRequests table missing!<br>";
}

echo "<hr><strong>Quick Links:</strong><br>";
echo "<a href='index.php?link1=communities'>All Communities</a> | ";
echo "<a href='index.php?link1=create-community'>Create Community</a> | ";
echo "<a href='index.php?link1=joined-communities'>My Joined Communities</a> | ";
echo "<a href='index.php?link1=request-community'>My Pending Requests</a>";
?>
