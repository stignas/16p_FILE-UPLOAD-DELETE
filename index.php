<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File upload, list, delete</title>
</head>
<!-- Nuskaitom duomenų failą į duomenų masyvą -->
<?php $metadatafile = json_decode(file_get_contents('./data/metadata.json'), true); ?>
<!-- Pastilizuojam failų sąrašo lentelę-->
<style>
    table, tr, td, th {
        text-align: center;
        border: 1px solid black;
        padding: 0.5rem;
        border-collapse: collapse;
    }
</style>

<body>
<!-- Sukuriam formą failų įkėlimui-->
<form
        action="submit.php"
        method="POST"
        enctype="multipart/form-data"
>
    <input type="file" name="my_file">
    <button type="submit">Upload</button>
</form>
<hr>
<!-- Atvaizduojam gautą pranešimą iš submit.php ar delete.php-->
<?php if (isset($_GET['message'])): ?>
    <h3> <?= $_GET['message'] ?></h3>
<?php endif;
unset($_GET['message']); ?>
<!-- Atvaizduojam įkeltų failų sąrašą.-->
<table>
    <tr>
        <th>Original file name</th>
        <th>File Size, KB</th>
        <th>Uploaded At:</th>
        <th>Delete File</th>
    </tr>
    <?php if (!empty($metadatafile)): ?> <!-- Patikrinam ar failas nėra tuščias   -->
        <?php foreach ($metadatafile as $index => $fileInfo): ?>  <!--  Nuskaitom kiekvieną įrašą iš failo ir sukuriam kiekvienam įrašui eilutę lentelėje -->
            <tr>
                <td><?= $fileInfo['filename'] ?></td>  <!-- Atvaizduojam originalų failo pavadinimą   -->
                <td><?= round($fileInfo['filesize'] / 1024, 2) ?></td> <!-- Failo dydį suapvalinę atvaizduojame KB  -->
                <td><?= $fileInfo['uploadedAt'] ?></td> <!--  Atvaizduojam įkėlimo datą ir laiką  -->
                <td>
                    <!--  Sukuriam formą ir mygtuką failo ištrynimui  -->
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

