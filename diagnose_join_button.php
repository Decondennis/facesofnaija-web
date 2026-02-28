<?php
require_once('config.php');

echo "<h2>Community Join Button Diagnostic</h2>";

// 1. Check if button template files exist
echo "<h3>1. Button Template Files</h3>";
$templates = [
    'themes/facesofnaija/layout/buttons/join-community.phtml',
    'themes/facesofnaija/layout/buttons/leave-community.phtml',
    'themes/facesofnaija/layout/buttons/join-community-requested.phtml'
];

foreach ($templates as $template) {
    if (file_exists($template)) {
        echo "✅ " . $template . " EXISTS<br>";
    } else {
        echo "❌ " . $template . " MISSING<br>";
    }
}

// 2. Check if function exists
echo "<h3>2. Checking Function</h3>";
if (function_exists('Wo_GetCommunityJoinButton')) {
    echo "✅ Wo_GetCommunityJoinButton() function EXISTS<br>";
} else {
    echo "❌ Wo_GetCommunityJoinButton() function MISSING<br>";
}

// 3. Check if community_functions.php is loaded
echo "<h3>3. Checking community_functions.php</h3>";
if (file_exists('assets/includes/community_functions.php')) {
    echo "✅ community_functions.php file EXISTS<br>";
} else {
    echo "❌ community_functions.php file MISSING<br>";
}

// 4. Check if there are any communities to test with
echo "<h3>4. Available Communities</h3>";
$communities = mysqli_query($sqlConnect, "SELECT * FROM Wo_Communities LIMIT 5");
if (mysqli_num_rows($communities) > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Privacy</th><th>Test Link</th></tr>";
    while ($comm = mysqli_fetch_assoc($communities)) {
        $privacy = ($comm['privacy'] == 1 ? 'Public' : 'Private');
        echo "<tr>";
        echo "<td>" . $comm['id'] . "</td>";
        echo "<td>" . $comm['community_title'] . "</td>";
        echo "<td>" . $privacy . "</td>";
        echo "<td><a href='index.php?link1=community&c=" . $comm['community_name'] . "' target='_blank'>View Community</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<strong style='color:red;'>❌ NO COMMUNITIES FOUND!</strong><br>";
    echo "You need to create a community first: <a href='index.php?link1=create-community'>Create Community</a>";
}

// 5. Check the content.phtml file
echo "<h3>5. Checking Community Content Template</h3>";
$content_file = 'themes/facesofnaija/layout/community/content.phtml';
if (file_exists($content_file)) {
    $content = file_get_contents($content_file);
    if (strpos($content, 'Wo_GetCommunityJoinButton') !== false) {
        echo "✅ content.phtml contains Wo_GetCommunityJoinButton() call<br>";
    } else {
        echo "❌ content.phtml DOES NOT contain Wo_GetCommunityJoinButton() call<br>";
    }
    
    // Check if it's commented out
    if (strpos($content, '//echo Wo_GetCommunityJoinButton') !== false) {
        echo "⚠️ WARNING: The function call is COMMENTED OUT!<br>";
    }
}

echo "<br><hr><br>";
echo "<strong>Next Steps:</strong><br>";
echo "1. Create a community if you haven't already<br>";
echo "2. Clear browser cache (Ctrl+Shift+Delete)<br>";
echo "3. Visit a community page<br>";
echo "4. Look for the Join button at the top<br>";
?>
