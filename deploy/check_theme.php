<?php
$conn = new mysqli('localhost', 'facesofnaija_user', 'FacesDB_2026!', 'facesofnaija');
if ($conn->connect_error) { die("DB error: " . $conn->connect_error); }
$r = $conn->query("SELECT name, value FROM wo_config WHERE name IN ('theme','site_url','maintenance_mode') LIMIT 10");
while ($row = $r->fetch_assoc()) {
    echo $row['name'] . " = " . $row['value'] . "\n";
}
// Check theme directory
$theme_result = $conn->query("SELECT value FROM wo_config WHERE name='theme' LIMIT 1");
$theme = $theme_result->fetch_assoc()['value'];
echo "\nTheme: $theme\n";
echo "container.phtml exists: " . (file_exists("/var/www/html/facesofnaija/themes/$theme/layout/container.phtml") ? "YES" : "NO") . "\n";
echo "welcome/content.phtml exists: " . (file_exists("/var/www/html/facesofnaija/themes/$theme/layout/welcome/content.phtml") ? "YES" : "NO") . "\n";
echo "\nTheme dir listing:\n";
$dirs = glob("/var/www/html/facesofnaija/themes/$theme/layout/*", GLOB_ONLYDIR);
foreach ($dirs as $d) { echo "  $d\n"; }
