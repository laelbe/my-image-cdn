<?php
define('_MYCDN_', true);
require_once 'image_function.php';

$_SERVER['REQUEST_URI'] = preg_replace('/\/\/+/', '/', $_SERVER['REQUEST_URI']);
$_SERVER['REQUEST_URI'] = preg_replace('/\.\.+/', '.', $_SERVER['REQUEST_URI']);

$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url_ext = pathinfo($url_path, PATHINFO_EXTENSION);

if (!in_array($url_ext, $allowed_image_ext)) error_404(); // extension check

$target_host = 'https://blog.lael.be';
$target_path = $url_path;

save_image_and_display($target_host, $target_path);