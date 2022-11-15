<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<?php $metadatafile = json_decode(file_get_contents('./data/metadata.json'), true); ?>
<style>
    table, tr, td, th {
        text-align: center;
        border: 1px solid black;
        padding: 0.5rem;
        border-collapse: collapse;
    }
</style>
<body>
<form
        action="submit.php"
        method="POST"
        enctype="multipart/form-data"
>
    <input type="file" name="my_file">
    <button type="submit">Upload</button>
</form>

<hr>
<table>
    <tr>
        <th>Original file name</th>
        <th>File Size, KB</th>
        <th>Uploaded At:</th>
        <th>Delete File</th>

    </tr>
    <?php if (!empty($metadatafile)): ?>
        <?php foreach ($metadatafile as $index => $fileInfo): ?>
            <tr>
                <td><?= $fileInfo['filename'] ?></td>
                <td><?= round($fileInfo['filesize']/1024,2) ?></td>
                <td><?= $fileInfo['uploadedAt'] ?></td>
                <td>
                    <form action="delete.php" method="post">
                        <button type="submit"><input type="hidden" name="id" value="<?= $index ?>">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif ?>
</table>
</body>
</html>
