<?php
const INDEX_PATH = '/paskaitos/forms/16p_pvz/index.php';

$fileData = json_decode(file_get_contents('./data/metadata.json'), true);
$index = intval($_POST['id']);


if (isset($fileData[$index])) {
    $message = null;
    if(deleteFile($fileData, $index)) {
        $message = 'File deleted.';
    }
} else {
    $message = 'File does not exist.';
}
setHeader($message);


// FUNKCIJOS ------------------------------------------------------------------------------------

function deleteFile(array $metadata, int $i): bool
{
    if (unlink($metadata[$i]['filepath'])) {
        unset($metadata[$i]);
        file_put_contents('./data/metadata.json', json_encode(array_values($metadata), JSON_PRETTY_PRINT));
        return true;
    } else {
        return false;
    }
}

function setHeader(string $msg, string $path = INDEX_PATH): void
{
    header('Location: ' . $path . '?message=' . $msg);
}

