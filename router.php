<?php

chdir($_SERVER['DOCUMENT_ROOT']);
if (preg_match('#^/api/rest#', $_SERVER["REQUEST_URI"])) {
    $_SERVER["REQUEST_URI"] = 'api.php?type=rest';
} elseif (preg_match('#^/(media|skin|js)(?:$|/)#', $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH))) {
    return false;
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
    return false;
} else {
    require 'index.php';
}
