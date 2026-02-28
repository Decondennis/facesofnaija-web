<?php
// Serve SVG directly with correct headers
$file = 'upload/files/2022/09/iZcVfFlay3gkABhEhtVC_01_771d67d0b8ae8720f7775be3a0cfb51a_file.svg';

if (file_exists($file)) {
    header('Content-Type: image/svg+xml');
    header('Content-Length: ' . filesize($file));
    header('Cache-Control: no-cache');
    readfile($file);
    exit;
} else {
    header('HTTP/1.0 404 Not Found');
    echo 'SVG file not found';
}
?>
