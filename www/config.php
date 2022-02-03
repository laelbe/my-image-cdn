<?php
/**
 * my-image-cdn (https://github.com/laelbe/my-image-cdn)
 * configuration file.
 */
if (!defined('_MYCDN_')) {
    exit();
}

// origin hostname
$original_host = 'https://blog.lael.be';

// mycdn agentname
$mycdn_agentname = 'MYCDN/1.0';

// allowed extensions. please write in lowercase.
$allowed_extension = [];
$allowed_extension[] = 'png';
$allowed_extension[] = 'jpg';
$allowed_extension[] = 'jpeg';
$allowed_extension[] = 'gif';
$allowed_extension[] = 'css';
$allowed_extension[] = 'js';

// lowercase headername + colon(:) + custom value
// SEE : https://developer.mozilla.org/ko/docs/Web/HTTP/Headers
$custom_headers = [];
// $custom_headers[] = 'authorization: Bearer MySampleToken';
// $custom_headers[] = 'authorization: Basic YWxhZGRpbjpvcGVuc2VzYW1l';
// $custom_headers[] = 'x-mycdn-worker: ' . gethostname();
// $custom_headers[] = 'x-api-key: my_custom_key';

$use_resize = TRUE; // EDIT THIS VALUE

$resize_imagecode = [];

if ($use_resize === TRUE) {
	$resize_imagecode = [
	    'w360q50' => [
	        'w' => 360,
	        'q' => 50,
	    ],
	    'w360q100' => [
	        'w' => 360,
	        'q' => 100,
	    ],
	    'w1024q100' => array(
	        'w' => 1024,
	        'q' => 100,
	    ),
	    'original' => [
		    
	    ],
	    '100x100q80zc' => [
	        'w' => 100,
	        'h' => 100,
	        'q' => 80,
	        'zc' => TRUE,
	    ],
	    '100x100q80' => [
	        'w' => 100,
	        'h' => 100,
	        'q' => 80,
	        'zc' => FALSE,
	    ],
	    'mysize1' => [
	        'w' => 100,
	        'h' => 100,
	        'q' => 80,
	        'zc' => TRUE,
	    ],
	];

}