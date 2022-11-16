<?php
$fileList = json_decode(file_get_contents('./data/metadata.json'), true);
$index = intval($_POST['id']);
unset($_GET['message']);

// patikrinam ar failas egzistuoja ir ji ištrinam iš saugyklos ir metadata failo.
// nusiunčiam žinutę į index.php kaip pavyko
if (isset($fileList[$index])) {
    $fileToDelete = $fileList[$index]['filepath'];
    unlink($fileToDelete);
    unset($fileList[$index]);
    file_put_contents('./data/metadata.json', json_encode(array_values($fileList), JSON_PRETTY_PRINT));
    header('Location: /paskaitos/forms/16p_pvz/index.php?message=File successfully deleted.');
} else {
    header('Location: /paskaitos/forms/16p_pvz/index.php?message=File does not exist.');
}

