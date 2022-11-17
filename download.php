<?php

$file = $_POST['filepath'];

if (file_exists($file)) {
    header('Content-Type: application/actet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Content-Length:' . filesize($file));
    readfile($file);
    die;
} else {
    header('Location: /paskaitos/forms/16p_pvz/index.php?message=File does not exist.');
}


