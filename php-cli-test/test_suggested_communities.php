<?php
chdir(__DIR__ . '/..');
require 'assets/init.php';
// If CLI has no session, load a user from DB for testing
if (!isset($wo['user'])) {
    global $sqlConnect;
    $u = mysqli_query($sqlConnect, "SELECT `user_id` FROM Wo_Users LIMIT 1");
    if ($u && mysqli_num_rows($u)) {
        $uu = mysqli_fetch_assoc($u);
        $wo['user'] = array('user_id' => $uu['user_id']);
        $wo['loggedin'] = true;
    } else {
        echo "No users found in DB\n";
        exit;
    }
}
$pages = Wo_CommunitySug(50);
echo "count=" . count($pages) . "\n";
if (count($pages) > 0) {
    echo "first id=" . $pages[0]['community_id'] . "\n";
}
// extra debug: show total communities and membership rows for test user
$res = mysqli_query($sqlConnect, "SELECT COUNT(*) AS c FROM Wo_Communities WHERE active = '1'");
$r = mysqli_fetch_assoc($res);
echo "total_active_communities=" . $r['c'] . "\n";
$user_id = $wo['user']['user_id'];
$res2 = mysqli_query($sqlConnect, "SELECT COUNT(*) AS c FROM Wo_Community_Members WHERE user_id = {$user_id}");
$r2 = mysqli_fetch_assoc($res2);
echo "user_member_rows=" . $r2['c'] . "\n";
?>