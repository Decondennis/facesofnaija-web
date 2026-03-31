<?php
// Get a valid session hash from wo_appssessions
$db = new mysqli('localhost','facesofnaija_user','FacesDB_2026!','facesofnaija');
$r = $db->query("SELECT session_id, user_id FROM wo_appssessions ORDER BY time DESC LIMIT 5");
echo "=== Recent sessions ===\n";
while($row = $r->fetch_assoc()) {
    echo "user_id=".$row['user_id']." session=".$row['session_id']."\n";
}

// Check total sessions
$c = $db->query("SELECT COUNT(*) as n FROM wo_appssessions WHERE platform='web'")->fetch_assoc();
echo "Total web sessions: ".$c['n']."\n";
