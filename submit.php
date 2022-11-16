<?php
// Paleidžiam funkciją, kuri patikrina ar neviršytas POST limitas.
postErrHandler();
// Sudedam į kintamuosius informaciją apie įkeliamą failą.
$error = $_FILES['my_file']['error'];
$fileSize = $_FILES['my_file']['size'];
$fileType = $_FILES['my_file']['type'];
$uploadFileName = $_FILES['my_file']['name'];
$fileSavePath = './data/' . uniqid() . '_' . $uploadFileName;
$tempFilePath = $_FILES['my_file']['tmp_name'];
// Apsirašom įkeliamo failo limitus: leidžiami failo tipai ir max dydis 1MB.
const FILETYPES_ALLOWED = array('JPEG', 'PNG', 'JPG');
const MAX_FILESIZE_ALLOWED = 1048576;
unset($_GET['message']);

// Patikrinam ar įkėlimo metu neįvyko klaidų ir ar atitinka failo tipo ir dydžio limitus.
if ($error !== UPLOAD_ERR_OK || !in_array(pathinfo($uploadFileName, PATHINFO_EXTENSION), FILETYPES_ALLOWED) || $fileSize > MAX_FILESIZE_ALLOWED ) {
//  Užsetinam $_GET['message], kad galėtume index.php atvaizduoti žinutę.
    header('Location: /paskaitos/forms/16p_pvz/index.php?message=Error uploading file. Only *.png, *.jpg files below 1MB allowed.');
    die();
} else {
// Jei klaidų nėra, įkeliam failus, padarom įrašą su failo metadata.
    move_uploaded_file($tempFilePath, $fileSavePath);
    writeMeta($uploadFileName, $fileSize, $fileSavePath);
    header('Location: /paskaitos/forms/16p_pvz/index.php?message=File successfully uploaded.');
}

function writeMeta($filename, $filesize, $filesavepath): void
{
    $metadata = json_decode(file_get_contents('./data/metadata.json'), true);
    if (empty($metadata)) {
        $metadata = [];
    }
    $metadata[] = [
        'filename' => $filename,
        'filesize' => $filesize,
        'filepath' => $filesavepath,
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


