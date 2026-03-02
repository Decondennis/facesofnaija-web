<?php
require_once(__DIR__ . '/../assets/init.php');
header('Content-Type: application/json');
$name = '';
if (!empty($_GET['name'])) {
    $name = Wo_Secure($_GET['name']);
}
if (empty($name)) {
    echo json_encode(array('error' => 'missing_name'));
    exit();
}
$query = mysqli_query($sqlConnect, "SELECT * FROM " . T_COMMUNITIES . " WHERE community_name = '{$name}' LIMIT 1");
if ($query && mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    echo json_encode(array('found' => true, 'community' => $row));
} else {
    echo json_encode(array('found' => false));
}
