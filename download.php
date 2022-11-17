<?php

$file = $_POST['filepath'];

if (file_exists($file)) {
    header('Content-Type: application/actet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Content-Length:' . filesize($file));
    readfile($file);
    die;
} else {
    die('File does not exist.');
}


