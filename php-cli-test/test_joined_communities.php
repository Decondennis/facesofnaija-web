<?php
chdir(__DIR__ . '/..');
require 'assets/init.php';

// Ensure we have a user context when running from CLI
if (!isset($wo['user']) || empty($wo['user'])) {
    global $sqlConnect;
    $u = mysqli_query($sqlConnect, "SELECT `user_id` FROM Wo_Users LIMIT 1");
    if ($u && mysqli_num_rows($u)) {
        $uu = mysqli_fetch_assoc($u);
        $wo['user'] = array('user_id' => $uu['user_id']);
        $wo['loggedin'] = true;
    } else {
        echo "No users found in DB\n";
        exit(1);
    }
}

$user_id = $wo['user']['user_id'];

echo "Using user_id={$user_id}\n";

$pages = Wo_GetMyCommunities();
echo "Wo_GetMyCommunities() returned count=" . count($pages) . "\n";
if (count($pages) > 0) {
    echo "First community id (from Wo_CommunityData) = " . $pages[0]['community_id'] . "\n";
}

// Run the raw query used by Wo_GetMyCommunities() to capture SQL errors and exact rows
global $sqlConnect;
$raw_q = "SELECT `id` FROM Wo_Communities WHERE `id` IN (SELECT `community_id` FROM Wo_Community_Members WHERE `user_id` = {$user_id})";
$res = mysqli_query($sqlConnect, $raw_q);
if ($res === false) {
    echo "Raw query failed: " . mysqli_error($sqlConnect) . "\n";
} else {
    $rows = mysqli_num_rows($res);
    echo "Raw query rows=" . $rows . "\n";
    if ($rows > 0) {
        echo "Community ids: ";
        $ids = array();
        while ($r = mysqli_fetch_assoc($res)) {
            $ids[] = $r['id'];
        }
        echo implode(',', $ids) . "\n";
    }
}

// Extra diagnostics: count active communities and membership rows
$res2 = mysqli_query($sqlConnect, "SELECT COUNT(*) AS c FROM Wo_Communities WHERE active = '1'");
if ($res2) {
    $r2 = mysqli_fetch_assoc($res2);
    echo "total_active_communities=" . $r2['c'] . "\n";
}
$res3 = mysqli_query($sqlConnect, "SELECT COUNT(*) AS c FROM Wo_Community_Members WHERE user_id = {$user_id}");
if ($res3) {
    $r3 = mysqli_fetch_assoc($res3);
    echo "user_member_rows=" . $r3['c'] . "\n";
}

?>
