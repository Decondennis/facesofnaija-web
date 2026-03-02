<?php
// Quick diagnostics for theme CSS
$path = __DIR__ . '/../themes/facesofnaija/stylesheet/style.css';
header('Content-Type: text/plain; charset=utf-8');
if (!file_exists($path)) {
    echo "MISSING: $path\n";
    exit(1);
}
$size = filesize($path);
echo "PATH: $path\n";
echo "SIZE: $size\n";
echo "MOD: " . date('c', filemtime($path)) . "\n";
echo "----BEGIN----\n";
$fh = fopen($path, 'r');
if ($fh) {
    echo fread($fh, 10240);
    fclose($fh);
} else {
    echo "FAILED_TO_OPEN\n";
}
echo "\n----END----\n";
?>