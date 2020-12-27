<?php
include ('db.php');
include ('ThumbGenerator.php');

if (php_sapi_name() == "cli") {
    $name = @$argv[1] ?? null;
    $size = @$argv[2] ?? null;
}
else { // при вызове через GET/POST запрос
    $name = @$_REQUEST['name'] ?? null;
    $size = @$_REQUEST['size'] ?? null;
}

if (!$name || !$size) {
    return 'no image data';
}

$fileName = 'cache'.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR.$size.DIRECTORY_SEPARATOR.$name.'.jpg';
if (!file_exists($fileName)) {
    ThumbGenerator::generate($name);
}

if (file_exists($fileName)) {
    echo file_get_contents($fileName);
} else {
    echo 'no image';
}