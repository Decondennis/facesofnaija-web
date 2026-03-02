<?php
chdir(__DIR__ . '/..');
require 'assets/init.php';
// ensure user for CLI
if (!isset($wo['user']) || empty($wo['user'])) {
    global $sqlConnect;
    $u = mysqli_query($sqlConnect, "SELECT `user_id` FROM Wo_Users LIMIT 1");
    if ($u && mysqli_num_rows($u)) {
        $uu = mysqli_fetch_assoc($u);
        $wo['user'] = Wo_UserData($uu['user_id']);
        $wo['loggedin'] = true;
    } else {
        echo "No users\n"; exit(1);
    }
}
$pages = Wo_GetMyCommunities();
echo "pages_count=" . count($pages) . "\n";
foreach ($pages as $i => $c) {
    $wo['community'] = $c;
    $out = Wo_LoadPage('community/community-list');
    echo "community[{$i}] id=" . $c['community_id'] . " rendered_length=" . strlen($out) . "\n";
    if (strlen($out) < 10) {
        echo "--- OUTPUT START ---\n" . $out . "\n--- OUTPUT END ---\n";
    }
}

?>
