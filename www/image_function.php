<?php
if(!defined('_MYCDN_')) error_404();

$imagepath = __DIR__;
$allowed_image_ext = array('png', 'jpg', 'jpeg','gif');

function save_image_and_display($target_host, $target_path) {
	global $imagepath, $allowed_image_ext;

	$target_url = $target_host . $target_path;

	$agent= 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.140 Safari/537.36 Edge/17.0';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_VERBOSE, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_URL, $target_url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
	$result=curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($result && $httpcode == 200) {
		// security check1
		$local_path = pathinfo($target_path, PATHINFO_DIRNAME);
		$local_filename = basename($target_path);
		$local_filename_ext = pathinfo($local_filename, PATHINFO_EXTENSION);
		if (!in_array($local_filename_ext, $allowed_image_ext)) die('error');

		$local_fullpath = $imagepath . $local_path . '/' . $local_filename;

		// create folder if not exist
		if (!file_exists($imagepath . $local_path)) exec('mkdir -p ' . $imagepath . $local_path);
		file_put_contents($local_fullpath, $result);

		// security check2
		if (exif_imagetype($local_fullpath) === FALSE) {
			// if not an image -> overwrite
			copy("{$imagepath}/1x1_blank.png", $local_fullpath);

			// read again
			$result = file_get_contents($local_fullpath);
		}

		// display
		header('my-cache-status: MISS');
		header('Content-Type: image/png');
		echo $result;
	}
	else {
		error_404();
	}

	exit;
}

function error_404() {
	header('my-cache-status: MISS');
	header("HTTP/1.1 404 Not Found");
	exit;
}
