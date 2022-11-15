<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit</title>
</head>
<body>
<?php
echo '<pre>';
postErrHandler();

$error = $_FILES['my_file']['error'];
$fileSize = $_FILES['my_file']['size'];
$fileType = $_FILES['my_file']['type'];
$uploadFileName = $_FILES['my_file']['name'];
$fileSavePath = './data/' . uniqid() . '_' . $uploadFileName;
$tempFilePath = $_FILES['my_file']['tmp_name'];
$allowedTypes = array('JPEG', 'PNG', 'JPG');


if ($error !== UPLOAD_ERR_OK || !in_array(pathinfo($uploadFileName, PATHINFO_EXTENSION), $allowedTypes) || $fileSize > 1048576) {
    echo 'Error uploading file';
    die();
} else {
    move_uploaded_file($tempFilePath, $fileSavePath);
    writeMeta($uploadFileName, $fileSize, $fileSavePath);
    echo '<h2>File successfully uploaded. You will be automatically redirected to <i>index page</i>.</h2>';
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

function postErrHandler(): void
{
    if (isset($_SERVER['CONTENT_LENGTH'])) {
        if ($_SERVER['CONTENT_LENGTH'] > ((int)ini_get('post_max_size') * 1024 * 1024)) {
            echo '<h2>Upload is bigger than ' . ini_get('upload_max_filesize') . '</h2>';
            die('<script type="text/javascript">
                    setTimeout(()=>{window.location = "./index.php" },2000)
                 </script>');
        }
    }
}

?>
<script>
    setTimeout(() => {
        window.location = './index.php'
    }, 2000)
</script>
</body>
</html>

