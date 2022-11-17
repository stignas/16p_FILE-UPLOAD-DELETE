<?php

const FILETYPES_ALLOWED = array('JPEG', 'PNG', 'JPG');
const MAX_FILESIZE_ALLOWED = 1048576;
const INDEX_PATH = '/paskaitos/forms/16p_pvz/index.php';

$fileSavePath = './data/' . uniqid() . '_' . $_FILES['my_file']['name'];
$tempFilePath = $_FILES['my_file']['tmp_name'];

// Paleidžiam klaidų tikrinimo funkcijas.
postErrHandler();
$errors = errorHandling($_FILES);

// Jei yra klaidų, paruošiam žinutę.
if (!empty($errors)) {
    $message = null;
    foreach ($errors as $error) {
        $message .= $error . " ";
    }
    if (isset($message)) {
        setHeader($message);
    }
    die;
}


if (move_uploaded_file($tempFilePath, $fileSavePath)) {
    writeMeta($_FILES, $fileSavePath);
    $message = 'File successfully uploaded.';
}
else{
    $message = "Unknown error occured";
}
setHeader($message);


// FUNKCIJOS ------------------------------------------------------------

function errorHandling(array $file, array $allowedTypes = FILETYPES_ALLOWED): array
{
    $errorMessages = [];
    if ($file['my_file']['error'] !== UPLOAD_ERR_OK) {
        $errorMessages[] = 'Error occured: ' . $file["my_file"]["error"];
//        header('Location: /paskaitos/forms/16p_pvz/index.php?message=Error occured: ' . $file["my_file"]["error"]);
    }
    if (!in_array(pathinfo($file['my_file']['name'], PATHINFO_EXTENSION), $allowedTypes) || $file['my_file']['size'] > MAX_FILESIZE_ALLOWED) {
        $errorMessages[] = 'Only *.png, *.jpg files below 1MB allowed.';
//        header('Location: /paskaitos/forms/16p_pvz/index.php?message=Only *.png, *.jpg files below 1MB allowed.');
    }

    return $errorMessages;
}

function setHeader(string $msg, string $path=INDEX_PATH): void
{
    header('Location: '.$path. '?message=' . $msg);
}

function writeMeta(array $file, string $path): void
{
    $metadata = json_decode(file_get_contents('./data/metadata.json'), true);
    if (empty($metadata)) {
        $metadata = [];
    }
    $metadata[] = [
        'filename' => $file['my_file']['name'],
        'filesize' => $file['my_file']['size'],
        'filepath' => $path,
        'uploadedAt' => date('Y-m-d H:i:s'),
    ];
    file_put_contents('./data/metadata.json', json_encode($metadata, JSON_PRETTY_PRINT));
}

// Funkcija POST klaidos handlinimui, jeigu bandome ikelti didesni faila negu PHP serverio nustatymuose
function postErrHandler(): void
{
    if (isset($_SERVER['CONTENT_LENGTH'])) {
        if ($_SERVER['CONTENT_LENGTH'] > ((int)ini_get('post_max_size') * 1024 * 1024)) {
            header('Location: /paskaitos/forms/16p_pvz/index.php?message=Error uploading file. Only *.png, *.jpg files below 1MB allowed.');
            die;
        }
    }
}
