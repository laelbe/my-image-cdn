<?php
/**
 * You don't need to change this file.
 */
if (!defined('_MYCDN_')) {
    error_404();
}

function save_and_display_file($target_host, $target_path)
{
    global $allowed_extension, $custom_headers, $mycdn_agentname;
	if (!is_array($allowed_extension)) $allowed_extension = [];
	if (!is_array($custom_headers)) $custom_headers = [];
	if (empty($mycdn_agentname)) $mycdn_agentname = 'MYCDN/1.0';

    $current_path = __DIR__;

    $local_path = pathinfo($target_path, PATHINFO_DIRNAME);
    $local_filename = basename($target_path);
    $local_filename_ext = pathinfo($local_filename, PATHINFO_EXTENSION);
    if (!in_array(strtolower($local_filename_ext), $allowed_extension)) {
        die('error'); // extension check
    }

    $target_url = $target_host . $target_path;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $custom_headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $request_agent);
    curl_setopt($ch, CURLOPT_URL, $target_url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    $result=curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // print_r(curl_getinfo($ch)); exit(); // if you want to debug the request, use this line.
    curl_close($ch);

    if ($result && $httpcode == 200) {
        $local_fullpath = "{$current_path}{$local_path}/{$local_filename}";

        // create folder if not exist
        if (!file_exists($current_path . $local_path)) {
            exec("mkdir -p {$current_path}{$local_path}");
        }
        file_put_contents($local_fullpath, $result);
        $content_type = mime_content_type($local_fullpath);
        $content_length = filesize($local_fullpath);

        // display
        header('my-cache-status: MISS');
        header("content-type: {$content_type}");
        header("content-length: {$content_length}");
        echo $result;
    } else {
        error_404();
    }

    exit;
}

function error_404()
{
    header('my-cache-status: MISS');
    header("HTTP/1.1 404 Not Found");
    echo '404 Not Found';
    exit;
}
