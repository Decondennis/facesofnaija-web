<?php
$hash = password_hash('Test1234x', PASSWORD_BCRYPT);
echo $hash . "\n";
$db = new mysqli('localhost','facesofnaija_user','FacesDB_2026!','facesofnaija');
$db->query("UPDATE wo_users SET password='$hash' WHERE user_id=1");
echo "Done. Rows: " . $db->affected_rows . "\n";
