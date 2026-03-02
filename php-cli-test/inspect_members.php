<?php
require_once(dirname(__DIR__) . '/assets/init.php');

$user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 330;
$sql = '';
echo "<pre>Inspecting community membership rows for user_id={$user_id}\n\n";

$tables = array(T_COMMUNITY_MEMBERS, str_replace('_','',T_COMMUNITY_MEMBERS), 'Wo_CommunityMembers');
$checked = array();
foreach ($tables as $tbl) {
    if (in_array($tbl,$checked)) continue;
    $checked[] = $tbl;
    $q = "SELECT * FROM `{$tbl}` WHERE `user_id` = {$user_id}";
    $res = @mysqli_query($sqlConnect, $q);
    if ($res === false) {
        echo "Table {$tbl}: ERROR - " . mysqli_error($sqlConnect) . "\n\n";
        continue;
    }
    $count = mysqli_num_rows($res);
    echo "Table {$tbl}: found {$count} rows\n";
    if ($count > 0) {
        while ($r = mysqli_fetch_assoc($res)) {
            print_r($r);
        }
        echo "\n";
    }
}

// Also try to join community titles
$q2 = "SELECT c.id,c.community_title,cm.* FROM `" . T_COMMUNITIES . "` c INNER JOIN `" . T_COMMUNITY_MEMBERS . "` cm ON c.id = cm.community_id WHERE cm.user_id = {$user_id}";
$r2 = @mysqli_query($sqlConnect, $q2);
if ($r2 !== false) {
    echo "Joined via " . T_COMMUNITY_MEMBERS . ": " . mysqli_num_rows($r2) . " rows\n";
    while ($row = mysqli_fetch_assoc($r2)) print_r($row);
} else {
    echo "Join-lookup using " . T_COMMUNITY_MEMBERS . " returned ERROR: " . mysqli_error($sqlConnect) . "\n";
}

echo "</pre>";
