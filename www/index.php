<?php
/**
 * You don't need to change this file.
 */
define('_MYCDN_', true);
require_once __DIR__ . '/cache_function.php';
require_once __DIR__ . '/config.php';

$_SERVER['REQUEST_URI'] = preg_replace('/\/\/+/', '/', $_SERVER['REQUEST_URI']);
$_SERVER['REQUEST_URI'] = preg_replace('/\.\.+/', '.', $_SERVER['REQUEST_URI']);

$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url_ext = pathinfo($url_path, PATHINFO_EXTENSION);

if (!in_array(strtolower($url_ext), $allowed_extension)) { // extension check
    error_404();
}

save_and_display_file($original_host, $url_path);
