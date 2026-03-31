<?php
chdir('/var/www/html');
require_once 'assets/init.php';
$q = mysqli_query($sqlConnect, "SELECT session_id FROM Wo_Appssessions WHERE user_id=330 ORDER BY id DESC LIMIT 1");
$r = mysqli_fetch_assoc($q);
echo $r['session_id'];
