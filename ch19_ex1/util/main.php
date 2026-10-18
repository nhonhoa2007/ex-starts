<?php
$doc_root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/..';
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$dirs = explode('/', parse_url($uri, PHP_URL_PATH));
if (count($dirs) >= 3 && !empty($dirs[1]) && !empty($dirs[2]) && !str_ends_with($dirs[2], '.php')) {
    $app_path = '/' . $dirs[1] . '/' . $dirs[2] . '/';
} else if (count($dirs) >= 2 && !empty($dirs[1]) && !str_ends_with($dirs[1], '.php')) {
    $app_path = '/' . $dirs[1] . '/';
} else {
    $app_path = '/';
}
set_include_path(rtrim($doc_root, '/') . $app_path . PATH_SEPARATOR . get_include_path() . PATH_SEPARATOR . dirname(__DIR__));
?>

