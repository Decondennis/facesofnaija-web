<?php
require_once('assets/init.php');

echo "<h2>Create Community via Script</h2>";

// Check if we have the necessary data
if (isset($_POST['create'])) {
    $community_name = 'test-community-' . time();
    $community_title = 'Test Community ' . date('Y-m-d H:i:s');
    $category = 1;
    $privacy = 1; // Public
    $user_id = $wo['user']['user_id']; // Current logged in user

    $insert = mysqli_query($sqlConnect, "INSERT INTO Wo_Communities 
        (community_name, community_title, user_id, category, privacy, active, time) 
        VALUES 
        ('$community_name', '$community_title', $user_id, $category, $privacy, 1, " . time() . ")");

    if ($insert) {
        $community_id = mysqli_insert_id($sqlConnect);

        // Add creator as member
        mysqli_query($sqlConnect, "INSERT INTO Wo_CommunityMembers (community_id, user_id, active, time) 
            VALUES ($community_id, $user_id, 1, " . time() . ")");

        echo "<div style='background:green;color:white;padding:10px;'>";
        echo "✅ Community created successfully!<br>";
        echo "ID: " . $community_id . "<br>";
        echo "Name: " . $community_name . "<br>";
        echo "Title: " . $community_title . "<br>";
        echo "<br><a href='index.php?link1=community&c=$community_name' style='color:white;font-weight:bold;'>View Community</a>";
        echo "</div>";
    } else {
        echo "<div style='background:red;color:white;padding:10px;'>";
        echo "❌ Error: " . mysqli_error($sqlConnect);
        echo "</div>";
    }
} else {
    ?>
    <form method="POST">
        <p>Click the button below to create a test community:</p>
        <button type="submit" name="create" style="padding:10px 20px;background:green;color:white;border:none;cursor:pointer;">Create Test Community</button>
    </form>
    <?php
}

// Check if function exists
echo "<hr><h3>Function Check:</h3>";
if (function_exists('Wo_GetCommunityJoinButton')) {
    echo "✅ Wo_GetCommunityJoinButton() EXISTS<br>";
} else {
    echo "❌ Wo_GetCommunityJoinButton() MISSING<br>";
}

// List existing communities
echo "<h3>Existing Communities:</h3>";
$communities = mysqli_query($sqlConnect, "SELECT * FROM Wo_Communities");
if (mysqli_num_rows($communities) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Title</th><th>Owner</th><th>Link</th></tr>";
    while ($comm = mysqli_fetch_assoc($communities)) {
        echo "<tr>";
        echo "<td>" . $comm['id'] . "</td>";
        echo "<td>" . $comm['community_name'] . "</td>";
        echo "<td>" . $comm['community_title'] . "</td>";
        echo "<td>" . $comm['user_id'] . "</td>";
        echo "<td><a href='index.php?link1=community&c=" . $comm['community_name'] . "' target='_blank'>View</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No communities found.";
}
?>
