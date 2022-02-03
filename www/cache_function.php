<?php
/**
 * You don't need to change this file.
 */
if (!defined('_MYCDN_')) {
    error_404();
}

function save_and_display_file($target_host, $target_path)
{
    global $allowed_extension, $custom_headers, $mycdn_agentname, $resize_imagecode; // config variable
	if (!is_array($allowed_extension)) $allowed_extension = [];
	if (!is_array($custom_headers)) $custom_headers = [];
	if (empty($mycdn_agentname)) $mycdn_agentname = 'MYCDN/1.0';
	if (!is_array($resize_imagecode)) $resize_imagecode = [];
	$do_resize = FALSE;
	$do_resizeinfo = [];

    $current_path = __DIR__;

// 	var_dump($target_path);exit;
	$target_path_token = explode('/', $target_path);
	if (count($target_path_token) > 3 && isset($resize_imagecode[$target_path_token[1]])) {
		$do_resize = TRUE;
		$do_resizecode = $target_path_token[1];

		array_shift($target_path_token);
		array_shift($target_path_token);
		array_unshift($target_path_token, '');

		$target_path = implode('/', $target_path_token);
	}

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
	    driver_file_save($result, $current_path, $local_path, $local_filename, $do_resize, $do_resizecode);
    } else {
        error_404();
    }

    exit;
}

function error_404($str = '404 Not Found')
{
    header('my-cache-status: MISS');
    header("HTTP/1.1 404 Not Found");
    echo $str;
    exit;
}

function driver_file_save($result, $current_path, $local_path, $local_filename, $do_resize, $do_resizecode) {
    $local_fullpath = "{$current_path}{$local_path}/{$local_filename}";

    // create folder if not exist
    if (!file_exists($current_path . $local_path)) {
        exec("mkdir -p {$current_path}{$local_path}");
    }
    file_put_contents($local_fullpath, $result);


	if ($do_resize === TRUE) {
		$quality = 80;
		$dst_w = 100;
		$dst_h = 100;
		$image_extension = 'png';
		
		$result = compress($local_fullpath, $quality, $dst_w, $dst_h = -1, $image_extension);
		
		$local_fullpath = "{$current_path}/$do_resizecode/{$local_path}/{$local_filename}";
	    if (!file_exists("{$current_path}/$do_resizecode/{$local_path}")) {
	        exec("mkdir -p {$current_path}/$do_resizecode/{$local_path}");
	    }
	    file_put_contents($local_fullpath, $result);
	}

	// print_r("{$local_path}/{$local_filename}");

	// var_dump($do_resize);
	// var_dump($do_resizecode);

	// echo "d";
	// exit;

    $content_type = mime_content_type($local_fullpath);
    $content_length = filesize($local_fullpath);

    // display
    header('my-cache-status: MISS');
    header("content-type: {$content_type}");
    header("content-length: {$content_length}");
    echo $result;

}




function compress($source, $quality, $dst_w, $dst_h = -1, $image_extension)
{
    if ($dst_h == -1) {
        $crop = false; // fail safe
    } else {
        $crop = true;
    }

    $info = getimagesize($source);
//     print_r($source);
//     print_r($info);

    $width = $info[0];
    $height = $info[1];
    $ratio = $width / $height;

    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/gif') { // we do not resize gif format.
        $dat = file_get_contents($source);
        return $dat;
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
        imagesavealpha($image, true);
    } else {
        error_404('Invalid Imageformat2.');
    }

    // add orientation handle, jpg, png
    $exif = exif_read_data($source);
    if (!empty($exif['Orientation'])) {
        switch ($exif['Orientation']) {
            case 3:
                $image = imagerotate($image, 180, 0);
                break;

            case 6:
                $image = imagerotate($image, -90, 0);
                $width = $info[1];
                $height = $info[0];
                $ratio = $width / $height;

                break;

            case 8:
                $image = imagerotate($image, 90, 0);
                $width = $info[1];
                $height = $info[0];
                $ratio = $width / $height;

                break;
        }
    }

    if ($dst_w == -1 || $width < $dst_w) { // original 이거나 너무 작으면 -> resize skip

    } else { // try to resize
        if ($dst_h == -1) { // width 기준 resize
            $newwidth = $dst_w;
            $newheight = $dst_w / $ratio;
        } else {
            if ($crop) {
                if ($width > $height) {
                    $width = ceil($width-($width*abs($ratio-$dst_w/$dst_h)));
                } else {
                    $height = ceil($height-($height*abs($ratio-$dst_w/$dst_h)));
                }
                $newwidth = $dst_w;
                $newheight = $dst_h;
            } else {
                if ($dst_w/$dst_h > $ratio) {
                    $newwidth = $dst_h*$ratio;
                    $newheight = $dst_h;
                } else {
                    $newheight = $dst_w/$ratio;
                    $newwidth = $dst_w;
                }
            }
        }

        $result = imagecreatetruecolor($newwidth, $newheight);

        imagealphablending($result, false);
        $transparency = imagecolorallocatealpha($result, 0, 0, 0, 127);
        imagefill($result, 0, 0, $transparency);
        imagesavealpha($result, true);

        imagecopyresampled($result, $image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        /*
		imageconvolution($result, array(
			  array( -1, -1, -1 ),
			  array( -1, 16, -1 ),
			  array( -1, -1, -1 ),
		), 8, 0);
		*/

        $image = $result;
    }

    // 투명이 없을 경우 quality 처리
    if ($quality < 1) {
        $quality = 1;
    }
    if ($quality > 100) {
        $quality = 100;
    }

    // header("cache-control:public, max-age=3600");

    $old_filesize = filesize($source);

    if ($image_extension == 'png' && hasTransparency($image)) {
        header("content-type: image/png");
        ob_start();
        imagepng($image, null, -1);
        $imageString = ob_get_contents();
        ob_end_clean();
    } else {
        header("content-type: image/jpeg");
        ob_start();
        imagejpeg($image, null, $quality);
        $imageString = ob_get_contents();
        ob_end_clean();
    }

	// echo $imageString;
	
	return $imageString;
	/*
    if ($old_filesize < mb_strlen($imageString, '8bit')) { // 기존것 파일이 더 작으면
        // jpg or png  원본과 헤더가 잘못될수도.
        $dat = file_get_contents($source);
        echo $dat;
    } else {
        echo $imageString;
    }
    */

}



function hasTransparency($image): bool
{
    if (!is_resource($image)) {
        // throw new \InvalidArgumentException("Image resource expected. Got: " . gettype($image));
        error_404('Invalid Imageformat.');
    }

    $shrinkFactor      = 64.0;
    $minSquareToShrink = 64.0 * 64.0;

    $width  = imagesx($image);
    $height = imagesy($image);
    $square = $width * $height;

    if ($square <= $minSquareToShrink) {
        [$thumb, $thumbWidth, $thumbHeight] = [$image, $width, $height];
    } else {
        $thumbSquare = $square / $shrinkFactor;
        $thumbWidth    = (int) round($width / sqrt($shrinkFactor));
        $thumbWidth < 1 and $thumbWidth = 1;
        $thumbHeight = (int) round($thumbSquare / $thumbWidth);
        $thumb         = imagecreatetruecolor($thumbWidth, $thumbHeight);
        imagealphablending($thumb, false);
        imagecopyresized($thumb, $image, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
    }

    for ($i = 0; $i < $thumbWidth; $i++) {
        for ($j = 0; $j < $thumbHeight; $j++) {
            if (imagecolorat($thumb, $i, $j) & 0x7F000000) {
                return true;
            }
        }
    }

    return false;
}


