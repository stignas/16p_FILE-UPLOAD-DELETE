<?php

$file = $_POST['filepath'];

header('Content-Type: application/actet-stream');
header('Content-Disposition: attachment; filename="'.basename($file) . '"');
header('Content-Length:'.filesize($file));
readfile($file);


