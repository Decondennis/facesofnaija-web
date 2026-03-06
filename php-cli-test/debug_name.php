<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    header('Content-Type: application/json');
    echo json_encode(['php_error' => "$errstr in $errfile on line $errline"], JSON_PRETTY_PRINT);
    exit;
});
set_exception_handler(function($e) {
    header('Content-Type: application/json');
    echo json_encode(['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()], JSON_PRETTY_PRINT);
    exit;
});

header('Content-Type: application/json');
$u = isset($_GET['u']) ? $_GET['u'] : 'eddacon';
$result = ['checked_name' => $u];

// include safely to capture include failures
$included = @include __DIR__ . '/../assets/init.php';
if ($included === false) {
    $result['include_init'] = 'failed';
    echo json_encode($result, JSON_PRETTY_PRINT);
    exit;
}

if (!function_exists('Wo_IsNameExist')) {
    $result['error'] = 'Wo_IsNameExist not defined after init';
    echo json_encode($result, JSON_PRETTY_PRINT);
    exit;
}

try {
    $check = Wo_IsNameExist($u, 1);
    $result['Wo_IsNameExist'] = $check;
    global $sqlConnect;
    $result['mysqli_error'] = mysqli_error($sqlConnect);
} catch (Throwable $t) {
    $result['exception'] = $t->getMessage();
}

echo json_encode($result, JSON_PRETTY_PRINT);
