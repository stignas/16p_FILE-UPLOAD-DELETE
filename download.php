<?php
const INDEX_PATH = '/paskaitos/forms/16p_pvz/index.php';
$message = null;

if (isset($_POST['filepath'])) {
    $file = $_POST['filepath'];
    if (file_exists($file)) {
        downloadFile($file);
    } else {
        $message = 'File does not exist.';
    }
} else {
    $message = 'Filename is not defined.';
}

if (isset($message)) {
    setHeader($message);
}


// FUNKCIJOS -------------------------------

function downloadFile($filepath): void
{
    header('Content-Type: application/actet-stream');
    header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
    header('Content-Length:' . filesize($filepath));
    readfile($filepath);
}

function setHeader(string $msg, string $path = INDEX_PATH): void
{
    header('Location: ' . $path . '?message=' . $msg);
}
