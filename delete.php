<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete</title>
</head>
<?php
$fileList = json_decode(file_get_contents('./data/metadata.json'), true);
$index = intval($_POST['id']);
?>
<body>
<?php if (isset($fileList[$index])): ?>
    <?php $fileToDelete = $fileList[$index]['filepath'];
    unlink($fileToDelete);
    unset($fileList[$index]);
    file_put_contents('./data/metadata.json', json_encode(array_values($fileList), JSON_PRETTY_PRINT));
    ?>
    <h3>File successfully deleted.</h3>
<?php else: ?>
    <h2>File does not exist.</h2>
<?php endif ?>
<script>
    setTimeout(() => {
        window.location = './index.php'
    }, 2000)
</script>
</body>
</html>